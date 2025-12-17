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
            \Log::channel('push_notification')->error('❌ Firebase service account file not found', [
                'path' => $this->serviceAccountPath,
                'exists' => file_exists($this->serviceAccountPath),
            ]);
            return null;
        }

        \Log::channel('push_notification')->info('✅ Loading service account file', [
            'path' => $this->serviceAccountPath,
        ]);

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

        if (isset($result['access_token'])) {
            \Log::channel('push_notification')->info('✅ Got OAuth access token');
        } else {
            \Log::channel('push_notification')->error('❌ Failed to get OAuth token', [
                'response' => $result,
            ]);
        }

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
        \Log::channel('push_notification')->info('🔔 ========== STARTING NOTIFICATION SEND ==========');
        \Log::channel('push_notification')->info('📱 Target Device', [
            'fcm_token_preview' => substr($fcmToken, 0, 30) . '...',
        ]);
        \Log::channel('push_notification')->info('📝 Notification Content', [
            'title' => $title,
            'body' => substr($body, 0, 100),
            'data' => $data,
            'image_url' => $imageUrl,
        ]);

        if (empty($fcmToken)) {
            \Log::channel('push_notification')->error('❌ FCM token is empty - ABORTING');
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            \Log::channel('push_notification')->error('❌ Failed to get FCM access token - ABORTING');
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

        \Log::channel('push_notification')->info('📤 Sending to FCM API', [
            'url' => $url,
            'project_id' => $this->projectId,
            'message_structure' => $message,
        ]);

        $headers = [
            'Authorization: Bearer ' . substr($accessToken, 0, 20) . '...',
            'Content-Type: application/json',
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
            \Log::channel('push_notification')->error('❌ CURL ERROR', [
                'error' => curl_error($ch),
                'errno' => curl_errno($ch),
            ]);
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $response = json_decode($result, true);

        \Log::channel('push_notification')->info('📥 FCM Response', [
            'http_code' => $httpCode,
            'response' => $response,
            'raw_result' => $result,
        ]);

        if ($httpCode === 200) {
            \Log::channel('push_notification')->info('✅ ========== NOTIFICATION SENT SUCCESSFULLY ==========');
            return true;
        } else {
            \Log::channel('push_notification')->error('❌ ========== NOTIFICATION FAILED ==========', [
                'http_code' => $httpCode,
                'error_details' => $response,
            ]);
            return false;
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultiple($fcmTokens, $title, $body, $data = [])
    {
        \Log::channel('push_notification')->info('📢 Sending to multiple devices', [
            'device_count' => count($fcmTokens),
        ]);

        $results = [];
        foreach ($fcmTokens as $token) {
            $results[] = $this->sendNotification($token, $title, $body, $data);
        }
        return $results;
    }
}
