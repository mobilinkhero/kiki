# 🎉 APG Encryption FIXED - AES-128-CBC Implementation

## ✅ **Critical Fix Applied**

Based on the APG sample code, I've corrected the encryption method:

### **Previous (WRONG):**
- ❌ Using 3DES encryption
- ❌ Encrypting only: `MerchantID + StoreID + TransactionRef`

### **Current (CORRECT):**
- ✅ Using **AES-128-CBC** encryption
- ✅ Encrypting **entire query string** of all parameters
- ✅ Key1 as encryption key (16 bytes)
- ✅ Key2 as IV (16 bytes)

---

## 📝 **How APG Encryption Works**

### From APG's JavaScript Sample Code:
```javascript
CryptoJS.AES.encrypt(
    CryptoJS.enc.Utf8.parse(mapString),  // Query string of all params
    CryptoJS.enc.Utf8.parse($("#Key1").val()),  // Key1 = encryption key
    {
        keySize: 128 / 8,
        iv: CryptoJS.enc.Utf8.parse($("#Key2").val()),  // Key2 = IV
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    }
)
```

### Our PHP Implementation:
```php
public function generateHash($data)
{
    $key1 = $this->config['encryption']['key1']; // 16 bytes - AES key
    $key2 = $this->config['encryption']['key2']; // 16 bytes - IV

    // AES-128-CBC encryption
    $encrypted = openssl_encrypt(
        $data,
        'aes-128-cbc',
        $key1,
        OPENSSL_RAW_DATA,
        $key2
    );

    return base64_encode($encrypted);
}
```

---

## 🔐 **What Gets Encrypted**

### Query String Format:
```
HS_RequestHash=&HS_IsRedirectionRequest=0&HS_ChannelId=1001&HS_ReturnURL=https://...&HS_MerchantId=233462&HS_StoreId=524122&HS_MerchantHash=...&HS_MerchantUsername=yzowem&HS_MerchantPassword=...&HS_TransactionReferenceNumber=TXN123
```

This entire string is encrypted with AES-128-CBC and then base64 encoded.

---

## 🚀 **Test Now!**

### 1. Clear Logs
Click 🗑 Clear Log in debug console

### 2. Try Payment
Go to test page and click "Pay PKR 10"

### 3. Check Debug Console
You should now see:
- **query_string_to_encrypt**: Full query string
- **encryption_key**: Your Key1
- **encryption_iv**: Your Key2
- **generated_hash**: New AES-encrypted hash

### 4. Expected Result
```json
{
  "success": "true",
  "AuthToken": "long-token-here...",
  "ReturnURL": "https://soft.chatvoo.com/payment/alfa/callback",
  "ErrorMessage": ""
}
```

---

## 📊 **Changes Made**

### File: `app/Services/Payment/AlfaPaymentService.php`

1. **generateHash()** - Changed from 3DES to AES-128-CBC
2. **generateRequestHash()** - Now builds query string from all params
3. **initiateHandshake()** - Builds params first, then generates hash

---

## 🎯 **Why It Was Failing**

1. ❌ **Wrong Algorithm**: We were using 3DES, APG uses AES
2. ❌ **Wrong Data**: We were encrypting `MerchantID+StoreID+TxnRef`, APG encrypts full query string
3. ❌ **Wrong Keys**: We were combining keys, APG uses Key1=key, Key2=IV

---

## ✅ **Now It Should Work!**

The encryption now **exactly matches** APG's JavaScript sample code.

**Test it and let me know the result!** 🎉
