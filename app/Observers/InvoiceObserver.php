<?php

namespace App\Observers;

use App\Models\Invoice\Invoice;
use App\Services\AddonActivationService;
use Illuminate\Support\Facades\Log;

class InvoiceObserver
{
    protected $addonActivation;

    public function __construct(AddonActivationService $addonActivation)
    {
        $this->addonActivation = $addonActivation;
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice)
    {
        // Check if status changed to 'paid'
        if ($invoice->isDirty('status') && $invoice->status === Invoice::STATUS_PAID) {

            Log::info('Invoice paid, checking for addon activation', [
                'invoice_id' => $invoice->id,
                'type' => $invoice->type,
            ]);

            // Activate addon if it's an addon purchase
            if ($invoice->type === 'addon_service') {
                $this->addonActivation->activateFromInvoice($invoice);
            }

            // Your existing subscription activation code can stay here
            // if ($invoice->type === 'subscription') {
            //     // ... existing code ...
            // }
        }
    }
}
