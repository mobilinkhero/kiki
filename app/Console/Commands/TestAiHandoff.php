<?php

namespace App\Console\Commands;

use App\Services\AiHandoffService;
use Illuminate\Console\Command;

class TestAiHandoff extends Command
{
    protected $signature = 'ai:test-handoff {message}';
    protected $description = 'Test AI handoff detection';

    public function handle()
    {
        $message = $this->argument('message');
        $service = new AiHandoffService();

        $result = $service->shouldHandoff($message);

        $this->info("Testing message: $message");
        $this->line('');

        if ($result['should_handoff']) {
            $this->warn('🚨 HANDOFF DETECTED!');
            $this->line("Reason: {$result['reason']}");
            $this->line("Keyword: {$result['keyword']}");
            $this->line("Urgent: " . ($result['is_urgent'] ? 'YES' : 'NO'));
        } else {
            $this->info('✅ No handoff needed - AI can handle this');
        }

        return 0;
    }
}
