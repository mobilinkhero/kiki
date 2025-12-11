# 🔍 Duplicate Message Debugging - Test Instructions

## Current Status
- ✅ Fix #1 Applied: Prevent multiple trigger execution
- ✅ Fix #2 Applied: Prevent duplicate edge execution  
- ✅ Debug Logging Added: Track execution flow
- ⚠️ **Issue Still Persists:** 2 messages being sent

---

## 🧪 Test Now & Check Logs

### Step 1: Send Test Message
1. Send "test" to your WhatsApp bot
2. Note the exact time you sent it

### Step 2: Check Laravel Logs
```bash
# In PowerShell (from kiki directory)
cd c:\wamp64\www\chatvoo\kiki
Get-Content storage\logs\laravel.log -Tail 200 | Select-String -Pattern "BEFORE sendFlowMessage|AFTER sendFlowMessage|Skipping duplicate"
```

### Step 3: Look for These Patterns

#### ✅ GOOD (Only 1 execution):
```
[timestamp] 🔵 BEFORE sendFlowMessage
execution_id: exec_abc123
node_id: node_xyz
node_type: textMessage

[timestamp] 🟢 AFTER sendFlowMessage
execution_id: exec_abc123
result_status: true
```

#### ❌ BAD (Duplicate execution):
```
[timestamp] 🔵 BEFORE sendFlowMessage
execution_id: exec_abc123
node_id: node_xyz
node_type: textMessage

[timestamp] 🟢 AFTER sendFlowMessage
execution_id: exec_abc123

[timestamp] 🔵 BEFORE sendFlowMessage  ← DUPLICATE!
execution_id: exec_def456
node_id: node_xyz  ← SAME NODE!
node_type: textMessage
```

---

## 📊 What to Look For

### Scenario A: Same Node Executed Twice
If you see the **same node_id** with **2 different execution_ids**:
- This means the node is being added to the queue twice
- Check for duplicate edges in Flow Builder

### Scenario B: Webhook Called Twice
If you see **2 separate webhook calls** (check message_id):
- WhatsApp is sending the webhook twice
- This is a WhatsApp API issue, not our code

### Scenario C: Different Nodes
If you see **2 different node_ids**:
- You have 2 text message nodes connected to the trigger
- Delete one of them in Flow Builder

---

## 🔧 Quick Fixes Based on Logs

### If Logs Show: "Skipping duplicate edge"
**Cause:** Duplicate edges in flow  
**Fix:** 
1. Open Flow Builder
2. Delete duplicate connection
3. Save flow

### If Logs Show: "Skipping flow - another flow already executed"
**Cause:** Multiple flows matching same trigger  
**Fix:**
1. Check all active flows
2. Ensure only 1 flow has "test" trigger
3. Deactivate others

### If Logs Show: Same execution_id twice
**Cause:** Code is calling sendFlowMessage twice  
**Fix:** Need to investigate code path

---

## 📝 Send Me the Logs

After testing, please share:

1. **The log output** from Step 2 above
2. **Screenshot** of your flow in Flow Builder showing:
   - Trigger node
   - Connected nodes
   - All connections/edges

This will help me identify the exact cause!

---

## 🎯 Alternative: Check Database

```sql
-- Check if message is stored twice
SELECT id, message_id, message, created_at 
FROM chat_messages 
WHERE message LIKE '%test is working%'
ORDER BY created_at DESC 
LIMIT 10;
```

If you see **2 rows** with different `id` but same `message_id`:
- Messages are being stored twice
- This confirms duplicate execution

If you see **2 rows** with different `message_id`:
- WhatsApp is sending 2 actual messages
- Check if sendFlowMessage is being called twice

---

## 🚨 Emergency Stop

If you want to temporarily stop the bot while we debug:

```php
// Add this at line 2880 in WhatsAppWebhookController.php
// BEFORE: $result = $this->sendFlowMessage(...)

if ($nodeType === 'textMessage') {
    whatsapp_log('⛔ EMERGENCY STOP - Skipping text message', 'warning', [
        'node_id' => $node['id'],
        'reason' => 'debugging_duplicate_issue',
    ]);
    return true; // Skip sending
}
```

This will prevent text messages from being sent while we investigate.

---

**Next Steps:**
1. Test with "test" message
2. Check logs
3. Share results with me
4. I'll provide targeted fix based on what we find
