<?php

namespace App\Services;

use App\Models\Tenant\Chat;
use App\Models\Tenant\Contact;
use App\Models\User;
use App\Services\pusher\PusherService;
use Illuminate\Support\Facades\Log;

/**
 * AI Handoff Service
 * 
 * Handles seamless transfer from AI to human agents
 */
class AiHandoffService
{
    /**
     * Keywords that trigger human handoff
     */
    protected const HANDOFF_KEYWORDS = [
        'human',
        'agent',
        'representative',
        'person',
        'talk to someone',
        'speak to human',
        'real person',
        'customer service',
        'support team',
        'manager',
        'supervisor',
        'escalate',
        'complaint',
        // Add more languages
        'humano',
        'persona',
        'representante', // Spanish
        'إنسان',
        'وكيل',
        'ممثل', // Arabic
        'मानव',
        'एजेंट', // Hindi
    ];

    /**
     * Phrases indicating frustration/urgency
     */
    protected const URGENT_KEYWORDS = [
        'urgent',
        'emergency',
        'asap',
        'immediately',
        'right now',
        'frustrated',
        'angry',
        'upset',
        'disappointed',
        'terrible',
        'worst',
        'horrible',
        'unacceptable',
        'refund',
        'cancel',
        'lawsuit',
        'lawyer',
        'legal action',
        'complaint',
    ];

    /**
     * Detect if message requests human handoff
     */
    public function shouldHandoff(string $message): array
    {
        $messageLower = strtolower($message);

        // Check for explicit handoff keywords
        foreach (self::HANDOFF_KEYWORDS as $keyword) {
            if (str_contains($messageLower, strtolower($keyword))) {
                return [
                    'should_handoff' => true,
                    'reason' => 'user_requested',
                    'keyword' => $keyword,
                    'is_urgent' => $this->isUrgent($message),
                ];
            }
        }

        // Check for urgency/frustration
        if ($this->isUrgent($message)) {
            return [
                'should_handoff' => true,
                'reason' => 'urgent_detected',
                'keyword' => 'urgency',
                'is_urgent' => true,
            ];
        }

        return [
            'should_handoff' => false,
            'reason' => null,
            'keyword' => null,
            'is_urgent' => false,
        ];
    }

    /**
     * Check if message indicates urgency
     */
    protected function isUrgent(string $message): bool
    {
        $messageLower = strtolower($message);

        $urgentCount = 0;
        foreach (self::URGENT_KEYWORDS as $keyword) {
            if (str_contains($messageLower, strtolower($keyword))) {
                $urgentCount++;
            }
        }

        // Multiple urgent keywords or excessive punctuation
        return $urgentCount >= 2 ||
            substr_count($message, '!') >= 3 ||
            substr_count($message, '?') >= 3;
    }

    /**
     * Execute handoff from AI to human
     */
    public function executeHandoff(
        Chat $chat,
        Contact $contact,
        string $reason,
        bool $isUrgent = false,
        ?string $keyword = null
    ): array {
        try {
            $tenantId = $chat->tenant_id;
            $subdomain = tenant_subdomain_by_tenant_id($tenantId);

            // 1. Disable AI for this contact
            $contact->disableAi();

            // 2. Get available agent
            $agent = $this->getAvailableAgent($tenantId, $isUrgent);

            // 3. Update chat with handoff info
            $chat->update([
                'ai_handed_off' => true,
                'ai_handoff_at' => now(),
                'ai_handoff_reason' => $reason,
                'assigned_agent_id' => $agent?->id,
                'agent_notified' => false,
            ]);

            // 4. Notify agent
            if ($agent) {
                $this->notifyAgent($agent, $chat, $contact, $reason, $isUrgent);

                $chat->update(['agent_notified' => true]);
            }

            // 5. Log handoff
            whatsapp_log('AI Handoff Executed', 'info', [
                'chat_id' => $chat->id,
                'contact_id' => $contact->id,
                'reason' => $reason,
                'keyword' => $keyword,
                'is_urgent' => $isUrgent,
                'assigned_agent' => $agent?->id,
                'tenant_id' => $tenantId,
            ], null, $tenantId);

            return [
                'success' => true,
                'message' => $this->getHandoffMessage($agent, $isUrgent),
                'agent' => $agent,
                'is_urgent' => $isUrgent,
            ];

        } catch (\Exception $e) {
            Log::error('AI Handoff Failed', [
                'error' => $e->getMessage(),
                'chat_id' => $chat->id,
                'contact_id' => $contact->id,
            ]);

            return [
                'success' => false,
                'message' => 'We\'re experiencing technical difficulties. Please try again in a moment.',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get available agent for assignment
     */
    protected function getAvailableAgent(int $tenantId, bool $isUrgent = false): ?User
    {
        // Get users for this tenant
        $query = User::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('role', '!=', 'guest');

        // If urgent, prioritize online agents
        if ($isUrgent) {
            $onlineAgent = $query->where('is_online', true)
                ->orderBy('last_activity_at', 'desc')
                ->first();

            if ($onlineAgent) {
                return $onlineAgent;
            }
        }

        // Get agent with least active chats
        $agent = $query->withCount([
            'assignedChats' => function ($q) use ($tenantId) {
                $subdomain = tenant_subdomain_by_tenant_id($tenantId);
                $q->from($subdomain . '_chats')
                    ->where('ai_handed_off', true)
                    ->whereNull('resolved_at');
            }
        ])
            ->orderBy('assigned_chats_count', 'asc')
            ->first();

        return $agent;
    }

    /**
     * Notify agent about handoff
     */
    protected function notifyAgent(User $agent, Chat $chat, Contact $contact, string $reason, bool $isUrgent): void
    {
        try {
            $tenantId = $chat->tenant_id;
            $subdomain = tenant_subdomain_by_tenant_id($tenantId);

            // Send Pusher notification
            $pusherSettings = tenant_settings_by_group('pusher', $tenantId);

            if (!empty($pusherSettings['pusher.app_id'])) {
                $pusher = new PusherService($tenantId);

                $pusher->trigger('chat-channel-' . $agent->id, 'ai-handoff', [
                    'chat_id' => $chat->id,
                    'contact_id' => $contact->id,
                    'contact_name' => $contact->firstname . ' ' . $contact->lastname,
                    'contact_phone' => $contact->phone,
                    'reason' => $reason,
                    'is_urgent' => $isUrgent,
                    'message' => $isUrgent ? '🚨 URGENT: AI handed off conversation' : '📞 AI handed off conversation',
                    'timestamp' => now()->toISOString(),
                ]);
            }

            // TODO: Add email notification if agent is offline
            // TODO: Add SMS notification for urgent handoffs

        } catch (\Exception $e) {
            Log::error('Failed to notify agent', [
                'agent_id' => $agent->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get handoff message for user
     */
    protected function getHandoffMessage(?User $agent, bool $isUrgent): string
    {
        if ($isUrgent && $agent) {
            return "🚨 I understand this is urgent. Connecting you to {$agent->name} right away. They'll be with you shortly.";
        }

        if ($agent) {
            return "👋 I'm connecting you with {$agent->name} from our team. They'll assist you shortly.";
        }

        return "👋 I'm connecting you with our support team. Someone will be with you shortly.";
    }

    /**
     * Get handoff statistics for analytics
     */
    public function getHandoffStats(int $tenantId, ?string $period = 'today'): array
    {
        $subdomain = tenant_subdomain_by_tenant_id($tenantId);

        $query = Chat::fromTenant($subdomain)
            ->where('tenant_id', $tenantId)
            ->where('ai_handed_off', true);

        // Filter by period
        switch ($period) {
            case 'today':
                $query->whereDate('ai_handoff_at', today());
                break;
            case 'week':
                $query->where('ai_handoff_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('ai_handoff_at', '>=', now()->subMonth());
                break;
        }

        $total = $query->count();
        $urgent = $query->where('ai_handoff_reason', 'urgent_detected')->count();
        $userRequested = $query->where('ai_handoff_reason', 'user_requested')->count();

        return [
            'total_handoffs' => $total,
            'urgent_handoffs' => $urgent,
            'user_requested' => $userRequested,
            'period' => $period,
        ];
    }
}
