<?php

namespace App\Console\Commands;

use App\Services\AiVisionService;
use Illuminate\Console\Command;

class TestAiVision extends Command
{
    protected $signature = 'ai:test-vision {image_url} {--message=} {--type=auto}';
    protected $description = 'Test AI Vision image analysis';

    public function handle()
    {
        $imageUrl = $this->argument('image_url');
        $message = $this->option('message') ?? 'What is this?';
        $type = $this->option('type');

        $this->info("Testing AI Vision...");
        $this->line("Image URL: $imageUrl");
        $this->line("Message: $message");
        $this->line("Analysis Type: $type");
        $this->line('');

        $service = new AiVisionService();
        $result = $service->analyzeImage($imageUrl, $message, null, $type);

        if ($result['success']) {
            $this->info('✅ ANALYSIS SUCCESSFUL');
            $this->line('');
            $this->line('=== AI VISION ANALYSIS ===');
            $this->line($result['analysis']);
            $this->line('');
            $this->line("Tokens Used: {$result['tokens_used']}");

            if (!empty($result['structured_data'])) {
                $this->line('');
                $this->line('=== STRUCTURED DATA ===');
                $this->line(json_encode($result['structured_data'], JSON_PRETTY_PRINT));
            }
        } else {
            $this->error('❌ ANALYSIS FAILED');
            $this->error($result['error']);
        }

        return 0;
    }
}
