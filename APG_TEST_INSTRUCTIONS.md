# 🧪 APG Payment Test Instructions

## 🌐 Access the Test Page

Open this URL in Chrome:

```
https://dash.chatvoo.com/payment/apg/test
```

---

## ✅ Before Testing - Add Credentials to .env

**IMPORTANT**: Make sure you've added the APG credentials to your production `.env` file:

```bash
# SSH to your server
ssh u108339042@us-bos-web1915.servconfig.com

# Edit .env file
cd /home/u108339042/domains/dash.chatvoo.com/public_html
nano .env
```

Add these lines at the end:
```env
APG_ENABLED=true
APG_ENVIRONMENT=production
APG_MERCHANT_ID=233462
APG_STORE_ID=524122
APG_MERCHANT_HASH=OUU362MB1urSPofgyh3P9tUKThUQaeazHUfnKCVWWSGfpgxBX0Drh3bUaozpQdsANhXgxRACoCo=
APG_MERCHANT_USERNAME=yzowem
APG_MERCHANT_PASSWORD=0Gm8LIw4CFZvFzk4yqF7CA==
APG_ENCRYPTION_KEY1=dZUc9QUgPQP8pnKY
APG_ENCRYPTION_KEY2=9956991048721627
APG_CURRENCY=PKR
APG_LOG_REQUESTS=true
```

Save and exit (Ctrl+X, then Y, then Enter)

Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🎯 Testing Steps

1. **Open the test page** in Chrome:
   ```
   https://dash.chatvoo.com/payment/apg/test
   ```

2. **Choose a test amount**:
   - PKR 10 (Small test)
   - PKR 100 (Medium test)
   - PKR 1,000 (Subscription test)

3. **Click "Pay" button**

4. **Expected Flow**:
   - ✅ You'll see "Processing Payment" page (1 second)
   - ✅ Redirected to "Redirecting to Secure Payment" page
   - ✅ Automatically redirected to APG payment page
   - ✅ Enter your card details on APG
   - ✅ Complete payment
   - ✅ Redirected back to Success or Failed page

---

## 📊 Monitor Transactions

### Check Database:
```sql
-- View recent transactions
SELECT * FROM apg_transactions ORDER BY created_at DESC LIMIT 10;

-- View payment logs
SELECT * FROM apg_payment_logs ORDER BY created_at DESC LIMIT 20;
```

### Check Laravel Logs:
```bash
tail -f storage/logs/laravel.log
```

---

## ⚠️ Troubleshooting

### If you get "Invalid Request" error:
- Check if credentials are in `.env`
- Run `php artisan config:clear`
- Check Laravel logs

### If payment doesn't redirect back:
- Check return URL is correct: `https://dash.chatvoo.com/payment/apg/return`
- Check if transaction was created in database

### If IPN doesn't work:
- Contact APG Business Owner to whitelist: `https://dash.chatvoo.com/payment/apg/ipn`

---

## 🎉 Success!

If everything works, you should see:
- ✅ Transaction record in `apg_transactions` table
- ✅ Payment logs in `apg_payment_logs` table
- ✅ Success page with transaction details
- ✅ Status = "paid" in database

**You're ready to accept real payments!** 💰
