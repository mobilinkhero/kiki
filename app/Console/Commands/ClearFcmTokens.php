<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearFcmTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all FCM tokens from all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Counting users with FCM tokens...');

        $count = User::whereNotNull('fcm_token')->count();

        if ($count === 0) {
            $this->info('✅ No FCM tokens found. Nothing to clear.');
            return 0;
        }

        $this->warn("⚠️  Found {$count} users with FCM tokens.");

        if (!$this->confirm('Do you want to clear all FCM tokens?', true)) {
            $this->info('❌ Operation cancelled.');
            return 0;
        }

        $this->info('🗑️  Clearing all FCM tokens...');

        User::whereNotNull('fcm_token')->update(['fcm_token' => null]);

        $this->info("✅ Successfully cleared {$count} FCM tokens!");
        $this->info('📱 Users will need to re-login to the app to receive notifications again.');

        return 0;
    }
}
