<?php

namespace App\Services;

use App\Models\AddonService;
use App\Models\AiCredit;
use App\Models\AiCreditTransaction;
use App\Models\Invoice\Invoice;
use App\Models\UserAddonPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddonActivationService
{
    /**
     * Activate addon from paid invoice
     */
    public function activateFromInvoice(Invoice $invoice)
    {
        // Only process addon invoices
        if ($invoice->type !== 'addon_service') {
            return;
        }

        $metadata = $invoice->metadata;
        $addonId = $metadata['addon_service_id'] ?? null;

        if (!$addonId) {
            Log::error('Addon activation failed: No addon_service_id in invoice metadata', [
                'invoice_id' => $invoice->id,
            ]);
            return;
        }

        // Find the purchase record
        $purchase = UserAddonPurchase::where('invoice_id', $invoice->id)->first();

        if (!$purchase) {
            Log::error('Addon activation failed: Purchase record not found', [
                'invoice_id' => $invoice->id,
            ]);
            return;
        }

        if ($purchase->status === 'completed') {
            return; // Already activated
        }

        // Get addon service
        $addon = AddonService::find($addonId);

        if (!$addon) {
            Log::error('Addon activation failed: Addon service not found', [
                'addon_id' => $addonId,
            ]);
            return;
        }

        // Activate based on addon type
        try {
            switch ($addon->type) {
                case 'credits':
                    $this->activateCredits($purchase, $invoice, $addon);
                    break;

                case 'feature':
                    $this->activateFeature($purchase, $invoice, $addon);
                    break;

                case 'one_time':
                    $this->activateOneTime($purchase, $invoice, $addon);
                    break;
            }

            // Mark as completed
            $purchase->update([
                'status' => 'completed',
                'activated_at' => now(),
            ]);

            Log::info('Addon activated successfully', [
                'purchase_id' => $purchase->id,
                'addon_name' => $addon->name,
                'user_id' => $purchase->user_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Addon activation error', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
            ]);

            $purchase->update(['status' => 'failed']);
        }
    }

    /**
     * Activate credits addon
     */
    protected function activateCredits($purchase, $invoice, $addon)
    {
        $totalCredits = ($purchase->credits_received ?? 0) + ($purchase->bonus_received ?? 0);

        DB::transaction(function () use ($purchase, $totalCredits, $addon) {
            // Get or create credit account
            $aiCredit = AiCredit::firstOrCreate([
                'user_id' => $purchase->user_id,
                'tenant_id' => $purchase->tenant_id,
            ], [
                'balance' => 0,
                'reserved' => 0,
                'total_purchased' => 0,
                'total_used' => 0,
            ]);

            // Add credits
            $aiCredit->add($totalCredits);

            // Log transaction
            AiCreditTransaction::create([
                'user_id' => $purchase->user_id,
                'tenant_id' => $purchase->tenant_id,
                'type' => 'purchase',
                'amount' => $totalCredits,
                'balance_after' => $aiCredit->balance,
                'description' => "Purchased: {$addon->name}",
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'invoice_id' => $purchase->invoice_id,
                    'addon_id' => $addon->id,
                    'base_credits' => $purchase->credits_received,
                    'bonus_credits' => $purchase->bonus_received,
                ],
            ]);
        });
    }

    /**
     * Activate feature addon (for future use)
     */
    protected function activateFeature($purchase, $invoice, $addon)
    {
        // For future features like "Premium Support for 30 days"
        // You can implement this when needed

        if ($addon->duration_days) {
            $purchase->update([
                'expires_at' => now()->addDays($addon->duration_days),
            ]);
        }
    }

    /**
     * Activate one-time service addon (for future use)
     */
    protected function activateOneTime($purchase, $invoice, $addon)
    {
        // For one-time services like "Custom Template Design"
        // You can implement this when needed
    }
}
