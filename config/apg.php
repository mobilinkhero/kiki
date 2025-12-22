<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alfa Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Alfa Payment Gateway (APG) integration
    |
    */

    'enabled' => env('APG_ENABLED', true),

    'environment' => env('APG_ENVIRONMENT', 'production'), // sandbox or production

    'credentials' => [
        'merchant_id' => env('APG_MERCHANT_ID'),
        'store_id' => env('APG_STORE_ID'),
        'merchant_hash' => env('APG_MERCHANT_HASH'),
        'merchant_username' => env('APG_MERCHANT_USERNAME'),
        'merchant_password' => env('APG_MERCHANT_PASSWORD'),
    ],

    'encryption' => [
        'key1' => env('APG_ENCRYPTION_KEY1'),
        'key2' => env('APG_ENCRYPTION_KEY2'),
    ],

    'urls' => [
        'production' => [
            'handshake' => 'https://payments.bankalfalah.com/HS/HS/HS',
            'payment' => 'https://payments.bankalfalah.com/SSO/SSO/SSO',
            'ipn' => 'https://payments.bankalfalah.com/HS/api/IPN/OrderStatus',
        ],
        'sandbox' => [
            'handshake' => 'https://sandbox.bankalfalah.com/HS/HS/HS',
            'payment' => 'https://sandbox.bankalfalah.com/SSO/SSO/SSO',
            'ipn' => 'https://sandbox.bankalfalah.com/HS/api/IPN/OrderStatus',
        ],
    ],

    'channel_id' => '1001', // Page redirection channel

    'transaction_type_id' => '3', // Credit/Debit Card

    'currency' => env('APG_CURRENCY', 'PKR'),

    'return_url' => env('APG_RETURN_URL', 'https://soft.chatvoo.com/payment/alfa/return'),

    'callback_url' => env('APG_CALLBACK_URL', 'https://soft.chatvoo.com/payment/alfa/callback'),

    'ipn_url' => env('APG_IPN_URL', 'https://soft.chatvoo.com/payment/alfa/ipn'),

    // Logging
    'log_requests' => env('APG_LOG_REQUESTS', true),

    // Timeout settings
    'timeout' => 30, // seconds
];
