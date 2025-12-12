<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="mb-4 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('tenant.ai-assistant', ['subdomain' => $subdomain]) }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    AI Assistant
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Performance
                        Dashboard</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📊 AI Performance Dashboard</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track and analyze your AI assistant's performance
            metrics</p>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-wrap gap-4">
        <!-- Date Range Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Period</label>
            <select wire:model.live="dateRange"
                class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
            </select>
        </div>

        <!-- Assistant Filter -->
        @if($assistants && $assistants->count() > 1)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assistant</label>
                <select wire:model.live="selectedAssistant"
                    class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">All Assistants</option>
                    @foreach($assistants as $assistant)
                        <option value="{{ $assistant->id }}">{{ $assistant->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Conversations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Conversations</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ number_format($totalConversations) }}
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Handoff Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Handoff Rate</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $handoffRate }}%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $handoffRate < 15 ? '✅ Excellent' : ($handoffRate < 30 ? '⚠️ Good' : '🔴 Needs Improvement') }}
                    </p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $successRate }}%</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $successRate > 95 ? '🎉 Excellent' : ($successRate > 85 ? '✅ Good' : '⚠️ Needs Work') }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Avg Response Time -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Response Time</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $averageResponseTime ? round($averageResponseTime / 1000, 1) . 's' : 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $averageResponseTime < 3000 ? '⚡ Fast' : ($averageResponseTime < 5000 ? '✅ Good' : '⏱️ Slow') }}
                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Handoff Reasons -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Handoff Reasons</h3>
            @if(count($topHandoffReasons) > 0)
                <div class="space-y-3">
                    @foreach($topHandoffReasons as $reason => $count)
                        @php
                            $percentage = $totalConversations > 0 ? round(($count / $totalConversations) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span
                                    class="text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $reason) }}</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($percentage * 10, 100) }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No handoff data yet</p>
            @endif
        </div>

        <!-- Busiest Hours -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Busiest Hours (24h)</h3>
            @if(count($busiestHours) > 0)
                <div class="space-y-2">
                    @php
                        $maxCount = max($busiestHours);
                    @endphp
                    @for($hour = 0; $hour < 24; $hour++)
                        @php
                            $count = $busiestHours[$hour] ?? 0;
                            $width = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs text-gray-600 dark:text-gray-400 w-12">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</span>
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-4 rounded-full"
                                    style="width: {{ $width }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-400 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No data yet</p>
            @endif
        </div>
    </div>

    <!-- Daily Trends -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daily Activity Trend</h3>
        @if(count($dailyTrends) > 0)
            <div class="flex items-end justify-between h-64 gap-2">
                @php
                    $maxDaily = max($dailyTrends);
                @endphp
                @foreach($dailyTrends as $date => $count)
                    @php
                        $height = $maxDaily > 0 ? ($count / $maxDaily) * 100 : 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t hover:from-blue-700 hover:to-blue-500 transition-all cursor-pointer"
                            style="height: {{ $height }}%" title="{{ $count }} conversations on {{ $date }}">
                        </div>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400 mt-2 rotate-45 origin-top-left">{{ \Carbon\Carbon::parse($date)->format('M d') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No daily data yet</p>
        @endif
    </div>

    <!-- New Analytics Row: Language & Sentiment -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Language Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🌍 Language Distribution</h3>
            @if(count($languageBreakdown ?? []) > 0)
                <div class="space-y-3">
                    @php
                        $totalLang = array_sum($languageBreakdown);
                        $languageLabels = [
                            'english' => '🇬🇧 English',
                            'roman_urdu' => '🇵🇰 Roman Urdu',
                            'urdu_script' => '🇵🇰 Urdu Script'
                        ];
                    @endphp
                    @foreach($languageBreakdown as $lang => $count)
                        @php
                            $percentage = $totalLang > 0 ? round(($count / $totalLang) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span
                                    class="text-gray-700 dark:text-gray-300">{{ $languageLabels[$lang] ?? ucfirst($lang) }}</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No language data yet</p>
            @endif
        </div>

        <!-- Sentiment Analysis -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">😊 User Sentiment</h3>
            @if(count($sentimentBreakdown ?? []) > 0)
                <div class="space-y-4">
                    @php
                        $totalSentiment = array_sum($sentimentBreakdown);
                        $sentimentConfig = [
                            'positive' => ['emoji' => '😊', 'label' => 'Positive', 'color' => 'from-green-500 to-emerald-500'],
                            'neutral' => ['emoji' => '😐', 'label' => 'Neutral', 'color' => 'from-blue-500 to-cyan-500'],
                            'negative' => ['emoji' => '😠', 'label' => 'Negative', 'color' => 'from-red-500 to-orange-500']
                        ];
                    @endphp

                    <!-- Pie Chart Visual -->
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        @foreach(['positive', 'neutral', 'negative'] as $sentiment)
                            @php
                                $count = $sentimentBreakdown[$sentiment] ?? 0;
                                $percentage = $totalSentiment > 0 ? round(($count / $totalSentiment) * 100, 1) : 0;
                                $config = $sentimentConfig[$sentiment];
                            @endphp
                            <div class="text-center">
                                <div class="text-4xl mb-2">{{ $config['emoji'] }}</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $percentage }}%</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">{{ $config['label'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-500">{{ $count }} msgs</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bar representation -->
                    @foreach($sentimentBreakdown as $sentiment => $count)
                        @php
                            $percentage = $totalSentiment > 0 ? round(($count / $totalSentiment) * 100, 1) : 0;
                            $config = $sentimentConfig[$sentiment];
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300">{{ $config['emoji'] }}
                                    {{ $config['label'] }}</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r {{ $config['color'] }} h-2 rounded-full"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No sentiment data yet</p>
            @endif
        </div>
    </div>
</div>