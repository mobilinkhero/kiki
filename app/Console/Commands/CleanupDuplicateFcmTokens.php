<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanupDuplicateFcmTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:cleanup-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate FCM tokens - keep only the most recent user for each token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Searching for duplicate FCM tokens...');

        // Get all users with FCM tokens
        $usersWithTokens = User::whereNotNull('fcm_token')
            ->orderBy('updated_at', 'desc')
            ->get();

        $tokenMap = [];
        $duplicatesFound = 0;
        $tokensCleared = 0;

        foreach ($usersWithTokens as $user) {
            $token = $user->fcm_token;

            if (isset($tokenMap[$token])) {
                // This token already exists for another user
                // Clear it from this user (older one)
                $this->warn("⚠️  Duplicate found: User #{$user->id} has same token as User #{$tokenMap[$token]}");
                $user->update(['fcm_token' => null]);
                $tokensCleared++;
                $duplicatesFound++;
            } else {
                // First time seeing this token - keep it
                $tokenMap[$token] = $user->id;
            }
        }

        if ($duplicatesFound > 0) {
            $this->info("✅ Cleaned up {$tokensCleared} duplicate FCM tokens");
            $this->info("📊 Total unique tokens remaining: " . count($tokenMap));
        } else {
            $this->info("✅ No duplicate FCM tokens found!");
        }

        return 0;
    }
}
