<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\AlfaPaymentService;
use App\Models\ApgTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApgPaymentController extends Controller
{
    protected $apgService;

    public function __construct(AlfaPaymentService $apgService)
    {
        $this->apgService = $apgService;
    }

    /**
     * Initiate payment process
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function initiatePayment(Request $request)
    {
        // Log to dedicated file
        $logFile = storage_path('logs/paymentgateway.log');
        $logData = [
            'timestamp' => now()->toDateTimeString(),
            'action' => 'INITIATE_PAYMENT',
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
        ];

        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_type' => 'required|string',
            'related_id' => 'nullable|integer',
        ]);

        try {
            // Generate unique transaction reference number
            $transactionRef = 'TXN' . time() . Str::random(6);

            file_put_contents($logFile, "Generated Transaction Ref: $transactionRef\n\n", FILE_APPEND);

            // Create transaction record
            $transaction = $this->apgService->createTransaction([
                'transaction_reference_number' => $transactionRef,
                'user_id' => Auth::id(),
                'tenant_id' => null, // Set to null for testing
                'amount' => $request->amount,
                'transaction_type' => $request->transaction_type,
                'related_id' => $request->related_id,
                'request_data' => $request->all(),
            ]);

            file_put_contents($logFile, "Transaction Created: " . json_encode($transaction->toArray(), JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            // Step 1: Initiate handshake
            file_put_contents($logFile, "Calling APG Handshake...\n", FILE_APPEND);
            $handshakeResponse = $this->apgService->initiateHandshake($transactionRef);

            file_put_contents($logFile, "Handshake Response: " . json_encode($handshakeResponse, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            if (isset($handshakeResponse['success']) && $handshakeResponse['success'] === 'true') {
                // Store auth token
                $transaction->update([
                    'auth_token' => $handshakeResponse['AuthToken'],
                    'status' => 'processing',
                ]);

                file_put_contents($logFile, "SUCCESS: Auth token stored, redirecting to handshake view\n\n", FILE_APPEND);

                // Return view with form to auto-submit to callback
                return view('payment.apg.handshake', [
                    'authToken' => $handshakeResponse['AuthToken'],
                    'returnUrl' => $handshakeResponse['ReturnURL'],
                    'transaction' => $transaction,
                ]);
            } else {
                $errorMsg = $handshakeResponse['ErrorMessage'] ?? 'Handshake failed';
                file_put_contents($logFile, "ERROR: Handshake failed - $errorMsg\n\n", FILE_APPEND);

                $transaction->update([
                    'status' => 'failed',
                    'error_message' => $errorMsg,
                ]);

                return redirect()->back()->with('error', 'Payment initiation failed: ' . $errorMsg);
            }

        } catch (\Exception $e) {
            $errorData = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
            file_put_contents($logFile, "EXCEPTION: " . json_encode($errorData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            Log::error('APG Payment Initiation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Handle callback from handshake (Step 1.5)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function handleCallback(Request $request)
    {
        $authToken = $request->query('auth_token');

        if (!$authToken) {
            return redirect()->route('home')->with('error', 'Invalid payment session.');
        }

        // Find transaction by auth token
        $transaction = ApgTransaction::where('auth_token', $authToken)->first();

        if (!$transaction) {
            return redirect()->route('home')->with('error', 'Transaction not found.');
        }

        // Step 2: Prepare payment request
        $paymentData = $this->apgService->processPayment(
            $authToken,
            $transaction->amount,
            $transaction->transaction_reference_number
        );

        // Return view with auto-submit form to APG payment page
        return view('payment.apg.payment', [
            'paymentUrl' => $paymentData['url'],
            'params' => $paymentData['params'],
            'transaction' => $transaction,
        ]);
    }

    /**
     * Handle return from APG after payment
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleReturn(Request $request)
    {
        // APG returns order ID with alias 'O'
        $orderId = $request->query('O');
        $transactionStatus = $request->query('TS'); // P = Paid, F = Failed
        $responseCode = $request->query('RC');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Invalid payment response.');
        }

        try {
            // Inquire transaction status from APG
            $statusResponse = $this->apgService->inquireTransaction($orderId);

            // Update transaction with response
            $transaction = $this->apgService->updateTransaction($orderId, $statusResponse);

            if (!$transaction) {
                return redirect()->route('home')->with('error', 'Transaction not found.');
            }

            // Check transaction status
            if ($transaction->isPaid()) {
                // Payment successful - handle post-payment logic here
                $this->handleSuccessfulPayment($transaction);

                return redirect()->route('payment.success', ['transaction' => $transaction->id])
                    ->with('success', 'Payment completed successfully!');
            } else {
                return redirect()->route('payment.failed', ['transaction' => $transaction->id])
                    ->with('error', 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('APG Return Handler Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle IPN (Instant Payment Notification) webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleIpn(Request $request)
    {
        try {
            // APG sends IPN with 'url' parameter
            $ipnUrl = $request->input('url');

            if (!$ipnUrl) {
                return response()->json(['error' => 'Invalid IPN request'], 400);
            }

            // Extract transaction reference from URL
            // URL format: https://payments.bankalfalah.com/HS/api/IPN/OrderStatus/123/000456/TXN123
            $urlParts = explode('/', $ipnUrl);
            $transactionRef = end($urlParts);

            // Make GET request to IPN URL to get transaction status
            $statusResponse = $this->apgService->inquireTransaction($transactionRef);

            // Update transaction
            $transaction = $this->apgService->updateTransaction($transactionRef, $statusResponse);

            if ($transaction && $transaction->isPaid()) {
                // Handle successful payment
                $this->handleSuccessfulPayment($transaction);
            }

            return response()->json(['success' => true, 'message' => 'IPN processed']);

        } catch (\Exception $e) {
            Log::error('APG IPN Error: ' . $e->getMessage());
            return response()->json(['error' => 'IPN processing failed'], 500);
        }
    }

    /**
     * Handle successful payment logic
     * 
     * @param ApgTransaction $transaction
     * @return void
     */
    protected function handleSuccessfulPayment(ApgTransaction $transaction)
    {
        // Implement your post-payment logic here
        // For example:
        // - Activate subscription
        // - Mark invoice as paid
        // - Send confirmation email
        // - Update user credits

        Log::info('Payment successful', [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'user_id' => $transaction->user_id,
        ]);

        // Example: If it's a subscription payment
        if ($transaction->transaction_type === 'subscription' && $transaction->related_id) {
            // Activate subscription logic here
            // $subscription = Subscription::find($transaction->related_id);
            // $subscription->activate();
        }
    }

    /**
     * Show payment success page
     * 
     * @param ApgTransaction $transaction
     * @return \Illuminate\View\View
     */
    public function success(ApgTransaction $transaction)
    {
        return view('payment.apg.success', compact('transaction'));
    }

    /**
     * Show payment failed page
     * 
     * @param ApgTransaction $transaction
     * @return \Illuminate\View\View
     */
    public function failed(ApgTransaction $transaction)
    {
        return view('payment.apg.failed', compact('transaction'));
    }

    /**
     * Get transaction status (AJAX endpoint)
     * 
     * @param string $transactionRef
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatus($transactionRef)
    {
        try {
            $statusResponse = $this->apgService->inquireTransaction($transactionRef);
            return response()->json($statusResponse);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
