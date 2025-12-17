<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FcmTestController extends Controller
{
    /**
     * Test FCM notification
     */
    public function testNotification(Request $request)
    {
        $user = $request->user();

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'No FCM token found for user',
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);
        }

        try {
            $fcmService = new \App\Services\FcmService();
            $result = $fcmService->sendNotification(
                $user->fcm_token,
                'Test Notification',
                'This is a test notification from Chatvoo',
                [
                    'chat_id' => '999',
                    'chat_name' => 'Test Chat',
                    'message' => 'Test message',
                ]
            );

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Notification sent successfully' : 'Failed to send notification',
                'fcm_token' => substr($user->fcm_token, 0, 20) . '...',
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exception occurred',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Check FCM configuration
     */
    public function checkConfig(Request $request)
    {
        $user = $request->user();

        $serviceAccountPath = storage_path('app/firebase-service-account.json');
        $serviceAccountExists = file_exists($serviceAccountPath);

        $config = [
            'user_has_fcm_token' => !empty($user->fcm_token),
            'fcm_token_preview' => $user->fcm_token ? substr($user->fcm_token, 0, 30) . '...' : null,
            'service_account_exists' => $serviceAccountExists,
            'service_account_path' => $serviceAccountPath,
            'fcm_project_id' => env('FCM_PROJECT_ID'),
        ];

        if ($serviceAccountExists) {
            $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
            $config['service_account_email'] = $serviceAccount['client_email'] ?? 'Not found';
            $config['service_account_project_id'] = $serviceAccount['project_id'] ?? 'Not found';
        }

        return response()->json($config);
    }
}
