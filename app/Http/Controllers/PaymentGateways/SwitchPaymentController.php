<?php

namespace App\Http\Controllers\PaymentGateways;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use App\Models\Transaction;
use App\Services\Billing\TransactionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SwitchPaymentController extends Controller
{
    /**
     * Handle success return from Switch gateway.
     */
    public function success(Request $request, $subdomain)
    {
        // Switch usually sends some parameters back
        $invoiceId = $request->query('invoice_id'); // Example param
        $reference = $request->query('ref');        // Example param

        Log::info('Switch Payment Success Return', [
            'params' => $request->all(),
            'subdomain' => $subdomain
        ]);

        if (!$invoiceId) {
            return redirect()->to(tenant_route('tenant.dashboard'))
                ->with('error', 'Invalid payment session.');
        }

        $invoice = Invoice::findOrFail($invoiceId);

        // Here you would verify the payment with Switch API using the reference
        // For now, we flash a success message if you trust the return (not recommended for production)
        // Ideally, use a service to verify and mark as paid.

        if ($invoice->isPaid()) {
            return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]));
        }

        // If not processed yet, show pending or try to verify
        return redirect()->to(tenant_route('tenant.subscription.thank-you', ['invoice' => $invoice->id]))
            ->with('success', 'Your payment is being processed.');
    }

    /**
     * Handle failure return from Switch gateway.
     */
    public function failed(Request $request, $subdomain)
    {
        $invoiceId = $request->query('invoice_id');

        Log::warning('Switch Payment Failed Return', [
            'params' => $request->all(),
            'subdomain' => $subdomain
        ]);

        if ($invoiceId) {
            return redirect()->to(tenant_route('tenant.checkout.resume', ['id' => $invoiceId]))
                ->with('error', 'Payment was cancelled or failed.');
        }

        return redirect()->to(tenant_route('tenant.dashboard'))
            ->with('error', 'Payment failed.');
    }
}
