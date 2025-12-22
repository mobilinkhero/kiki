<?php

namespace App\Services\PaymentGateways;

use App\Models\Invoice\Invoice;
use App\Models\Transaction;
use App\Services\Billing\TransactionResult;
use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;
use App\Settings\PaymentSettings;

/**
 * APG (Alfa Payment Gateway) Payment Gateway
 * 
 * Bank Alfalah payment gateway integration for Pakistani market.
 * Supports Alfa Wallet, Bank Account, and Credit/Debit Card payments.
 */
class ApgPaymentGateway implements PaymentGatewayInterface
{
    protected $settings;
    protected $active = false;

    public function __construct(PaymentSettings $settings)
    {
        $this->settings = $settings;
        $this->validate();
    }

    /**
     * Validate gateway configuration
     */
    protected function validate()
    {
        $this->active = $this->settings->apg_enabled
            && !empty($this->settings->apg_merchant_id)
            && !empty($this->settings->apg_store_id)
            && !empty($this->settings->apg_encryption_key1)
            && !empty($this->settings->apg_encryption_key2);
    }

    public function getName(): string
    {
        return 'APG (Alfa Payment Gateway)';
    }

    public function getType(): string
    {
        return 'apg';
    }

    public function getDescription(): string
    {
        return 'Bank Alfalah payment gateway - Alfa Wallet, Bank Account, and Credit/Debit Cards';
    }

    public function getShortDescription(): string
    {
        return 'Bank Alfalah';
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getSettingsUrl(): string
    {
        return route('admin.settings.payment.apg');
    }

    public function getCheckoutUrl(Invoice $invoice): string
    {
        // For APG, we need to initiate payment first, so redirect to initiate endpoint
        return route('tenant.payment.apg.initiate', [
            'subdomain' => tenant('id'),
            'invoice' => $invoice->id
        ]);
    }

    public function supportsAutoBilling(): bool
    {
        return false; // APG doesn't support recurring billing
    }

    public function autoCharge(Invoice $invoice)
    {
        throw new \Exception('APG does not support automatic billing');
    }

    public function getAutoBillingDataUpdateUrl(string $returnUrl = '/'): string
    {
        throw new \Exception('APG does not support automatic billing');
    }

    public function verify(Transaction $transaction): TransactionResult
    {
        // APG verification is handled by the ApgPaymentController
        // This method is called after payment completion

        if ($transaction->status === 'paid') {
            return new TransactionResult(TransactionResult::RESULT_DONE, 'Payment completed successfully');
        }

        if ($transaction->status === 'failed') {
            return new TransactionResult(TransactionResult::RESULT_FAILED, 'Payment failed');
        }

        return new TransactionResult(TransactionResult::RESULT_PENDING, 'Payment is pending');
    }

    public function allowManualReviewingOfTransaction(): bool
    {
        return false; // APG payments are automatically verified
    }

    public function getMinimumChargeAmount($currency)
    {
        // APG works with PKR, minimum is typically 10 PKR
        return $currency === 'PKR' ? 10 : 0;
    }
}
