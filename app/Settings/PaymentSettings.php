<?php

namespace App\Settings;

use App\Traits\HasDynamicSettings;
use Spatie\LaravelSettings\Settings;

class PaymentSettings extends Settings
{
    use HasDynamicSettings;

    // Core Payment Settings (Static)
    public string $default_gateway = 'offline';

    // Offline Payment Settings
    public bool $offline_enabled = false;

    public string $offline_description = 'Pay via direct bank transfer.';

    public string $offline_instructions = 'Please transfer the amount to our bank account and email the receipt.';

    // Core Gateway Settings (Static - these are always available)
    public bool $stripe_enabled = false;

    public string $stripe_key = '';

    public string $stripe_secret = '';

    public string $stripe_webhook_secret = '';

    public string $stripe_webhook_id = '';

    public bool $razorpay_enabled = false;

    public string $razorpay_key_id = '';

    public string $razorpay_key_secret = '';

    public string $razorpay_webhook_secret = '';

    public bool $paypal_enabled = false;

    public string $paypal_mode = 'sandbox';

    public string $paypal_client_id = '';

    public string $paypal_client_secret = '';

    public string $paypal_webhook_id = '';

    public string $paypal_brand_name = 'WhatsMarks';

    public bool $paystack_enabled = false;

    public string $paystack_public_key = '';

    public string $paystack_secret_key = '';

    // APG (Alfa Payment Gateway) Settings
    public bool $apg_enabled = false;

    public string $apg_merchant_id = '';

    public string $apg_store_id = '';

    public string $apg_merchant_hash = '';

    public string $apg_merchant_username = '';

    public string $apg_merchant_password = '';

    public string $apg_encryption_key1 = '';

    public string $apg_encryption_key2 = '';

    public string $apg_environment = 'production';

    // Tax Settings
    public bool $tax_enabled = false;

    public static function group(): string
    {
        return 'payment';
    }

    /**
     * Get all available gateway settings (static + dynamic)
     */
    public function getAllGatewaySettings(): array
    {
        $this->loadDynamicSettings();

        $static = $this->toArray();
        $dynamic = [];

        // Get dynamic property values
        foreach ($this->dynamicSettings as $key => $defaultValue) {
            $dynamic[$key] = $this->$key; // This will use __get
        }

        return array_merge($static, $dynamic);
    }

    /**
     * Check if a specific gateway is enabled (works for both static and dynamic)
     */
    public function isGatewayEnabled(string $gateway): bool
    {
        $enabledProperty = $gateway . '_enabled';

        // Try dynamic first, then static
        if ($this->isDynamicProperty($enabledProperty)) {
            return (bool) $this->$enabledProperty;
        }

        // Check if it exists as static property
        if (property_exists($this, $enabledProperty)) {
            return (bool) $this->$enabledProperty;
        }

        return false;
    }
}
