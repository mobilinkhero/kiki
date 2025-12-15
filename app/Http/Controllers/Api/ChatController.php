<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Chat;
use App\Models\Tenant\ChatMessage;
use App\Traits\WhatsApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

/**
 * @group Chat Management
 *
 * APIs for managing chats and messages  
 */
class ChatController extends Controller
{
    use WhatsApp;  // ✅ Use the WhatsApp trait!
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

    /**
     * Send Message
     *
     * Send a message from vendor/staff to customer via WhatsApp and save to database.
     *
     * @authenticated
     * @urlParam id integer required The Chat/Interaction ID.
     */
    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:4096',
            'type' => 'nullable|string|in:text,image,video,document,audio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

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

        // ✅ WORKING LOGIC - Same as website (WhatsAppWebhookController)
        $whatsapp_success = false;
        $waMessageId = null;

        try {
            // Initialize WhatsApp Cloud API client (EXACTLY like website does it - line 1442)
            $whatsappApi = new WhatsAppCloudApi([
                'from_phone_number_id' => $chat->wa_no_id,
                'access_token' => $this->setWaTenantId($user->tenant_id)->getToken(),
            ]);

            // Send text message via WhatsApp
            $response = $whatsappApi->sendTextMessage(
                $chat->receiver_id, // Customer's WhatsApp number
                $request->input('message')
            );

            // Decode the response (same as website - line 1473)
            $response_data = $response->decodedBody();

            // Store the message ID if available (same as website - line 1476)
            if (isset($response_data['messages'][0]['id'])) {
                $waMessageId = $response_data['messages'][0]['id'];
                $whatsapp_success = true;
            }
        } catch (\Exception $e) {
            whatsapp_log('WhatsApp send failed from Android API', 'error', [
                'to' => $chat->receiver_id,
                'error' => $e->getMessage(),
            ], $e, $user->tenant_id);
        }

        $status = $whatsapp_success ? 'sent' : 'failed';


        // Create message in database (even if WhatsApp send failed)
        $message = ChatMessage::fromTenant($subdomain)->create([
            'tenant_id' => $user->tenant_id,
            'interaction_id' => $id,
            'sender_id' => 'staff_' . $user->id, // Mark as staff message
            'message' => $request->input('message'),
            'type' => $request->input('type', 'text'),
            'time_sent' => now(),
            'status' => $status,
            'message_id' => $waMessageId,
            'staff_id' => $user->id,
            'is_read' => false,
        ]);

        // Update chat last message and time
        $chat->update([
            'last_message' => $request->input('message'),
            'last_msg_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $status === 'sent'
                ? 'Message sent successfully via WhatsApp'
                : 'Message saved but WhatsApp delivery failed',
            'data' => [
                'message' => $message,
                'whatsapp_status' => $status
            ]
        ], 201);
    }
}
