# ⚠️ CRITICAL: Alfa Encryption Keys Required

## Current Status
The Alfa Payment Gateway integration is **95% complete** but **cannot work** without encryption keys.

## What You Have ✅
- Merchant ID: `233462`
- Store ID: `524122`
- Merchant Hash: `OUU362MB1urSPofgyh3P9tUKThUQaeazHUfnKCVWWSGfpgxBX0Drh2oCoSNjPvhzn0fQey5Z5u0=`
- Merchant Username: `zezawi`
- Merchant Password: `Yi7FXlgDxjxvFzk4yqF7CA==`

## What You're Missing ❌
- **Key1** (Encryption Key for AES)
- **Key2** (IV - Initialization Vector for AES)

## Where to Get Them

### Option 1: Download Sample Code
Check your Alfa merchant portal:
1. Go to **Integration** → **Page Redirection**
2. Scroll to the bottom
3. Look for **"Post method Sample Code (Encryption Mechanism Embedded)"**
4. Download the sample code - it should contain Key1 and Key2

### Option 2: Google Drive Link
The documentation mentions this link:
```
https://drive.google.com/open?id=141dRIb6qvSxwROQicLCvOrvEVThDERyq
```
Download and check for encryption keys.

### Option 3: Contact Bank Alfalah
Email/Call your Bank Alfalah account manager and request:
> "Please provide the Encryption Keys (Key1 and Key2) for AES encryption in sandbox environment"

## How the Hash Works

According to Alfa documentation, the hash is generated using:

```javascript
CryptoJS.AES.encrypt(
    CryptoJS.enc.Utf8.parse(mapString),
    CryptoJS.enc.Utf8.parse(Key1),  // ← YOU NEED THIS
    {
        keySize: 128 / 8,
        iv: CryptoJS.enc.Utf8.parse(Key2),  // ← YOU NEED THIS
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    }
)
```

Where `mapString` format is:
```
HS_ChannelId=1001&HS_MerchantId=233462&HS_StoreId=524122&...
```

## Once You Get the Keys

Add them to your admin panel settings:
1. Go to `/admin/settings` → Click **Alfa**
2. Add two new fields:
   - **Encryption Key (Key1)**
   - **IV Key (Key2)**
3. Save

Then the integration will work immediately!

## Current Error
```json
{
    "success": "false",
    "AuthToken": null,
    "ReturnURL": "https://soft.chatvoo.com/payment/alfa/return",
    "ErrorMessage": "Invalid Request"
}
```

This is because the `HS_RequestHash` is incorrect without the proper encryption keys.
