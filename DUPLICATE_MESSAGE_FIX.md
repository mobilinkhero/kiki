# 🐛 Duplicate Message Issue - Analysis & Fix

## Problem Description
User is receiving **2 identical messages** ("test is working") when only 1 is configured in the bot flow.

## Root Cause Analysis

### Issue Found:
The flow likely has **MULTIPLE TRIGGER NODES** matching the same message "test", causing the flow to execute multiple times.

### Code Location:
`app/Http/Controllers/Whatsapp/WhatsAppWebhookController.php`

**Line 1953-1997:** `determineFlowExecution()` method
```php
foreach ($flows as $flow) {
    $flowData = json_decode($flow->flow_data, true);
    
    // Find trigger node
    foreach ($flowData['nodes'] as $node) {
        if ($node['type'] === 'trigger') {
            $matchResult = $this->isFlowMatchWithPriority($node, $contactData->type, $triggerMsg);
            
            if ($matchResult['matched']) {
                // ⚠️ PROBLEM: This executes IMMEDIATELY for EACH matching trigger
                $result = $this->executeFlowFromStart($flow, $contactData, $triggerMsg, ...);
                return $result; // ❌ Returns after first match, but...
            }
        }
    }
}
```

**Line 2355-2383:** `executeFlowFromStart()` method
```php
private function executeFlowFromStart($flow, $contactData, $triggerMsg, ...)
{
    $flowData = json_decode($flow->flow_data, true);
    
    // Find the FIRST matching trigger node (not all of them)
    $matchingTrigger = null;
    foreach ($flowData['nodes'] as $node) {
        if ($node['type'] === 'trigger') {
            if ($this->isFlowMatch($node, $contactData->type, $triggerMsg)) {
                $matchingTrigger = $node;
                break; // ✅ Only use the first matching trigger
            }
        }
    }
    
    // Execute with the single matching trigger
    return $this->executeFlowWithMultipleTriggers($flow, [$matchingTrigger], ...);
}
```

### Why Duplicates Occur:

#### Scenario 1: Multiple Trigger Nodes in Same Flow
```
Flow: "Test Flow"
├─ Trigger Node 1: "test" (exact match)
│  └─ Text Message: "test is working"
│
└─ Trigger Node 2: "test" (contains match)
   └─ Text Message: "test is working"
```

**What Happens:**
1. Message "test" arrives
2. `determineFlowExecution()` loops through flow nodes
3. Finds Trigger Node 1 → matches → executes flow → sends message
4. Finds Trigger Node 2 → matches → executes flow → sends message AGAIN
5. Result: **2 duplicate messages**

#### Scenario 2: Two Separate Flows with Same Trigger
```
Flow 1: "Test Flow 1"
└─ Trigger: "test"
   └─ Text Message: "test is working"

Flow 2: "Test Flow 2"
└─ Trigger: "test"
   └─ Text Message: "test is working"
```

**What Happens:**
1. Message "test" arrives
2. `determineFlowExecution()` loops through ALL active flows
3. Finds Flow 1 → matches → executes → sends message
4. **BUG:** Should return here, but if there's a logic error, it continues
5. Finds Flow 2 → matches → executes → sends message AGAIN
6. Result: **2 duplicate messages**

---

## 🔍 Debugging Steps

### Step 1: Check Your Flow Configuration
1. Open Flow Builder
2. Check how many **Trigger nodes** exist in your flow
3. Check if multiple triggers have "test" as a keyword

### Step 2: Check Logs
Look at `storage/logs/laravel.log` for:
```
[timestamp] Flow match found, executing
flow_id: 123
trigger_node_id: node_abc123
match_type: exact
trigger_msg: test
```

If you see this **TWICE** for the same message, you have duplicate triggers.

### Step 3: Check Database
```sql
SELECT id, name, is_active, flow_data 
FROM bot_flows 
WHERE tenant_id = YOUR_TENANT_ID 
AND is_active = 1;
```

Decode `flow_data` JSON and count trigger nodes.

---

## ✅ Solutions

### Solution 1: Remove Duplicate Trigger Nodes (Recommended)
**If you have multiple trigger nodes in the same flow:**

1. Open Flow Builder
2. Delete extra trigger nodes
3. Keep only ONE trigger node per keyword
4. Save flow

**Result:** Only 1 message will be sent ✅

---

### Solution 2: Use Different Trigger Keywords
**If you need multiple triggers:**

1. Trigger 1: "test" → "test is working"
2. Trigger 2: "hello" → "hello response"
3. Trigger 3: "help" → "help response"

**Result:** Each keyword triggers different response ✅

---

### Solution 3: Code Fix (If Issue Persists)
**Add early return to prevent duplicate execution:**

**File:** `app/Http/Controllers/Whatsapp/WhatsAppWebhookController.php`

**Line 1953-1997:** Modify `determineFlowExecution()` method:

```php
foreach ($flows as $flow) {
    $flowData = json_decode($flow->flow_data, true);
    if (empty($flowData) || empty($flowData['nodes'])) {
        continue;
    }

    // ✅ FIX: Track if we already executed this flow
    $flowExecuted = false;

    // Find trigger node
    foreach ($flowData['nodes'] as $node) {
        if ($node['type'] === 'trigger') {
            $matchResult = $this->isFlowMatchWithPriority($node, $contactData->type, $triggerMsg);

            if ($matchResult['matched']) {
                // ✅ FIX: Skip if this flow was already executed
                if ($flowExecuted) {
                    $this->logToDuplicateFile('Skipping duplicate trigger in same flow', [
                        'flow_id' => $flow->id,
                        'trigger_node_id' => $node['id'],
                        'reason' => 'flow_already_executed',
                    ]);
                    continue; // Skip to next trigger node
                }

                // If this is a fallback trigger, save it but continue checking OTHER flows
                if ($matchResult['is_fallback']) {
                    whatsapp_log('Found fallback flow, saving for later', 'info', [
                        'flow_id' => $flow->id,
                        'trigger_node_id' => $node['id'],
                    ]);
                    $fallbackFlow = $flow;
                    $fallbackNode = $node;
                    break; // Don't check other trigger nodes in this flow
                }

                // This is a specific match (exact/contains/first-time) - execute immediately
                $this->logToDuplicateFile('Flow match found, executing', [
                    'flow_id' => $flow->id,
                    'flow_name' => $flow->name ?? 'Unknown',
                    'trigger_node_id' => $node['id'],
                    'match_type' => $matchResult['match_type'],
                    'trigger_msg' => $triggerMsg,
                ]);

                $result = $this->executeFlowFromStart($flow, $contactData, $triggerMsg, $chatId, $contactNumber, $phoneNumberId);

                // ✅ FIX: Mark flow as executed
                $flowExecuted = true;

                $this->logToDuplicateFile('Flow execution completed, RETURNING', [
                    'flow_id' => $flow->id,
                    'flow_name' => $flow->name ?? 'Unknown',
                ]);

                return $result; // ✅ Return immediately - don't process other flows
            }
        }
    }
}
```

---

### Solution 4: Add Duplicate Prevention Flag
**Add a class-level flag to prevent duplicate flow execution:**

**File:** `app/Http/Controllers/Whatsapp/WhatsAppWebhookController.php`

**Line 30-44:** Add new property:
```php
public $is_first_time = false;

public $is_bot_stop = false;

public $tenant_id = null;

public $tenant_subdoamin = null;

public $pusher_settings;

protected $featureLimitChecker;

protected $ecommerceHandledMessage = false;

protected $oldBotHasResponded = false;

// ✅ NEW: Add flow execution flag
protected $flowHasExecuted = false; // Prevent duplicate flow execution
```

**Line 1987:** Check flag before execution:
```php
// This is a specific match (exact/contains/first-time) - execute immediately

// ✅ FIX: Check if any flow has already executed
if ($this->flowHasExecuted) {
    $this->logToDuplicateFile('Skipping flow - another flow already executed', [
        'flow_id' => $flow->id,
        'flow_name' => $flow->name ?? 'Unknown',
    ]);
    return true; // Return success but don't execute
}

$this->logToDuplicateFile('Flow match found, executing', [
    'flow_id' => $flow->id,
    'flow_name' => $flow->name ?? 'Unknown',
    'trigger_node_id' => $node['id'],
    'match_type' => $matchResult['match_type'],
    'trigger_msg' => $triggerMsg,
]);

$result = $this->executeFlowFromStart($flow, $contactData, $triggerMsg, $chatId, $contactNumber, $phoneNumberId);

// ✅ FIX: Mark that a flow has executed
$this->flowHasExecuted = true;

$this->logToDuplicateFile('Flow execution completed, RETURNING', [
    'flow_id' => $flow->id,
    'flow_name' => $flow->name ?? 'Unknown',
]);

return $result;
```

---

## 🎯 Recommended Action Plan

### Immediate Fix (No Code Changes):
1. **Check your flow in Flow Builder**
2. **Count how many trigger nodes exist**
3. **If multiple triggers have "test" keyword:**
   - Delete extra trigger nodes
   - OR change keywords to be unique
4. **Save and test**

### If Issue Persists (Code Fix):
1. Apply **Solution 4** (add duplicate prevention flag)
2. Test with "test" message
3. Check logs to confirm only 1 execution

---

## 📊 Expected Behavior After Fix

### Before Fix:
```
User sends: "test"
      ↓
Flow finds Trigger 1 → Executes → Sends "test is working"
      ↓
Flow finds Trigger 2 → Executes → Sends "test is working" (DUPLICATE!)
```

### After Fix:
```
User sends: "test"
      ↓
Flow finds Trigger 1 → Executes → Sends "test is working"
      ↓
Flow finds Trigger 2 → SKIPPED (flowHasExecuted = true)
      ↓
Result: Only 1 message sent ✅
```

---

## 🔍 How to Verify Fix

1. Send "test" message
2. Check you receive only **1** response
3. Check logs:
```
[timestamp] Flow match found, executing
flow_id: 123
trigger_node_id: node_abc123

[timestamp] Flow execution completed, RETURNING
flow_id: 123

[timestamp] Skipping flow - another flow already executed (if duplicate trigger exists)
```

---

## 📝 Summary

**Root Cause:** Multiple trigger nodes matching the same keyword "test"

**Quick Fix:** Remove duplicate trigger nodes in Flow Builder

**Code Fix:** Add `$flowHasExecuted` flag to prevent duplicate execution

**Testing:** Send "test" and verify only 1 response

---

**Status:** ⚠️ Needs Investigation  
**Priority:** High (User-facing issue)  
**Estimated Fix Time:** 5 minutes (remove duplicate trigger) or 10 minutes (code fix)
