<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalytics extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'contact_id',
        'personal_assistant_id',
        'event_type',
        'conversation_id',
        'user_message',
        'ai_response',
        'response_time_ms',
        'was_successful',
        'handoff_reason',
        'ai_disabled_after',
        'detected_language',
        'user_sentiment',
        'message_length',
        'business_category',
        'was_in_business_scope',
        'event_time',
    ];

    protected $casts = [
        'was_successful' => 'boolean',
        'ai_disabled_after' => 'boolean',
        'was_in_business_scope' => 'boolean',
        'response_time_ms' => 'integer',
        'message_length' => 'integer',
        'event_time' => 'datetime',
    ];

    /**
     * Event types
     */
    const EVENT_AI_RESPONSE = 'ai_response';
    const EVENT_HANDOFF = 'handoff';
    const EVENT_RESOLUTION = 'resolution';
    const EVENT_ERROR = 'error';

    /**
     * Handoff reasons
     */
    const HANDOFF_USER_REQUEST = 'user_request';
    const HANDOFF_IMAGE_VERIFICATION = 'image_verification';
    const HANDOFF_BEYOND_CAPABILITY = 'beyond_capability';
    const HANDOFF_HARASSMENT = 'harassment';
    const HANDOFF_HIGH_STAKES = 'high_stakes';
    const HANDOFF_UNCERTAIN = 'uncertain';

    /**
     * Get the tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the contact
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Get the assistant
     */
    public function assistant(): BelongsTo
    {
        return $this->belongsTo(PersonalAssistant::class, 'personal_assistant_id');
    }

    /**
     * Track an AI response
     */
    public static function trackAiResponse(array $data): self
    {
        return self::create(array_merge($data, [
            'event_type' => self::EVENT_AI_RESPONSE,
            'event_time' => now(),
        ]));
    }

    /**
     * Track a handoff event
     */
    public static function trackHandoff(array $data): self
    {
        return self::create(array_merge($data, [
            'event_type' => self::EVENT_HANDOFF,
            'event_time' => now(),
        ]));
    }

    /**
     * Get handoff rate for a tenant
     */
    public static function getHandoffRate($tenantId, $days = 30): float
    {
        $total = self::where('tenant_id', $tenantId)
            ->where('event_type', self::EVENT_AI_RESPONSE)
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        if ($total === 0) {
            return 0;
        }

        $handoffs = self::where('tenant_id', $tenantId)
            ->where('event_type', self::EVENT_HANDOFF)
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        return round(($handoffs / $total) * 100, 2);
    }

    /**
     * Get average response time
     */
    public static function getAverageResponseTime($tenantId, $days = 30): int
    {
        return (int) self::where('tenant_id', $tenantId)
            ->where('event_type', self::EVENT_AI_RESPONSE)
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('response_time_ms')
            ->avg('response_time_ms');
    }

    /**
     * Get top handoff reasons
     */
    public static function getTopHandoffReasons($tenantId, $days = 30, $limit = 5): array
    {
        return self::where('tenant_id', $tenantId)
            ->where('event_type', self::EVENT_HANDOFF)
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('handoff_reason')
            ->selectRaw('handoff_reason, COUNT(*) as count')
            ->groupBy('handoff_reason')
            ->orderByDesc('count')
            ->limit($limit)
            ->pluck('count', 'handoff_reason')
            ->toArray();
    }

    /**
     * Get busiest hours
     */
    public static function getBusiestHours($tenantId, $days = 30): array
    {
        return self::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('HOUR(event_time) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();
    }
}
