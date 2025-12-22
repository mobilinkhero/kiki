<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Events\TransactionCreated;
use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use App\Models\TenantCreditBalance;
use App\Models\ApgTransaction;
use App\Services\Billing\TransactionResult;
use App\Services\Payment\AlfaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApgPaymentController extends Controller
{
    /**
     * The APG payment gateway instance.
     */
    protected $gateway;
    protected $apgService;

    /**
     * Create a new controller instance.
     */
    public function __construct(AlfaPaymentService $apgService)
    {
        $this->gateway = app('billing.manager')->gateway('apg');
        $this->apgService = $apgService;
    }

    /**
     * Show the checkout page for an invoice (initiates APG payment).
     *
     * @param  string  $subdomain
     * @param  mixed  $invoiceId
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function checkout(Request $request, string $subdomain, $invoiceId)
    {
        $invoice = Invoice::with('taxes')
            ->where('id', $invoiceId)
            ->where('tenant_id', tenant_id())
            ->where('status', Invoice::STATUS_NEW)
            ->firstOrFail();

        // If the invoice is free, bypass payment
        if ($invoice->isFree()) {
            $invoice->bypassPayment();

            session()->flash('notification', [
                'type' => 'success',
                'message' => t('subscription_activate_message'),
            ]);

            return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]));
        }

        $balance = TenantCreditBalance::getOrCreateBalance(tenant_id(), $invoice->currency_id);
        $remainingCredit = $balance->balance ?? 0;
        $finalAmount = $invoice->finalPayableAmount($remainingCredit);

        // If coupon or credit makes it free, bypass payment
        if ($finalAmount <= 0) {
            $invoice->bypassPayment();

            session()->flash('notification', [
                'type' => 'success',
                'message' => t('subscription_activate_message'),
            ]);

            return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]));
        }

        // Log payment initiation
        $logFile = storage_path('logs/paymentgateway.log');
        file_put_contents($logFile, json_encode([
            'timestamp' => now()->toDateTimeString(),
            'action' => 'APG_CHECKOUT',
            'invoice_id' => $invoice->id,
            'amount' => $finalAmount,
            'tenant_id' => tenant_id(),
        ], JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

        try {
            // Generate unique transaction reference number
            $transactionRef = 'TXN' . time() . Str::random(6);

            // Create APG transaction record
            $apgTransaction = ApgTransaction::create([
                'transaction_reference_number' => $transactionRef,
                'user_id' => Auth::id(),
                'tenant_id' => tenant_id(),
                'amount' => $finalAmount,
                'currency' => 'PKR',
                'transaction_type' => 'subscription',
                'related_id' => $invoice->id,
                'status' => 'pending',
                'request_data' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                ],
            ]);

            // Step 1: Initiate handshake with APG
            $handshakeResponse = $this->apgService->initiateHandshake($transactionRef);

            if (isset($handshakeResponse['success']) && $handshakeResponse['success'] === 'true') {
                // Store auth token
                $apgTransaction->update([
                    'auth_token' => $handshakeResponse['AuthToken'],
                    'status' => 'processing',
                ]);

                // Return view with form to auto-submit to APG
                return view('payment-gateways.apg.checkout', [
                    'invoice' => $invoice,
                    'authToken' => $handshakeResponse['AuthToken'],
                    'returnUrl' => $handshakeResponse['ReturnURL'],
                    'transaction' => $apgTransaction,
                    'remainingCredit' => $remainingCredit,
                ]);
            } else {
                $errorMsg = $handshakeResponse['ErrorMessage'] ?? 'Handshake failed';

                $apgTransaction->update([
                    'status' => 'failed',
                    'error_message' => $errorMsg,
                ]);

                return redirect()->to(tenant_route('tenant.checkout.resume', ['id' => $invoice->id]))
                    ->with('error', 'Payment initiation failed: ' . $errorMsg);
            }

        } catch (\Exception $e) {
            Log::error('APG checkout error', [
                'tenant_id' => tenant_id(),
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->to(tenant_route('tenant.checkout.resume', ['id' => $invoice->id]))
                ->with('error', 'Payment initiation error: ' . $e->getMessage());
        }
    }

    /**
     * Handle return from APG after payment.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handleReturn(Request $request)
    {
        // Log return
        $logFile = storage_path('logs/paymentgateway.log');
        file_put_contents($logFile, json_encode([
            'timestamp' => now()->toDateTimeString(),
            'action' => 'APG_RETURN',
            'params' => $request->all(),
        ], JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

        // APG returns order ID with alias 'O'
        $orderId = $request->query('O');
        $transactionStatus = $request->query('TS'); // P = Paid, F = Failed
        $responseCode = $request->query('RC');

        if (!$orderId) {
            return redirect()->to(tenant_route('tenant.dashboard'))
                ->with('error', 'Invalid payment response.');
        }

        try {
            // Find APG transaction
            $apgTransaction = ApgTransaction::where('transaction_reference_number', $orderId)->first();

            if (!$apgTransaction) {
                return redirect()->to(tenant_route('tenant.dashboard'))
                    ->with('error', 'Transaction not found.');
            }

            // Find related invoice
            $invoice = Invoice::find($apgTransaction->related_id);

            if (!$invoice) {
                return redirect()->to(tenant_route('tenant.dashboard'))
                    ->with('error', 'Invoice not found.');
            }

            // Inquire transaction status from APG
            $statusResponse = $this->apgService->inquireTransaction($orderId);

            // Update APG transaction
            $apgTransaction = $this->apgService->updateTransaction($orderId, $statusResponse);

            // Process invoice payment based on APG transaction status
            if ($apgTransaction->isPaid()) {
                // Process the invoice payment
                $invoice->checkout($this->gateway, function ($invoice, $transaction) use ($apgTransaction, $statusResponse) {
                    // Link billing transaction with APG transaction
                    $transaction->metadata = [
                        'apg_transaction_id' => $apgTransaction->id,
                        'apg_reference' => $apgTransaction->transaction_reference_number,
                        'apg_response' => $statusResponse,
                    ];
                    $transaction->save();

                    // Dispatch event
                    event(new TransactionCreated($transaction->id, $invoice->id));

                    // Return success result
                    return new TransactionResult(TransactionResult::RESULT_DONE, 'Payment completed successfully');
                });

                session()->flash('notification', [
                    'type' => 'success',
                    'message' => t('payment_completed_successfully'),
                ]);

                return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]));
            } else {
                // Payment failed
                session()->flash('notification', [
                    'type' => 'error',
                    'message' => t('payment_failed'),
                ]);

                return redirect()->to(tenant_route('tenant.checkout.resume', ['id' => $invoice->id]));
            }

        } catch (\Exception $e) {
            Log::error('APG return error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return redirect()->to(tenant_route('tenant.dashboard'))
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }

    /**
     * Handle callback from APG (Step 2 - Payment form submission).
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request)
    {
        $authToken = $request->input('auth_token') ?? $request->query('auth_token');

        if (!$authToken) {
            return redirect()->to(tenant_route('tenant.dashboard'))
                ->with('error', 'Invalid payment session.');
        }

        // Find transaction by auth token and ensure it belongs to this tenant
        $transaction = ApgTransaction::where('auth_token', $authToken)
            ->where('tenant_id', tenant_id())
            ->first();

        if (!$transaction) {
            return redirect()->to(tenant_route('tenant.dashboard'))
                ->with('error', 'Transaction not found.');
        }

        try {
            // Step 2: Prepare payment request
            $paymentMethod = $transaction->request_data['payment_method'] ?? null;

            $paymentData = $this->apgService->processPayment(
                $authToken,
                $transaction->amount,
                $transaction->transaction_reference_number,
                null, // return URL (uses default)
                $paymentMethod
            );

            // Redirect directly to bank if the service provided a URL and params
            if (isset($paymentData['url']) && !empty($paymentData['url'])) {
                return view('payment-gateways.apg.payment', [
                    'paymentUrl' => $paymentData['url'],
                    'params' => $paymentData['params'],
                    'transaction' => $transaction,
                ]);
            }

            return redirect()->to(tenant_route('tenant.checkout.resume', ['id' => $transaction->related_id]))
                ->with('error', 'Could not prepare payment information.');

        } catch (\Exception $e) {
            Log::error('APG callback error', [
                'error' => $e->getMessage(),
                'token' => $authToken
            ]);

            return redirect()->to(tenant_route('tenant.dashboard'))
                ->with('error', 'Payment redirection error.');
        }
    }
}
