<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Chat;
use App\Models\Tenant\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Chat Management
 *
 * APIs for managing chats and messages
 */
class ChatController extends Controller
{
    /**
     * List Chats
     *
     * Retrieve a paginated list of chats/conversations.
     *
     * @authenticated
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->tenant_id) {
            return response()->json(['success' => false, 'message' => 'User is not associated with a tenant'], 403);
        }

        $subdomain = tenant_subdomain_by_tenant_id($user->tenant_id);

        $chats = Chat::fromTenant($subdomain)
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('last_msg_time', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }

    /**
     * Get Chat Messages
     *
     * Retrieve messages for a specific chat.
     *
     * @authenticated
     * @urlParam id integer required The Chat/Interaction ID.
     */
    public function messages(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (!$user->tenant_id) {
            return response()->json(['success' => false, 'message' => 'User is not associated with a tenant'], 403);
        }

        $subdomain = tenant_subdomain_by_tenant_id($user->tenant_id);

        // Verify chat belongs to tenant
        $chat = Chat::fromTenant($subdomain)
            ->where('id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->first();

        if (!$chat) {
            return response()->json(['success' => false, 'message' => 'Chat not found'], 404);
        }

        $messages = ChatMessage::fromTenant($subdomain)
            ->where('interaction_id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('time_sent', 'desc')
            ->paginate($request->input('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }
}
