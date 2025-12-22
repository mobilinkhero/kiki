# 🔍 APG Integration Troubleshooting Checklist

## ❌ Current Issue
APG is returning: `"ErrorMessage": "Invalid Request"`

## 📋 Verification Checklist

### 1. ✅ Credentials Match (VERIFY THESE)

Go to your APG Merchant Portal and verify these **EXACTLY** match:

#### From Portal:
- [ ] **Merchant ID**: `233462`
- [ ] **Store ID**: `524122`
- [ ] **Merchant Hash**: `OUU362MB1urSPofgyh3P9tUKThUQaeazHUfnKCVWWSGfpgxBX0Drh3bUaozpQdsANhXgxRACoCo=`
- [ ] **Merchant Username**: `yzowem`
- [ ] **Merchant Password**: `0Gm8LIw4CFZvFzk4yqF7CA==`
- [ ] **Encryption Key 1**: `dZUc9QUgPQP8pnKY`
- [ ] **Encryption Key 2**: `9956991048721627`

#### From .env file:
```env
APG_MERCHANT_ID=233462
APG_STORE_ID=524122
APG_MERCHANT_HASH=OUU362MB1urSPofgyh3P9tUKThUQaeazHUfnKCVWWSGfpgxBX0Drh3bUaozpQdsANhXgxRACoCo=
APG_MERCHANT_USERNAME=yzowem
APG_MERCHANT_PASSWORD=0Gm8LIw4CFZvFzk4yqF7CA==
APG_ENCRYPTION_KEY1=dZUc9QUgPQP8pnKY
APG_ENCRYPTION_KEY2=9956991048721627
```

**⚠️ IMPORTANT**: Check for:
- Extra spaces
- Missing characters
- Wrong case (uppercase/lowercase)

---

### 2. ✅ URLs Match Portal Configuration

#### Portal URLs:
- [ ] **Return URL**: `https://soft.chatvoo.com/payment/alfa/return`
- [ ] **Listener URL**: `https://soft.chatvoo.com/payment/alfa/callback`

#### Our Configuration:
- ✅ Already updated to match

---

### 3. ❓ Encryption Method

The documentation says: **"See Encryption method in the Sample code attached"**

**CRITICAL**: We need the sample code from APG to see the exact encryption implementation!

#### Possible Issues:
1. **Wrong cipher mode** - We're using `des-ede3-cbc`, but APG might use different mode
2. **Wrong IV** - We're using zero IV, but APG might use different IV
3. **Wrong key format** - We're combining keys, but APG might use them differently
4. **Wrong padding** - We might need specific padding

---

### 4. 🔧 Next Steps

#### Option A: Get Sample Code from APG
1. Login to APG Merchant Portal
2. Go to: **Integration > Page Redirection**
3. Look for **"Sample Code"** or **"Download Sample"**
4. Get the PHP/Java sample code
5. Check the exact encryption method used

#### Option B: Contact APG Support
Ask them specifically:
- What encryption algorithm for `HS_RequestHash`?
- What cipher mode? (ECB, CBC, etc.)
- What IV value?
- How to combine the two encryption keys?
- Can they provide working sample code?

#### Option C: Try Alternative Encryption Methods

Let me create different encryption variations to test:

1. **ECB mode** (no IV)
2. **CBC mode with zero IV** (current)
3. **CBC mode with key-derived IV**
4. **Different key combinations**

---

## 🎯 Immediate Action Required

### Check Your APG Portal NOW:

1. **Login**: Go to APG Merchant Portal
2. **Navigate**: Integration > Page Redirection
3. **Look for**: 
   - Sample code download
   - Encryption example
   - Working code snippet
4. **Share**: If you find sample code, share it with me

### OR

**Contact APG Support** and ask:
> "I'm getting 'Invalid Request' error on handshake. Can you provide:
> 1. Sample PHP code for HS_RequestHash encryption
> 2. Exact encryption algorithm (cipher, mode, IV)
> 3. How to use the two encryption keys"

---

## 📞 APG Support Contact

Check your merchant portal for support contact details or email your Business Owner.

---

**Without the exact encryption method from APG's sample code, we're guessing!** 🎲

Let me know if you can find the sample code in your portal!
