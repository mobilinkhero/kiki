<?php

namespace App\Services;

class FcmService
{
    private $projectId;
    private $serviceAccountPath;

    public function __construct()
    {
        $this->projectId = env('FCM_PROJECT_ID', 'tptp-18e5a');
        $this->serviceAccountPath = storage_path('app/firebase-service-account.json');
    }

    /**
     * Get OAuth 2.0 access token from service account
     */
    private function getAccessToken()
    {
        if (!file_exists($this->serviceAccountPath)) {
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);

        $now = time();
        $payload = [
            'iss' => $serviceAccount['client_email'],
            'sub' => $serviceAccount['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ];

        // Create JWT
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $payload = json_encode($payload);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = '';
        openssl_sign(
            $base64UrlHeader . '.' . $base64UrlPayload,
            $signature,
            $serviceAccount['private_key'],
            'SHA256'
        );

        $base64UrlSignature = $this->base64UrlEncode($signature);
        $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

        // Exchange JWT for access token
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['access_token'] ?? null;
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Send push notification to a specific device using FCM HTTP v1 API
     */
    public function sendNotification($fcmToken, $title, $body, $data = [], $imageUrl = null)
    {
        if (empty($fcmToken)) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $notification = [
            'title' => $title,
            'body' => substr($body, 0, 100),
        ];

        // Add image if provided
        if ($imageUrl) {
            $notification['image'] = $imageUrl;
        }

        $message = [
            'message' => [
                'token' => $fcmToken,
                'notification' => $notification,
                'data' => array_map('strval', $data),
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'chat_messages',
                    ]
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('FCM CURL Error', ['error' => $error]);
            return false;
        }

        curl_close($ch);

        $response = json_decode($result, true);

        if ($httpCode !== 200) {
            \Log::error('FCM Failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'token_preview' => substr($fcmToken, 0, 20)
            ]);
        } else {
            \Log::info('FCM Success', [
                'token_preview' => substr($fcmToken, 0, 20)
            ]);
        }

        return $httpCode === 200;
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultiple($fcmTokens, $title, $body, $data = [])
    {
        $results = [];
        foreach ($fcmTokens as $token) {
            $results[] = $this->sendNotification($token, $title, $body, $data);
        }
        return $results;
    }
}
