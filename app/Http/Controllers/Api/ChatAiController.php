<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatAiController extends Controller
{
    /**
     * Toggle AI for a chat
     */
    public function toggleAi(Request $request, $chatId): JsonResponse
    {
        $chat = Chat::where('id', $chatId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found',
            ], 404);
        }

        // Toggle AI status
        $chat->ai_disabled = !$chat->ai_disabled;
        $chat->save();

        return response()->json([
            'success' => true,
            'message' => $chat->ai_disabled ? 'AI disabled for this chat' : 'AI enabled for this chat',
            'ai_disabled' => $chat->ai_disabled,
        ]);
    }

    /**
     * Enable AI for a chat
     */
    public function enableAi(Request $request, $chatId): JsonResponse
    {
        $chat = Chat::where('id', $chatId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found',
            ], 404);
        }

        $chat->ai_disabled = false;
        $chat->save();

        return response()->json([
            'success' => true,
            'message' => 'AI enabled for this chat',
            'ai_disabled' => false,
        ]);
    }

    /**
     * Disable AI for a chat
     */
    public function disableAi(Request $request, $chatId): JsonResponse
    {
        $chat = Chat::where('id', $chatId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found',
            ], 404);
        }

        $chat->ai_disabled = true;
        $chat->save();

        return response()->json([
            'success' => true,
            'message' => 'AI disabled for this chat',
            'ai_disabled' => true,
        ]);
    }

    /**
     * Get AI status for a chat
     */
    public function getAiStatus(Request $request, $chatId): JsonResponse
    {
        $chat = Chat::where('id', $chatId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ai_disabled' => (bool) $chat->ai_disabled,
            'ai_enabled' => !$chat->ai_disabled,
        ]);
    }
}
