# 🔍 APG Payment Debug Console - Instructions

## 🚀 Quick Start

### Step 1: Open Debug Console
Open this URL in Chrome:
```
https://dash.chatvoo.com/payment/apg/debug
```

### Step 2: Open Test Page in Another Tab
```
https://dash.chatvoo.com/payment/apg/test
```

### Step 3: Monitor in Real-Time
1. Keep the **Debug Console** tab open
2. Switch to the **Test Page** tab
3. Click "Pay PKR 10" button
4. Switch back to **Debug Console** to see live logs

---

## 📊 What You'll See in Debug Console

### Real-Time Logs Show:
- ✅ **Request Data** - Amount, transaction type, user info
- ✅ **Transaction Reference** - Generated unique ID
- ✅ **Database Record** - Created transaction details
- ✅ **APG Handshake Request** - Sent to APG
- ✅ **APG Response** - Success/failure with auth token
- ✅ **Errors** - Full exception details with stack trace

### Log Types:
- 🟢 **Green Border** - Success
- 🔵 **Blue Border** - Info/Request
- 🔴 **Red Border** - Error/Exception

---

## 🎯 Features

### Auto-Refresh
- Logs refresh every 2 seconds automatically
- Click "Pause Auto-Refresh" to stop
- Click "Resume Auto-Refresh" to continue

### Manual Refresh
- Click "🔄 Refresh" to update immediately

### Clear Logs
- Click "🗑 Clear Log" to delete all logs
- Useful for starting fresh tests

### Open Test Page
- Click "🧪 Open Test Page" to open payment test in new tab

---

## 📝 Log File Location

All logs are saved to:
```
storage/logs/paymentgateway.log
```

You can also view this file directly on the server:
```bash
tail -f storage/logs/paymentgateway.log
```

---

## 🐛 Debugging Common Issues

### Issue: No logs appearing
**Solution**: 
- Check if `storage/logs` directory is writable
- Run: `chmod -R 775 storage/logs`

### Issue: "Invalid Request" error in logs
**Solution**:
- Check if credentials are in `.env`
- Verify encryption keys are correct
- Check APG merchant ID and store ID

### Issue: Handshake fails
**Solution**:
- Check handshake response in logs
- Verify merchant credentials
- Check if APG production URLs are correct

### Issue: Payment doesn't redirect
**Solution**:
- Check if auth token was received
- Verify callback URL is accessible
- Check browser console for JavaScript errors

---

## 📋 Example Log Entry

```json
{
    "timestamp": "2025-12-22 14:03:20",
    "action": "INITIATE_PAYMENT",
    "request_data": {
        "amount": "10",
        "transaction_type": "test"
    },
    "user_id": null,
    "ip": "192.168.1.1"
}
```

---

## 🎨 Console Features

### Color Coding
- **Keys** - Blue (`"amount":`)
- **Strings** - Orange (`"test"`)
- **Numbers** - Green (`10`)
- **Booleans** - Blue (`true`, `false`)
- **Null** - Blue (`null`)

### Status Indicator
- **● LIVE** - Auto-refresh enabled (green)
- **● PAUSED** - Auto-refresh paused (gray)

---

## 🔥 Pro Tips

1. **Keep Debug Console Open** - Monitor all requests in real-time
2. **Use Small Amounts** - Test with PKR 10 first
3. **Check Each Step** - Verify handshake → payment → return flow
4. **Clear Logs Between Tests** - Easier to track individual tests
5. **Copy Error Details** - Full stack traces available in logs

---

## ✅ Successful Payment Flow Logs

You should see these entries in order:

1. **INITIATE_PAYMENT** - Request received
2. **Generated Transaction Ref** - TXN1234567890ABC
3. **Transaction Created** - Database record
4. **Calling APG Handshake** - Sending request
5. **Handshake Response** - Auth token received
6. **SUCCESS** - Redirecting to payment

If you see all these, the integration is working! 🎉

---

## 🆘 Need Help?

If you see errors in the debug console:
1. Copy the full error JSON
2. Check the `error`, `file`, and `line` fields
3. Look for the exception message
4. Check if it's a configuration issue or APG API issue

**Happy Debugging! 🚀**
