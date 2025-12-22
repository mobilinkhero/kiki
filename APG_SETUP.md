# APG Integration - Environment Variables

## Add these to your .env file:

```env
# Alfa Payment Gateway (APG) Configuration
APG_ENABLED=true
APG_ENVIRONMENT=production

# Production Credentials
APG_MERCHANT_ID=233462
APG_STORE_ID=524122
APG_MERCHANT_HASH=OUU362MB1urSPofgyh3P9tUKThUQaeazHUfnKCVWWSGfpgxBX0Drh3bUaozpQdsANhXgxRACoCo=
APG_MERCHANT_USERNAME=yzowem
APG_MERCHANT_PASSWORD=0Gm8LIw4CFZvFzk4yqF7CA==

# Encryption Keys
APG_ENCRYPTION_KEY1=dZUc9QUgPQP8pnKY
APG_ENCRYPTION_KEY2=9956991048721627

# Currency
APG_CURRENCY=PKR

# Logging
APG_LOG_REQUESTS=true
```

## Important URLs (Auto-configured):
- **Return URL**: `https://dash.chatvoo.com/payment/apg/return`
- **Callback URL**: `https://dash.chatvoo.com/payment/apg/callback`
- **IPN URL**: `https://dash.chatvoo.com/payment/apg/ipn`

## Next Steps:

1. **Add credentials to .env**:
   ```bash
   # Copy the above configuration to your .env file
   ```

2. **Run migrations**:
   ```bash
   php artisan migrate
   ```

3. **Clear config cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Inform APG Business Owner**:
   - Contact your APG Business Owner
   - Request IPN URL whitelisting for: `https://dash.chatvoo.com/payment/apg/ipn`
   - This is required for real-time payment notifications

## Testing Payment Flow:

### Initiate a test payment:
```php
// Example: From your subscription controller
use App\Services\Payment\AlfaPaymentService;

$apgService = new AlfaPaymentService();

// Create transaction and redirect to payment
return redirect()->route('payment.apg.initiate')->with([
    'amount' => 1000, // PKR 1000
    'transaction_type' => 'subscription',
    'related_id' => $subscription->id
]);
```

### Or use a simple form:
```html
<form action="{{ route('payment.apg.initiate') }}" method="POST">
    @csrf
    <input type="hidden" name="amount" value="1000">
    <input type="hidden" name="transaction_type" value="subscription">
    <input type="hidden" name="related_id" value="1">
    <button type="submit">Pay with APG</button>
</form>
```

## Payment Flow:
1. User clicks "Pay" → `POST /payment/apg/initiate`
2. System creates transaction → Calls APG handshake
3. User redirected → `GET /payment/apg/callback`
4. System prepares payment → Auto-submits to APG
5. User completes payment on APG
6. APG redirects back → `GET /payment/apg/return`
7. System verifies payment → Shows success/failed page
8. APG sends IPN → `POST /payment/apg/ipn` (async notification)

## Security Notes:
- ✅ All credentials stored in .env (not in code)
- ✅ 3DES encryption for request hashing
- ✅ Transaction logging for audit trail
- ✅ HTTPS required for all URLs
- ✅ CSRF protection on all POST routes

## Monitoring:
- Check `apg_transactions` table for payment records
- Check `apg_payment_logs` table for API interaction logs
- Monitor Laravel logs for errors: `storage/logs/laravel.log`
