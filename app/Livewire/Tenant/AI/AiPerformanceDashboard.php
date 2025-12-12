<?php

namespace App\Livewire\Tenant\AI;

use App\Models\AiAnalytics;
use App\Models\PersonalAssistant;
use Livewire\Component;

class AiPerformanceDashboard extends Component
{
    public $dateRange = 30; // days
    public $selectedAssistant = null;

    // Metrics
    public $totalConversations;
    public $handoffRate;
    public $averageResponseTime;
    public $topHandoffReasons;
    public $busiestHours;
    public $dailyTrends;
    public $successRate;
    public $languageBreakdown;
    public $sentimentBreakdown;

    public function mount()
    {
        $this->loadMetrics();
    }

    public function updatedDateRange()
    {
        $this->loadMetrics();
    }

    public function updatedSelectedAssistant()
    {
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        $tenantId = tenant_id();
        $days = $this->dateRange;

        $query = AiAnalytics::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays($days));

        if ($this->selectedAssistant) {
            $query->where('personal_assistant_id', $this->selectedAssistant);
        }

        // Total conversations
        $this->totalConversations = $query->where('event_type', AiAnalytics::EVENT_AI_RESPONSE)->count();

        // Handoff rate
        $this->handoffRate = AiAnalytics::getHandoffRate($tenantId, $days);

        // Average response time
        $this->averageResponseTime = AiAnalytics::getAverageResponseTime($tenantId, $days);

        // Success rate
        $total = $query->where('event_type', AiAnalytics::EVENT_AI_RESPONSE)->count();
        $successful = (clone $query)->where('event_type', AiAnalytics::EVENT_AI_RESPONSE)->where('was_successful', true)->count();
        $this->successRate = $total > 0 ? round(($successful / $total) * 100, 2) : 0;

        // Top handoff reasons
        $this->topHandoffReasons = AiAnalytics::getTopHandoffReasons($tenantId, $days);

        // Busiest hours
        $this->busiestHours = AiAnalytics::getBusiestHours($tenantId, $days);

        // Daily trends (last 7 days)
        $this->dailyTrends = AiAnalytics::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays(min(7, $days)))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Language breakdown
        $this->languageBreakdown = AiAnalytics::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('detected_language')
            ->selectRaw('detected_language, COUNT(*) as count')
            ->groupBy('detected_language')
            ->pluck('count', 'detected_language')
            ->toArray();

        // Sentiment breakdown
        $this->sentimentBreakdown = AiAnalytics::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('user_sentiment')
            ->selectRaw('user_sentiment, COUNT(*) as count')
            ->groupBy('user_sentiment')
            ->pluck('count', 'user_sentiment')
            ->toArray();
    }

    public function render()
    {
        $assistants = PersonalAssistant::getAllForCurrentTenant();

        return view('livewire.tenant.ai.ai-performance-dashboard', [
            'assistants' => $assistants,
        ]);
    }
}
