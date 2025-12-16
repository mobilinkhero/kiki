# Alfa Payment Gateway Integration

I have implemented the Alfa Payment Gateway integration based on the provided v1.1 guide. 

## 1. Implementation Details
- **Controller**: Created `app/Http/Controllers/PaymentGateways/AlfaController.php` which handles:
  - **Checkout**: Initiates the Handshake (Step 1) by POSTing to the Alfa `HS` URL.
  - **Process**: Receives the `AuthToken` return, and initiates the redirection to the Payment Page (Step 2) by POSTing to the Alfa `SSO` URL.
  - **Callback**: Handles the final success/failure response from Alfa.
- **Views**: Created:
  - `resources/views/payment-gateways/alfa/checkout.blade.php`: Auto-submitting form for Handshake.
  - `resources/views/payment-gateways/alfa/redirect.blade.php`: Auto-submitting form for SSO Redirection.
- **Settings**: Added Alfa configuration fields to `app/Settings/PaymentSettings.php`.
- **Routes**: Registered `payment.alfa.checkout`, `payment.alfa.return`, and `payment.alfa.callback` in `routes/tenant/tenant.php`.

## 2. Missing Encryption Logic
**CRITICAL**: The integration guide mentions: *"See Encryption method in the Sample code attached"*. 
Since the sample code was not provided, I could not implement the `generateRequestHash` method.
You must replace the placeholder `DUMMY_HASH_NEED_IMPLEMENTATION` in `AlfaController.php` with the actual hash generation logic provided by Bank Alfalah (usually AES or HMAC).

## 3. Important System Issue Detected
During the process, I noticed a critical error in your project:
`Trait "Laravel\Sanctum\HasApiTokens" not found`
This indicates the `laravel/sanctum` package is missing from your dependencies, causing the `User` model to crash.
I have attempted to install it via `composer require laravel/sanctum`. If this fails, you must install it manually to restore application functionality.

## 4. How to Enable
1.  Run `php artisan settings:discover` (after fixing the Sanctum error).
2.  Go to your Tenant Settings and enable "Alfa Payment".
3.  Enter your Alfa Merchant credentials (ID, Store ID, Hash, Username, Password).
