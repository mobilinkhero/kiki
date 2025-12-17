<?php

// Simple test script to verify push notification setup
// Run this from command line: php test_fcm_setup.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing FCM Push Notification Setup\n";
echo "=====================================\n\n";

// Test 1: Check if logging channel exists
echo "1. Checking logging configuration...\n";
$logConfig = config('logging.channels.push_notification');
if ($logConfig) {
    echo "   ✅ push_notification log channel configured\n";
    echo "   📁 Log file: " . $logConfig['path'] . "\n\n";
} else {
    echo "   ❌ push_notification log channel NOT found\n\n";
    exit(1);
}

// Test 2: Check service account file
echo "2. Checking Firebase service account file...\n";
$serviceAccountPath = storage_path('app/firebase-service-account.json');
if (file_exists($serviceAccountPath)) {
    echo "   ✅ Service account file exists\n";
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
    echo "   📧 Email: " . ($serviceAccount['client_email'] ?? 'N/A') . "\n";
    echo "   🆔 Project ID: " . ($serviceAccount['project_id'] ?? 'N/A') . "\n\n";
} else {
    echo "   ❌ Service account file NOT found at: $serviceAccountPath\n";
    echo "   ⚠️  You need to upload firebase-service-account.json\n\n";
}

// Test 3: Check .env configuration
echo "3. Checking .env configuration...\n";
$projectId = env('FCM_PROJECT_ID');
if ($projectId) {
    echo "   ✅ FCM_PROJECT_ID: $projectId\n\n";
} else {
    echo "   ⚠️  FCM_PROJECT_ID not set in .env\n\n";
}

// Test 4: Check if any users have FCM tokens
echo "4. Checking for users with FCM tokens...\n";
$usersWithTokens = \App\Models\User::whereNotNull('fcm_token')->count();
echo "   📱 Users with FCM tokens: $usersWithTokens\n";
if ($usersWithTokens > 0) {
    $user = \App\Models\User::whereNotNull('fcm_token')->first();
    echo "   👤 Example user: " . $user->firstname . " " . $user->lastname . "\n";
    echo "   🔑 Token preview: " . substr($user->fcm_token, 0, 30) . "...\n\n";
} else {
    echo "   ⚠️  No users have FCM tokens yet\n";
    echo "   💡 Users need to login on Android app to register tokens\n\n";
}

// Test 5: Test logging
echo "5. Testing log file creation...\n";
try {
    \Log::channel('push_notification')->info('🧪 Test log entry from setup script', [
        'timestamp' => now()->toDateTimeString(),
        'test' => true,
    ]);
    echo "   ✅ Log entry written successfully\n";
    echo "   📄 Check: tail -f " . storage_path('logs/push_notification_debug.log') . "\n\n";
} catch (\Exception $e) {
    echo "   ❌ Failed to write log: " . $e->getMessage() . "\n\n";
}

// Summary
echo "=====================================\n";
echo "📊 SUMMARY\n";
echo "=====================================\n";

$allGood = true;

if (!$logConfig) {
    echo "❌ Logging not configured\n";
    $allGood = false;
}

if (!file_exists($serviceAccountPath)) {
    echo "❌ Service account file missing\n";
    $allGood = false;
}

if (!$projectId) {
    echo "⚠️  FCM_PROJECT_ID not set\n";
    $allGood = false;
}

if ($usersWithTokens === 0) {
    echo "⚠️  No FCM tokens registered\n";
    $allGood = false;
}

if ($allGood) {
    echo "✅ All checks passed!\n";
    echo "\n📋 Next steps:\n";
    echo "   1. Send a WhatsApp message to trigger notification\n";
    echo "   2. Monitor: tail -f storage/logs/push_notification_debug.log\n";
    echo "   3. Check Android device for notification\n";
} else {
    echo "\n⚠️  Some issues found - please fix them first\n";
}

echo "\n";
