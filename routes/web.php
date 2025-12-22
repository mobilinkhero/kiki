<?php

// routes/web.php

use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentGateways\PaystackController;
use App\Http\Controllers\PaymentGateways\RazorpayController;
use App\Http\Controllers\PaymentGateways\StripeController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TermsConditionsController;
use App\Http\Controllers\Whatsapp\WhatsAppWebhookController;
use App\Http\Middleware\SanitizeInputs;
use Corbital\Installer\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

// Include debug routes (REMOVE AFTER DEBUGGING)
if (file_exists(__DIR__ . '/debug.php') && config('app.debug')) {
    include __DIR__ . '/debug.php';
}

Route::get('/', [HomeController::class, 'landingPage'])->name('home');

// Debug routes removed - can be re-enabled in the future if needed

Route::get('/validate', [InstallController::class, 'validate'])->name('validate');
Route::post('/validate', [InstallController::class, 'validateLicense'])->name('validate.license');

// Authentication related routes
require __DIR__ . '/auth.php';

// Authenticated user routes
Route::middleware(['auth', SanitizeInputs::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// WhatsApp Webhook Route (Supports GET & POST)
Route::match(['get', 'post'], '/whatsapp/webhook', [WhatsAppWebhookController::class, '__invoke'])
    ->name('whatsapp.webhook');

Route::match(['get', 'post'], 'webhooks/stripe', [StripeController::class, 'webhook'])
    ->name('webhook.stripe');

// CSRF Token refresher route
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf.token');

Route::match(['get', 'post'], 'webhooks/razorpay', [RazorpayController::class, 'webhook'])
    ->name('webhook.razorpay');

// Paystack webhook
Route::match(['get', 'post'], 'webhooks/paystack', [PaystackController::class, 'webhook'])
    ->name('webhook.paystack');

// APG (Alfa Payment Gateway) Routes
Route::prefix('payment/apg')->name('payment.apg.')->group(function () {
    Route::post('/initiate', [App\Http\Controllers\Payment\ApgPaymentController::class, 'initiatePayment'])
        ->name('initiate')->middleware('auth');
    Route::get('/callback', [App\Http\Controllers\Payment\ApgPaymentController::class, 'handleCallback'])
        ->name('callback');
    Route::get('/return', [App\Http\Controllers\Payment\ApgPaymentController::class, 'handleReturn'])
        ->name('return');
    Route::post('/ipn', [App\Http\Controllers\Payment\ApgPaymentController::class, 'handleIpn'])
        ->name('ipn');
    Route::get('/status/{transactionRef}', [App\Http\Controllers\Payment\ApgPaymentController::class, 'getStatus'])
        ->name('status');
});

// APG Routes with /alfa/ path (to match APG portal configuration)
Route::prefix('payment/alfa')->name('payment.alfa.')->group(function () {
    Route::post('/initiate', [App\Http\Controllers\Payment\ApgPaymentController::class, 'initiatePayment'])
        ->name('initiate')->middleware('auth');
    Route::match(['get', 'post'], '/callback', [App\Http\Controllers\Payment\ApgPaymentController::class, 'handleCallback'])
        ->name('callback');
    Route::match(['get', 'post'], '/return', [App\Http\Controllers\Payment\ApgPaymentController::class, 'handleReturn'])
        ->name('return');
    Route::match(['get', 'post'], '/ipn', [App\Http\Controllers\Payment\ApgPaymentController::class, 'handleIpn'])
        ->name('ipn');
});

// APG Test Page (can be accessed without auth for testing)
Route::get('/payment/apg/test', function () {
    return view('payment.apg.test');
})->name('payment.apg.test');

// APG Debug Console (for development/testing)
Route::get('/payment/apg/debug', function () {
    return view('payment.apg.debug');
})->name('payment.apg.debug');

Route::get('/payment/apg/debug/log', function () {
    $logFile = storage_path('logs/paymentgateway.log');

    if (!file_exists($logFile)) {
        return response()->json(['logs' => []]);
    }

    $content = file_get_contents($logFile);
    $entries = explode("\n\n", trim($content));

    $logs = array_map(function ($entry) {
        $decoded = json_decode($entry, true);
        return $decoded ?: ['raw' => $entry];
    }, array_filter($entries));

    return response()->json(['logs' => $logs]);
})->name('payment.apg.debug.log');

Route::post('/payment/apg/debug/clear', function () {
    $logFile = storage_path('logs/paymentgateway.log');
    if (file_exists($logFile)) {
        file_put_contents($logFile, '');
    }
    return response()->json(['success' => true]);
})->name('payment.apg.debug.clear');

Route::get('/payment/success/{transaction}', [App\Http\Controllers\Payment\ApgPaymentController::class, 'success'])
    ->name('payment.success')->middleware('auth');
Route::get('/payment/failed/{transaction}', [App\Http\Controllers\Payment\ApgPaymentController::class, 'failed'])
    ->name('payment.failed')->middleware('auth');

// PayPal routes
Route::match(['get', 'post'], 'webhooks/paypal', [\App\Http\Controllers\PaymentGateways\PayPalController::class, 'handleWebhook'])
    ->name('webhooks.paypal');

Route::get('paypal/subscription/success', [\App\Http\Controllers\PaymentGateways\PayPalController::class, 'subscriptionSuccess'])
    ->name('paypal.subscription.success');

Route::get('paypal/subscription/cancel', [\App\Http\Controllers\PaymentGateways\PayPalController::class, 'subscriptionCancel'])
    ->name('paypal.subscription.cancel');

Route::get('back-to-admin', [AuthenticatedSessionController::class, 'back_to_admin'])->name('back.to.admin');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('privacy.policy');
Route::get('/terms-conditions', [TermsConditionsController::class, 'show'])->name('terms.conditions');

// Theme Style CSS Routes
Route::get('/theme-style-css', [App\Http\Controllers\Admin\ThemeStyleController::class, 'css'])
    ->name('theme-style-css');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/api/translations/{locale?}', [TranslationController::class, 'index'])
    ->name('api.translations');
