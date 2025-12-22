# 🔧 APG Encryption Fix Applied

## Problem Identified
The 3DES encryption was not using the correct mode. APG likely requires:
- **Cipher**: `des-ede3-cbc` (3DES with CBC mode)
- **IV**: Zero IV (8 bytes of null characters)
- **Key**: Exactly 24 bytes

## What Was Changed

### Before:
```php
$encrypted = openssl_encrypt(
    $data,
    'des-ede3',      // ECB mode (implicit)
    $key,
    OPENSSL_RAW_DATA
);
```

### After:
```php
// Ensure key is exactly 24 bytes
if (strlen($key) < 24) {
    $key = str_pad($key, 24, "\0");
} elseif (strlen($key) > 24) {
    $key = substr($key, 0, 24);
}

// Use zero IV for CBC mode
$iv = str_repeat("\0", 8);

$encrypted = openssl_encrypt(
    $data,
    'des-ede3-cbc',  // CBC mode with IV
    $key,
    OPENSSL_RAW_DATA,
    $iv
);
```

## Key Details

Your encryption keys:
- **Key 1**: `dZUc9QUgPQP8pnKY` (16 bytes)
- **Key 2**: `9956991048721627` (16 bytes)
- **Combined**: 32 bytes → **Trimmed to 24 bytes** for 3DES

## Test Now!

1. **Clear logs** in debug console
2. **Try payment** again (PKR 10)
3. **Check debug console** for new hash

The generated hash should now be different and hopefully accepted by APG!

## Expected Result

✅ **Success response**:
```json
{
  "success": "true",
  "AuthToken": "long-token-here",
  "ReturnURL": "https://soft.chatvoo.com/payment/alfa/callback",
  "ErrorMessage": ""
}
```

❌ **If still fails**, we may need to try:
- Different cipher modes
- Different IV values
- Contact APG support for exact encryption specs

---

**Try the payment now!** 🚀
