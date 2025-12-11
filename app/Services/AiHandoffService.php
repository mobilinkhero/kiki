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
        // 'urgent', // Can be used in "I need delivery urgent" - AI can handle
        // 'asap',   // "Ship asap" - AI can handle
        'emergency', // True emergency
        // 'immediately',
        // 'right now',
        // 'frustrated', // Sentiment is better handled by AI
        // 'angry',
        // 'upset',
        // 'disappointed',
        'terrible service', // Specific phrases are safer
        'worst experience',
        'horrible service',
        'unacceptable',
        // 'refund', // AI should answer refund policy first
        // 'cancel', // AI should handle cancellation request first
        'lawsuit',
        'lawyer',
        'legal action',
        // 'complaint', // AI can take complaint details
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
     * AI-Powered Intelligent Handoff Detection
     * 
     * Let AI analyze if it should handoff based on:
     * - Complexity of query
     * - Confidence in response
     * - Topic sensitivity
     * 
     * @param string $userMessage The user's message
     * @param string $aiResponse The AI's generated response
     * @param int $tenantId Tenant ID for API key
     * @return array Handoff decision with reasoning
     */
    public function shouldAiHandoff(string $userMessage, string $aiResponse, int $tenantId): array
    {
        try {
            // Get OpenAI API key
            $apiKey = get_tenant_setting_by_tenant_id('whats-mark', 'openai_secret_key', null, $tenantId);

            if (empty($apiKey)) {
                // No API key, can't analyze - default to no handoff
                return [
                    'should_handoff' => false,
                    'reason' => 'no_api_key',
                    'confidence' => 0,
                    'ai_reasoning' => null,
                ];
            }

            // Prepare analysis prompt for AI
            $analysisPrompt = $this->buildHandoffAnalysisPrompt($userMessage, $aiResponse);

            // Call OpenAI to analyze
            $config = new \LLPhant\OpenAIConfig();
            $config->apiKey = $apiKey;
            $config->model = 'gpt-4o-mini'; // Fast and cheap for analysis
            $config->temperature = 0.1; // Low temperature for consistent decisions
            $config->maxTokens = 150;

            $chat = new \LLPhant\Chat\OpenAIChat($config);

            $messages = [
                ['role' => 'system', 'content' => $analysisPrompt],
                ['role' => 'user', 'content' => "User: $userMessage\n\nMy Response: $aiResponse\n\nShould I handoff?"],
            ];

            $analysis = $chat->generateChat($messages);

            // Parse AI's decision
            $decision = $this->parseHandoffDecision($analysis);

            whatsapp_log('AI Handoff Analysis', 'info', [
                'user_message' => substr($userMessage, 0, 100),
                'ai_response' => substr($aiResponse, 0, 100),
                'decision' => $decision,
                'tenant_id' => $tenantId,
            ], null, $tenantId);

            return $decision;

        } catch (\Exception $e) {
            // On error, default to no handoff
            Log::error('AI Handoff Analysis Failed', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenantId,
            ]);

            return [
                'should_handoff' => false,
                'reason' => 'analysis_error',
                'confidence' => 0,
                'ai_reasoning' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build prompt for AI to analyze if handoff is needed
     */
    protected function buildHandoffAnalysisPrompt(string $userMessage, string $aiResponse): string
    {
        return <<<PROMPT
You are an AI assistant analyzer. Your job is to determine if the AI should hand off the conversation to a human agent.

**IMPORTANT: Only handoff if the AI CANNOT help. If the AI provided a good answer, DO NOT handoff!**

Analyze the user's message and the AI's response. Decide if handoff is needed based on:

**HANDOFF REQUIRED ONLY IF:**
1. AI explicitly says "I don't know" or "I cannot help"
2. User is EXTREMELY frustrated or angry (multiple complaints)
3. Requires actual account access/changes (refunds, cancellations, account modifications)
4. User EXPLICITLY demands to speak to human/manager
5. AI response is completely wrong or irrelevant
6. Sensitive legal/medical advice that requires licensed professional
7. Payment/billing disputes

**DO NOT HANDOFF IF:**
1. AI provided helpful information (even if not perfect)
2. AI answered the question with knowledge base info
3. General product questions, FAQs, how-to guides
4. AI gave troubleshooting steps
5. User is just asking questions (not demanding human)
6. AI provided relevant response from its knowledge
7. Simple inquiries about business hours, policies, features
8. AI successfully identified products or provided recommendations

**CRITICAL RULE: If the AI's response contains useful information from its knowledge base, DO NOT handoff!**

**RESPONSE FORMAT (JSON only):**
{
  "handoff": true/false,
  "confidence": 0-100,
  "reason": "brief reason",
  "category": "cannot_help|extreme_frustration|account_access|explicit_demand|wrong_answer|legal_medical|billing_dispute"
}

**DEFAULT: When in doubt, LET AI HANDLE IT. Only handoff if AI truly cannot help.**
PROMPT;
    }

    /**
     * Parse AI's handoff decision from response
     */
    protected function parseHandoffDecision(string $analysis): array
    {
        // Try to extract JSON from response
        if (preg_match('/\{[^}]+\}/', $analysis, $matches)) {
            $json = json_decode($matches[0], true);

            if ($json && isset($json['handoff'])) {
                return [
                    'should_handoff' => (bool) $json['handoff'],
                    'reason' => 'ai_decided_' . ($json['category'] ?? 'unknown'),
                    'confidence' => (int) ($json['confidence'] ?? 50),
                    'ai_reasoning' => $json['reason'] ?? 'AI analysis',
                    'category' => $json['category'] ?? 'unknown',
                ];
            }
        }

        // Fallback: Check for keywords in response
        $analysisLower = strtolower($analysis);

        if (
            str_contains($analysisLower, 'handoff') ||
            str_contains($analysisLower, 'transfer') ||
            str_contains($analysisLower, 'human')
        ) {
            return [
                'should_handoff' => true,
                'reason' => 'ai_decided_uncertain',
                'confidence' => 60,
                'ai_reasoning' => 'AI suggested handoff based on analysis',
            ];
        }

        return [
            'should_handoff' => false,
            'reason' => 'ai_decided_can_handle',
            'confidence' => 70,
            'ai_reasoning' => 'AI can handle this query',
        ];
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
