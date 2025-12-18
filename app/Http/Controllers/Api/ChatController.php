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
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

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

        // Add unread count to each chat
        $chats->getCollection()->transform(function ($chat) use ($subdomain, $user) {
            $unreadCount = ChatMessage::fromTenant($subdomain)
                ->where('interaction_id', $chat->id)
                ->where('tenant_id', $user->tenant_id)
                ->where('is_read', false)
                ->whereNull('staff_id') // Only count customer messages, not staff messages
                ->count();

            $chat->unread_count = $unreadCount;
            return $chat;
        });

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

        // Mark all customer messages (not staff messages) as read
        ChatMessage::fromTenant($subdomain)
            ->where('interaction_id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->where('is_read', false)
            ->whereNull('staff_id') // Only mark customer messages as read
            ->update(['is_read' => true]);

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
        $type = $request->input('type', 'text');

        // Fix: If type is 'text' (default) but a file is uploaded, infer the type from the file
        // This handles cases where 'type' input might be missing or malformed in multipart requests
        if ($type === 'text' && $request->hasFile('file')) {
            $mime = $request->file('file')->getMimeType();
            if (str_starts_with($mime, 'audio/'))
                $type = 'audio';
            elseif (str_starts_with($mime, 'video/'))
                $type = 'video';
            elseif (str_starts_with($mime, 'image/'))
                $type = 'image';
            else
                $type = 'document';
        }

        $rules = [
            'type' => 'nullable|string|in:text,image,video,document,audio',
        ];

        if ($type === 'text') {
            $rules['message'] = 'required|string|max:4096';
        } else {
            $rules['file'] = 'required|file|max:20480'; // 20MB limit
            $rules['message'] = 'nullable|string|max:1024';
        }

        $validator = Validator::make($request->all(), $rules);

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

        $chat = Chat::fromTenant($subdomain)
            ->where('id', $id)
            ->where('tenant_id', $user->tenant_id)
            ->first();

        if (!$chat) {
            return response()->json(['success' => false, 'message' => 'Chat not found'], 404);
        }

        $whatsapp_success = false;
        $waMessageId = null;
        $storedFilename = null;
        $mediaUrl = null;

        // Handle File Upload
        try {
            if ($request->hasFile('file') && $type !== 'text') {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $storedFilename = 'media_' . uniqid() . '.' . $extension;

                // Save to public storage (same as incoming media)
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('whatsapp-attachments', $file, $storedFilename);

                // Generate Public Proxy URL for WhatsApp to access
                // This URL must be accessible from the internet (WhatsApp servers)
                // Since we made /api/media/ public, this should work if the server is public.
                // If localhost, this part might fail on WhatsApp side, but logic is correct.
                $mediaUrl = "https://dash.chatvoo.com/api/media/" . $storedFilename;
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'File Upload Failed: ' . $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }

        try {
            $whatsappApi = new WhatsAppCloudApi([
                'from_phone_number_id' => $chat->wa_no_id,
                'access_token' => $this->setWaTenantId($user->tenant_id)->getToken(),
            ]);

            $response = null;

            if ($type === 'text') {
                $response = $whatsappApi->sendTextMessage(
                    $chat->receiver_id,
                    $request->input('message')
                );
            } elseif ($type === 'image' && $mediaUrl) {
                // Send Image
                $response = $whatsappApi->sendImage(
                    $chat->receiver_id,
                    new LinkID($mediaUrl),
                    $request->input('message') // Caption
                );
            } elseif ($type === 'audio' && $mediaUrl) {
                // Send Audio
                $response = $whatsappApi->sendAudio(
                    $chat->receiver_id,
                    new LinkID($mediaUrl)
                );
            } elseif ($type === 'video' && $mediaUrl) {
                // Send Video
                $response = $whatsappApi->sendVideo(
                    $chat->receiver_id,
                    new LinkID($mediaUrl),
                    $request->input('message') // Caption
                );
            } elseif ($type === 'document' && $mediaUrl) {
                // Send Document
                // Use original filename or default
                $filename = $request->hasFile('file') ? $request->file('file')->getClientOriginalName() : 'document.pdf';

                $response = $whatsappApi->sendDocument(
                    $chat->receiver_id,
                    new LinkID($mediaUrl),
                    $filename,
                    $request->input('message') // Caption/Description
                );
            }

            if ($response) {
                $response_data = $response->decodedBody();

                // Log the full response for debugging
                \Log::info('WhatsApp API Response', [
                    'type' => $type,
                    'media_url' => $mediaUrl,
                    'response' => $response_data
                ]);

                if (isset($response_data['messages'][0]['id'])) {
                    $waMessageId = $response_data['messages'][0]['id'];
                    $whatsapp_success = true;
                } else {
                    \Log::error('WhatsApp API - No message ID in response', [
                        'response' => $response_data
                    ]);
                }
            }

        } catch (\Exception $e) {
            whatsapp_log('WhatsApp send failed from Android API', 'error', [
                'to' => $chat->receiver_id,
                'type' => $type,
                'error' => $e->getMessage(),
            ], $e, $user->tenant_id);
        }

        $status = $whatsapp_success ? 'sent' : 'failed';

        // Create message in database
        $message = ChatMessage::fromTenant($subdomain)->create([
            'tenant_id' => $user->tenant_id,
            'interaction_id' => $id,
            'sender_id' => 'staff_' . $user->id,
            'message' => $request->input('message') ?? '', // Caption or text, default to empty string to prevent DB null error
            'type' => $type,
            'url' => $storedFilename, // Save just the filename as DB expects
            'time_sent' => now(),
            'status' => $status,
            'message_id' => $waMessageId,
            'staff_id' => $user->id,
            'is_read' => false,
        ]);

        // Update chat last message
        $displayMessage = $type === 'text' ? $request->input('message') : ucfirst($type) . ' message';
        $chat->update([
            'last_message' => $displayMessage,
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
