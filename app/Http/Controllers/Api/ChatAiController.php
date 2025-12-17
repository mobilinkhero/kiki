<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Chat;
use App\Models\Tenant\Contact;
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

        // Get the contact associated with this chat
        // Contact uses dynamic table name: {subdomain}_contacts
        $subdomain = tenant_subdomain();
        $contact = Contact::fromTenant($subdomain)
            ->where('id', $chat->type_id)
            ->first();

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found',
            ], 404);
        }

        // Toggle AI status
        $contact->ai_disabled = !$contact->ai_disabled;
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => $contact->ai_disabled ? 'AI disabled for this chat' : 'AI enabled for this chat',
            'ai_disabled' => $contact->ai_disabled,
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

        $subdomain = tenant_subdomain();
        $contact = Contact::fromTenant($subdomain)
            ->where('id', $chat->type_id)
            ->first();

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found',
            ], 404);
        }

        $contact->ai_disabled = false;
        $contact->save();

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

        $subdomain = tenant_subdomain();
        $contact = Contact::fromTenant($subdomain)
            ->where('id', $chat->type_id)
            ->first();

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found',
            ], 404);
        }

        $contact->ai_disabled = true;
        $contact->save();

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
        \Log::info('ChatAi: Getting AI status', [
            'chat_id' => $chatId,
            'user_id' => $request->user()->id,
            'tenant_id' => $request->user()->tenant_id,
        ]);

        $chat = Chat::where('id', $chatId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();

        if (!$chat) {
            \Log::warning('ChatAi: Chat not found', ['chat_id' => $chatId]);
            return response()->json([
                'success' => false,
                'message' => 'Chat not found',
            ], 404);
        }

        \Log::info('ChatAi: Chat found', [
            'chat_id' => $chat->id,
            'type_id' => $chat->type_id,
        ]);

        $subdomain = tenant_subdomain();
        \Log::info('ChatAi: Using subdomain', ['subdomain' => $subdomain]);

        $contact = Contact::fromTenant($subdomain)
            ->where('id', $chat->type_id)
            ->first();

        if (!$contact) {
            \Log::warning('ChatAi: Contact not found', [
                'type_id' => $chat->type_id,
                'subdomain' => $subdomain,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Contact not found',
            ], 404);
        }

        \Log::info('ChatAi: Contact found', [
            'contact_id' => $contact->id,
            'ai_disabled' => $contact->ai_disabled,
        ]);

        return response()->json([
            'success' => true,
            'ai_disabled' => (bool) $contact->ai_disabled,
            'ai_enabled' => !$contact->ai_disabled,
        ]);
    }
}
