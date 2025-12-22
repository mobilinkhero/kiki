# ✅ APG URL Configuration Fixed!

## 🔧 Issue Identified

The URLs in your code didn't match the URLs configured in your APG Merchant Portal.

### APG Portal Configuration:
- **Return URL**: `https://soft.chatvoo.com/payment/alfa/return`
- **Listener URL**: `https://soft.chatvoo.com/payment/alfa/callback`

### Previous Code URLs:
- **Return URL**: `https://dash.chatvoo.com/payment/apg/return`
- **Callback URL**: `https://dash.chatvoo.com/payment/apg/callback`

**This mismatch caused APG to reject the request with "Invalid Request"**

---

## ✅ What I Fixed

### 1. Updated Configuration File
**File**: `config/apg.php`

Changed URLs to match APG portal:
```php
'return_url' => 'https://soft.chatvoo.com/payment/alfa/return',
'callback_url' => 'https://soft.chatvoo.com/payment/alfa/callback',
'ipn_url' => 'https://soft.chatvoo.com/payment/alfa/ipn',
```

### 2. Added New Routes
**File**: `routes/web.php`

Added `/payment/alfa/` routes to match APG portal:
```php
Route::prefix('payment/alfa')->group(function () {
    Route::post('/initiate', ...);
    Route::get('/callback', ...);
    Route::get('/return', ...);
    Route::post('/ipn', ...);
});
```

**Note**: I kept the old `/payment/apg/` routes for backward compatibility.

---

## 🚀 Next Steps

### 1. Clear Config Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### 2. Test Again!

Now try the payment again:
1. Open: `https://dash.chatvoo.com/payment/apg/test`
2. Click "Pay PKR 10"
3. Watch the debug console

**The handshake should now succeed!** ✅

---

## 📊 What to Expect in Debug Console

You should now see:
```json
{
  "action": "APG_HANDSHAKE_RESPONSE",
  "status_code": 200,
  "response_body": {
    "success": "true",
    "AuthToken": "some-long-token-here",
    "ReturnURL": "https://soft.chatvoo.com/payment/alfa/callback",
    "ErrorMessage": ""
  }
}
```

Instead of:
```json
{
  "success": "false",
  "ErrorMessage": "Invalid Request"
}
```

---

## 🔒 Important Notes

### Domain Configuration
Your APG is configured for `soft.chatvoo.com`, not `dash.chatvoo.com`.

**Options:**
1. ✅ **Use current setup** - Routes work on both domains
2. **Update APG portal** - Change URLs to `dash.chatvoo.com` (requires APG support)

### IPN URL
Make sure to inform your APG Business Owner to whitelist:
```
https://soft.chatvoo.com/payment/alfa/ipn
```

---

## 📝 Optional: Add to .env

You can override URLs in `.env` if needed:
```env
APG_RETURN_URL=https://soft.chatvoo.com/payment/alfa/return
APG_CALLBACK_URL=https://soft.chatvoo.com/payment/alfa/callback
APG_IPN_URL=https://soft.chatvoo.com/payment/alfa/ipn
```

---

## ✅ Summary

- ✅ URLs now match APG portal configuration
- ✅ Routes added for `/payment/alfa/` path
- ✅ Old `/payment/apg/` routes still work
- ✅ Configuration updated
- ✅ Ready to test!

**Try the payment now - it should work!** 🎉
