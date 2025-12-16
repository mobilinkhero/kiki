<?php

namespace App\Services\PaymentGateways;

use App\Models\Invoice\Invoice;
use App\Services\PaymentGateways\Contracts\PaymentGatewayInterface;

class AlfaPaymentGateway implements PaymentGatewayInterface
{
    protected $merchantId;
    protected $storeId;
    protected $hashKey;

    public function __construct($merchantId, $storeId, $hashKey)
    {
        $this->merchantId = $merchantId;
        $this->storeId = $storeId;
        $this->hashKey = $hashKey;
    }

    public function getName(): string
    {
        return 'Alfa';
    }

    public function getDescription(): string
    {
        return 'Pay securely with your Bank Alfalah account or Card.';
    }

    public function getCheckoutUrl(Invoice $invoice): string
    {
        return tenant_route('tenant.payment.alfa.checkout', ['invoice' => $invoice->id]);
    }

    public function supportsAutoBilling(): bool
    {
        return false;
    }

    public function isActive(): bool
    {
        return !empty($this->merchantId) && !empty($this->storeId) && !empty($this->hashKey);
    }

    public function getType(): string
    {
        return 'alfa';
    }

    public function getShortDescription(): string
    {
        return 'Bank Transfer/Card';
    }

    public function getSettingsUrl(): string
    {
        return '';
    }

    public function autoCharge(Invoice $invoice)
    {
        return null;
    }

    public function getAutoBillingDataUpdateUrl(string $returnUrl = '/'): string
    {
        return '';
    }

    public function verify(\App\Models\Transaction $transaction): \App\Services\Billing\TransactionResult
    {
        if ($transaction->status === 'success') {
            return new \App\Services\Billing\TransactionResult(
                \App\Services\Billing\TransactionResult::RESULT_DONE,
                'Payment Processed Successfully'
            );
        }
        return new \App\Services\Billing\TransactionResult(
            \App\Services\Billing\TransactionResult::RESULT_FAILED,
            'Payment Failed'
        );
    }

    public function allowManualReviewingOfTransaction(): bool
    {
        return false;
    }

    public function getMinimumChargeAmount($currency)
    {
        return 1;
    }
}
