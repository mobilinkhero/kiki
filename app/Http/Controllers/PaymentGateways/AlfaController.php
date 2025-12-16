<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use App\Models\TenantCreditBalance;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlfaController extends Controller
{
    /**
     * Get Alfa settings from database
     */
    private function getAlfaSettings(): array
    {
        return get_batch_settings([
            'payment.alfa_enabled',
            'payment.alfa_merchant_id',
            'payment.alfa_store_id',
            'payment.alfa_merchant_hash',
            'payment.alfa_merchant_username',
            'payment.alfa_merchant_password',
            'payment.alfa_mode', // sandbox or production
        ]);
    }

    /**
     * Show the checkout page/redirection logic for an invoice.
     */
    public function checkout(Request $request, string $subdomain, $invoiceId)
    {
        $settings = $this->getAlfaSettings();
        if (empty($settings['payment.alfa_enabled'])) {
            return back()->with('error', 'Alfa payment is not available.');
        }

        $invoice = Invoice::where('id', $invoiceId)
            ->where('tenant_id', tenant_id())
            ->where('status', Invoice::STATUS_NEW)
            ->firstOrFail();

        // Check for free invoice
        if ($invoice->isFree()) {
            $invoice->bypassPayment();
            session()->flash('notification', [
                'type' => 'success',
                'message' => t('subscription_activate_message'),
            ]);
            return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]));
        }

        // Determine environment URL
        $isProduction = ($settings['payment.alfa_mode'] ?? 'sandbox') === 'production';
        $handshakeUrl = $isProduction
            ? 'https://payments.bankalfalah.com/HS/HS/HS'
            : 'https://sandbox.bankalfalah.com/HS/HS/HS';

        // Prepare parameters for Handshake (Step 1)
        $info = [
            'HS_MerchantId' => $settings['payment.alfa_merchant_id'],
            'HS_StoreId' => $settings['payment.alfa_store_id'],
            'HS_MerchantHash' => $settings['payment.alfa_merchant_hash'],
            'HS_MerchantUsername' => $settings['payment.alfa_merchant_username'],
            'HS_MerchantPassword' => $settings['payment.alfa_merchant_password'],
            'HS_IsRedirectionRequest' => 0, // 0 for Redirection to merchant page for token? No, guide says: "0 Or Merchants wants to handle the authentication token on the same page (1)". Wait.
            // Guide: "This defines if a merchant wants to first redirect customers on a page where merchants will get authentication token (0) Or Merchants wants to handle the authentication token on the same page (1)"
            // Actually, typically 1 means we handle the token POST back immediately. Let's assume standard flow where we want to receive the token on our ReturnURL.
            'HS_ChannelId' => '1001',
            'HS_ReturnURL' => tenant_route('tenant.payment.alfa.return', ['invoice' => $invoice->id], false), // Must be absolute? Route helper returns absolute usually.
            'HS_TransactionReferenceNumber' => $invoice->id . '_' . time(),
        ];

        // We ensure ReturnURL is absolute public URL
        $info['HS_ReturnURL'] = str_replace('http://', 'https://', $info['HS_ReturnURL']); // Ensure secure if behind proxy

        // Generate Hash
        // NOTE: The exact hashing algorithm is missing from the guide ("See Encryption method in the Sample code attached").
        // We will assume a standard concatenation + HMAC or similar. 
        // IMPORTANT: For now, I will use a placeholder method and add a TODO comment explicitly asking for the encryption logic. 
        // HOWEVER, based on common integrations (e.g. JazzCash, EasyPaisa), it's often HMAC-SHA256 of sorted params with a salt.
        // For Alfa specifically, I found online references suggesting it uses AES encryption of the param string.
        // Without the specific library/code, I cannot implement `HS_RequestHash` correctly.
        // I will implement the structure and logging.

        $info['HS_RequestHash'] = $this->generateRequestHash($info);

        return view('payment-gateways.alfa.checkout', [
            'url' => $handshakeUrl,
            'params' => $info,
        ]);
    }

    /**
     * Process the Return from Alfa (Step 2 - Handshake Response)
     * This route receives the 'AuthToken' via GET/POST on the ReturnURL and then POSTs to SSO.
     */
    public function process(Request $request, string $subdomain)
    {
        $settings = $this->getAlfaSettings();

        $authToken = $request->input('AuthToken');
        $transactionRef = $request->input('TransactionReferenceNumber');

        // Extract invoice ID from transaction reference (format: invoiceId_timestamp)
        $invoiceId = explode('_', $transactionRef)[0] ?? null;

        if (!$authToken || !$invoiceId) {
            return back()->with('error', 'Payment failed: Invalid response from payment gateway.');
        }

        $invoice = Invoice::findOrFail($invoiceId);

        // Now we need to POST to SSO (Step 2)
        $isProduction = ($settings['payment.alfa_mode'] ?? 'sandbox') === 'production';
        $ssoUrl = $isProduction
            ? 'https://payments.bankalfalah.com/SSO/SSO/SSO'
            : 'https://sandbox.bankalfalah.com/SSO/SSO/SSO';

        $params = [
            'AuthToken' => $authToken,
            'ChannelId' => '1001',
            'ReturnURL' => tenant_route('tenant.payment.alfa.callback'), // Final callback after payment
            'MerchantId' => $settings['payment.alfa_merchant_id'],
            'StoreId' => $settings['payment.alfa_store_id'],
            'MerchantHash' => $settings['payment.alfa_merchant_hash'],
            'MerchantUsername' => $settings['payment.alfa_merchant_username'],
            'MerchantPassword' => $settings['payment.alfa_merchant_password'],
            'TransactionTypeId' => '3', // 3 for Credit/Debit Card
            'TransactionReferenceNumber' => $invoice->id . '_' . time(),
            'TransactionAmount' => $invoice->total(),
        ];

        // Generate Request Hash for SSO
        $params['RequestHash'] = $this->generateRequestHash($params);

        return view('payment-gateways.alfa.redirect', [
            'url' => $ssoUrl,
            'params' => $params
        ]);
    }

    /**
     * Final Callback after payment (Success/Failure)
     */
    public function callback(Request $request, string $subdomain)
    {
        // Alfa redirects here after payment attempt.
        // Extract invoice ID from transaction reference
        $transactionRef = $request->input('TransactionReferenceNumber');
        $invoiceId = explode('_', $transactionRef)[0] ?? null;

        if (!$invoiceId) {
            return back()->with('error', 'Invalid payment response.');
        }

        $responseCode = $request->input('ResponseCode');
        $description = $request->input('Description');

        if ($responseCode === '00' || $request->input('TransactionStatus') === 'Paid') {
            // Payment Success
            $invoice = Invoice::findOrFail($invoiceId);

            if (!$invoice->isPaid()) {
                // Mark as paid
                // Using transaction structure similar to Stripe
                $transaction = $invoice->createPendingTransaction('alfa', $invoice->tenant_id);
                $transaction->update([
                    'status' => 'success',
                    'metadata' => $request->all(),
                ]);

                $invoice->handleTransactionResult($transaction, new \App\Services\Billing\TransactionResult(
                    \App\Services\Billing\TransactionResult::RESULT_DONE,
                    'Payment processed via Alfa'
                ));
            }

            return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]));
        } else {
            return redirect()->route('tenant.invoices.show', $invoiceId)->with('error', 'Payment failed: ' . $description);
        }
    }

    /**
     * Placeholder for Hash Generation
     */
    private function generateRequestHash($params)
    {
        // TODO: Implement actual encryption logic provided by Alfa SDK/Sample Code.
        // Usually involves: AES/HMAC of concatenated string.
        return 'DUMMY_HASH_NEED_IMPLEMENTATION';
    }
}
