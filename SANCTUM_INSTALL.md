# Laravel Sanctum Installation Guide

## Step 1: Install Sanctum Package

```bash
composer require laravel/sanctum
```

## Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Step 3: Run Migration

```bash
php artisan migrate
```

This creates the `personal_access_tokens` table.

## Step 4: Add Sanctum Middleware

**File:** `app/Http/Kernel.php`

Add to `api` middleware group:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

## Step 5: Configure CORS (if needed)

**File:** `config/cors.php`

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],

'supports_credentials' => true,
```

## Step 6: Add HasApiTokens Trait to User Model

**File:** `app/Models/User.php`

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    // ... rest of your model
}
```

## Step 7: Protect Routes

**File:** `routes/api.php`

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Your protected routes here
});
```

## ✅ That's it! Sanctum is installed.

### Test It:

```bash
# Login and get token
curl -X POST https://dash.chatvoo.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Use token
curl -X GET https://dash.chatvoo.com/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Quick Commands:

```bash
# Install
composer require laravel/sanctum

# Publish & Migrate
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# Clear cache
php artisan config:clear
php artisan cache:clear
```

Done! 🎉
