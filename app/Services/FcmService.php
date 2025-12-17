<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class FcmService
{
    private $serverKey;
    private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->serverKey = env('FCM_SERVER_KEY');
    }

    /**
     * Send push notification to a specific device
     */
    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        if (empty($this->serverKey)) {
            Log::error('FCM_SERVER_KEY not configured');
            return false;
        }

        if (empty($fcmToken)) {
            Log::error('FCM token is empty');
            return false;
        }

        $notification = [
            'title' => $title,
            'body' => substr($body, 0, 100),
            'sound' => 'default',
            'badge' => '1',
        ];

        $payload = [
            'to' => $fcmToken,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        ];

        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            Log::error('FCM curl error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $response = json_decode($result, true);

        if ($httpCode === 200 && isset($response['success']) && $response['success'] > 0) {
            Log::info('FCM notification sent successfully', ['response' => $response]);
            return true;
        } else {
            Log::error('FCM notification failed', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            return false;
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultiple($fcmTokens, $title, $body, $data = [])
    {
        if (empty($this->serverKey)) {
            Log::error('FCM_SERVER_KEY not configured');
            return false;
        }

        $notification = [
            'title' => $title,
            'body' => substr($body, 0, 100),
            'sound' => 'default',
            'badge' => '1',
        ];

        $payload = [
            'registration_ids' => $fcmTokens,
            'notification' => $notification,
            'data' => $data,
            'priority' => 'high',
        ];

        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
