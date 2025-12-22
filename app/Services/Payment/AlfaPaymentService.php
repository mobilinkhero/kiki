<?php

namespace App\Services\Payment;

use App\Models\ApgTransaction;
use App\Models\ApgPaymentLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AlfaPaymentService
{
    protected $config;
    protected $urls;

    public function __construct()
    {
        // Load settings from database instead of config/env
        $settings = app(\App\Settings\PaymentSettings::class);

        $environment = $settings->apg_environment ?: 'production';

        // Build config array from database settings
        $this->config = [
            'enabled' => $settings->apg_enabled,
            'environment' => $environment,
            'credentials' => [
                'merchant_id' => $settings->apg_merchant_id,
                'store_id' => $settings->apg_store_id,
                'merchant_hash' => $settings->apg_merchant_hash,
                'merchant_username' => $settings->apg_merchant_username,
                'merchant_password' => $settings->apg_merchant_password,
            ],
            'encryption' => [
                'key1' => $settings->apg_encryption_key1,
                'key2' => $settings->apg_encryption_key2,
            ],
            'channel_id' => '1001',
            'currency' => 'PKR',
            'return_url' => url('/payment/alfa/return'),
            'callback_url' => url('/payment/alfa/callback'),
            'ipn_url' => url('/payment/alfa/ipn'),
            'log_requests' => true,
            'timeout' => 30,
        ];

        // Set URLs based on environment
        $this->urls = [
            'handshake' => $environment === 'production'
                ? 'https://payments.bankalfalah.com/HS/HS/HS'
                : 'https://sandbox.bankalfalah.com/HS/HS/HS',
            'transaction' => $environment === 'production'
                ? 'https://payments.bankalfalah.com/SSO/SSO/SSO'
                : 'https://sandbox.bankalfalah.com/SSO/SSO/SSO',
        ];
    }

    /**
     * Generate AES encrypted hash (matching APG's JavaScript implementation)
     * Uses AES-128-CBC with Key1 as key and Key2 as IV
     */
    public function generateHash($data)
    {
        $key1 = $this->config['encryption']['key1']; // 16 bytes - AES key
        $key2 = $this->config['encryption']['key2']; // 16 bytes - IV

        // AES-128-CBC encryption (matching CryptoJS.AES.encrypt)
        $encrypted = openssl_encrypt(
            $data,
            'aes-128-cbc',
            $key1,
            OPENSSL_RAW_DATA,
            $key2
        );

        return base64_encode($encrypted);
    }

    /**
     * Generate request hash for handshake
     * Hash is created from all request parameters as query string
     */
    public function generateRequestHash($params)
    {
        // Build query string from parameters (excluding the hash itself)
        $queryParts = [];
        foreach ($params as $key => $value) {
            if ($key !== 'HS_RequestHash') {
                $queryParts[] = $key . '=' . $value;
            }
        }
        $queryString = implode('&', $queryParts);

        return $this->generateHash($queryString);
    }

    /**
     * Initiate handshake (Step 1)
     */
    public function initiateHandshake($transactionReferenceNumber, $returnUrl = null)
    {
        $logFile = storage_path('logs/paymentgateway.log');

        $credentials = $this->config['credentials'];
        $returnUrl = $returnUrl ?? $this->config['callback_url'];

        // Build all parameters first (without hash)
        $params = [
            'HS_RequestHash' => '', // Will be filled after generation
            'HS_IsRedirectionRequest' => '0',
            'HS_ChannelId' => $this->config['channel_id'],
            'HS_ReturnURL' => $returnUrl,
            'HS_MerchantId' => $credentials['merchant_id'],
            'HS_StoreId' => $credentials['store_id'],
            'HS_MerchantHash' => $credentials['merchant_hash'],
            'HS_MerchantUsername' => $credentials['merchant_username'],
            'HS_MerchantPassword' => $credentials['merchant_password'],
            'HS_TransactionReferenceNumber' => $transactionReferenceNumber,
        ];

        // Generate hash from all parameters
        $requestHash = $this->generateRequestHash($params);
        $params['HS_RequestHash'] = $requestHash;

        // Log hash generation details
        $queryParts = [];
        foreach ($params as $key => $value) {
            if ($key !== 'HS_RequestHash') {
                $queryParts[] = $key . '=' . $value;
            }
        }
        $queryString = implode('&', $queryParts);

        $hashData = [
            'action' => 'HASH_GENERATION',
            'timestamp' => now()->toDateTimeString(),
            'query_string_to_encrypt' => $queryString,
            'encryption_key' => $this->config['encryption']['key1'],
            'encryption_iv' => $this->config['encryption']['key2'],
            'generated_hash' => $requestHash,
        ];
        file_put_contents($logFile, json_encode($hashData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

        // Log full request details
        $requestLog = [
            'action' => 'APG_HANDSHAKE_REQUEST',
            'timestamp' => now()->toDateTimeString(),
            'url' => $this->urls['handshake'],
            'environment' => $this->config['environment'],
            'params' => $params,
        ];
        file_put_contents($logFile, json_encode($requestLog, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

        // Log request
        $this->logRequest('handshake', $this->urls['handshake'], $params);

        try {
            $response = Http::timeout($this->config['timeout'])
                ->asForm()
                ->post($this->urls['handshake'], $params);

            $result = $response->json();

            // Log full response
            $responseLog = [
                'action' => 'APG_HANDSHAKE_RESPONSE',
                'timestamp' => now()->toDateTimeString(),
                'status_code' => $response->status(),
                'response_body' => $result,
                'raw_body' => $response->body(),
            ];
            file_put_contents($logFile, json_encode($responseLog, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            // Log response
            $this->logResponse('handshake', $result, $response->status(), $transactionReferenceNumber);

            return $result;

        } catch (Exception $e) {
            $errorLog = [
                'action' => 'APG_HANDSHAKE_EXCEPTION',
                'timestamp' => now()->toDateTimeString(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
            file_put_contents($logFile, json_encode($errorLog, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

            $this->logError('handshake', $e->getMessage(), $transactionReferenceNumber);
            throw $e;
        }
    }

    /**
     * Process payment (Step 2)
     */
    public function processPayment($authToken, $amount, $transactionReferenceNumber, $returnUrl = null, $paymentMethod = null)
    {
        $credentials = $this->config['credentials'];
        $returnUrl = $returnUrl ?? $this->config['return_url'];

        // Format amount as integer (APG requires whole numbers)
        $amount = (string) ((int) $amount);

        // Build all parameters first (without hash)
        $params = [
            'AuthToken' => $authToken,
            'RequestHash' => '', // Will be filled after generation
            'ChannelId' => $this->config['channel_id'],
            'Currency' => $this->config['currency'],
            'ReturnURL' => $returnUrl,
            'MerchantId' => $credentials['merchant_id'],
            'StoreId' => $credentials['store_id'],
            'MerchantHash' => $credentials['merchant_hash'],
            'MerchantUsername' => $credentials['merchant_username'],
            'MerchantPassword' => $credentials['merchant_password'],
            'TransactionReferenceNumber' => $transactionReferenceNumber,
            'TransactionAmount' => $amount,
        ];

        // Always include TransactionTypeId
        // Empty value = show all payment methods
        // 1 = Alfa Wallet, 2 = Bank Account, 3 = Credit/Debit Card
        $params['TransactionTypeId'] = $paymentMethod ?? '';

        // Generate hash from all parameters (same as handshake)
        $requestHash = $this->generateRequestHash($params);
        $params['RequestHash'] = $requestHash;

        // Log payment request details
        $logFile = storage_path('logs/paymentgateway.log');
        $queryParts = [];
        foreach ($params as $key => $value) {
            if ($key !== 'RequestHash') {
                $queryParts[] = $key . '=' . $value;
            }
        }
        $queryString = implode('&', $queryParts);

        $paymentLog = [
            'action' => 'PAYMENT_REQUEST_PREPARATION',
            'timestamp' => now()->toDateTimeString(),
            'payment_method' => $paymentMethod,
            'query_string_to_encrypt' => $queryString,
            'generated_hash' => $requestHash,
            'params' => $params,
            'url' => $this->urls['payment'],
        ];
        file_put_contents($logFile, json_encode($paymentLog, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

        // Log request
        $this->logRequest('payment', $this->urls['payment'], $params, $transactionReferenceNumber);

        return [
            'url' => $this->urls['payment'],
            'params' => $params
        ];
    }

    /**
     * Inquire transaction status
     */
    public function inquireTransaction($transactionReferenceNumber)
    {
        $credentials = $this->config['credentials'];

        $url = sprintf(
            '%s/%s/%s/%s',
            $this->urls['ipn'],
            $credentials['merchant_id'],
            $credentials['store_id'],
            $transactionReferenceNumber
        );

        // Log request
        $this->logRequest('inquiry', $url, [], $transactionReferenceNumber);

        try {
            $response = Http::timeout($this->config['timeout'])->get($url);
            $result = $response->json();

            // Log response
            $this->logResponse('inquiry', $result, $response->status(), $transactionReferenceNumber);

            return $result;

        } catch (Exception $e) {
            $this->logError('inquiry', $e->getMessage(), $transactionReferenceNumber);
            throw $e;
        }
    }

    /**
     * Create transaction record
     */
    public function createTransaction($data)
    {
        return ApgTransaction::create([
            'transaction_reference_number' => $data['transaction_reference_number'],
            'user_id' => $data['user_id'] ?? null,
            'tenant_id' => $data['tenant_id'] ?? null,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? $this->config['currency'],
            'transaction_type' => $data['transaction_type'] ?? 'subscription',
            'related_id' => $data['related_id'] ?? null,
            'status' => 'pending',
            'request_data' => $data['request_data'] ?? null,
        ]);
    }

    /**
     * Update transaction with APG response
     */
    public function updateTransaction($transactionReferenceNumber, $responseData)
    {
        $transaction = ApgTransaction::where('transaction_reference_number', $transactionReferenceNumber)->first();

        if (!$transaction) {
            return null;
        }

        $updateData = [
            'response_data' => $responseData,
            'response_code' => $responseData['ResponseCode'] ?? null,
            'response_description' => $responseData['Description'] ?? null,
        ];

        // Update status based on TransactionStatus
        if (isset($responseData['TransactionStatus'])) {
            $status = strtolower($responseData['TransactionStatus']);
            $updateData['status'] = $status === 'paid' ? 'paid' : 'failed';

            if ($status === 'paid') {
                $updateData['paid_at'] = now();
            }
        }

        // Update APG transaction details
        if (isset($responseData['TransactionId'])) {
            $updateData['apg_transaction_id'] = $responseData['TransactionId'];
        }

        if (isset($responseData['AccountNumber'])) {
            $updateData['account_number'] = $responseData['AccountNumber'];
        }

        if (isset($responseData['MobileNumber'])) {
            $updateData['mobile_number'] = $responseData['MobileNumber'];
        }

        if (isset($responseData['TransactionDateTime'])) {
            $updateData['transaction_datetime'] = $responseData['TransactionDateTime'];
        }

        $transaction->update($updateData);

        return $transaction;
    }

    /**
     * Log API request
     */
    protected function logRequest($action, $url, $payload, $transactionRef = null)
    {
        if (!$this->config['log_requests']) {
            return;
        }

        ApgPaymentLog::create([
            'transaction_reference_number' => $transactionRef,
            'action' => $action,
            'method' => 'POST',
            'url' => $url,
            'request_payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log API response
     */
    protected function logResponse($action, $response, $statusCode, $transactionRef = null)
    {
        if (!$this->config['log_requests']) {
            return;
        }

        $log = ApgPaymentLog::where('transaction_reference_number', $transactionRef)
            ->where('action', $action)
            ->latest()
            ->first();

        if ($log) {
            $log->update([
                'response_payload' => $response,
                'response_code' => $statusCode,
                'is_successful' => isset($response['success']) && $response['success'] === 'true',
            ]);
        }
    }

    /**
     * Log error
     */
    protected function logError($action, $error, $transactionRef = null)
    {
        if (!$this->config['log_requests']) {
            return;
        }

        $log = ApgPaymentLog::where('transaction_reference_number', $transactionRef)
            ->where('action', $action)
            ->latest()
            ->first();

        if ($log) {
            $log->update([
                'is_successful' => false,
                'error_message' => $error,
            ]);
        }

        Log::error("APG {$action} Error: " . $error, [
            'transaction_ref' => $transactionRef
        ]);
    }
}
