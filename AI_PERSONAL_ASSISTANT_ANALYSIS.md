# 🤖 AI Personal Assistant Bot - Complete Analysis

## 📋 Table of Contents
1. [Overview](#overview)
2. [How It Works](#how-it-works)
3. [Models & Database](#models--database)
4. [Message Trigger Flow](#message-trigger-flow)
5. [Response Generation](#response-generation)
6. [How to Stop/Control It](#how-to-stopcontrol-it)
7. [Configuration](#configuration)
8. [Recommendations](#recommendations)

---

## 🎯 Overview

The **AI Personal Assistant** is a document-based AI chatbot that uses OpenAI's GPT models to provide intelligent responses based on uploaded knowledge files (PDFs, CSVs, TXT, etc.). It can be integrated into WhatsApp bot flows to provide context-aware, intelligent responses.

### Key Features:
- ✅ **Document-based knowledge**: Upload files to create a knowledge base
- ✅ **Two API modes**: OpenAI Assistants API (with vector stores) or Chat Completions API
- ✅ **Conversation persistence**: Maintains thread history for 24 hours
- ✅ **Multi-assistant support**: Create multiple assistants for different use cases
- ✅ **Per-contact AI toggle**: Can be disabled for specific contacts
- ✅ **WhatsApp integration**: Works seamlessly in bot flows

---

## 🏗️ How It Works

### Architecture Diagram:
```
WhatsApp Message
      ↓
Webhook Controller
      ↓
Bot Flow System
      ↓
AI Assistant Node (if present in flow)
      ↓
Check: Is AI disabled for contact? ← AI TOGGLE CHECK
      ↓ (If enabled)
sendFlowPersonalAssistantMessage()
      ↓
personalAssistantResponse() (in Ai trait)
      ↓
Check: Is assistant synced with OpenAI?
      ├─ YES → useOpenAIAssistantsAPI() (with threads & vector stores)
      └─ NO  → Chat Completions API (fallback)
      ↓
Format response (Markdown → WhatsApp)
      ↓
Send to user via WhatsApp
```

---

## 📊 Models & Database

### 1. **PersonalAssistant Model**
**Location:** `app/Models/PersonalAssistant.php`

**Purpose:** Stores AI assistant configurations

**Key Fields:**
```php
- id                        // Primary key
- tenant_id                 // Tenant isolation
- name                      // Assistant name (e.g., "Product Support Bot")
- description               // Optional description
- system_instructions       // Core AI prompt/instructions
- model                     // OpenAI model (gpt-4, gpt-3.5-turbo, etc.)
- temperature               // Creativity (0.0 - 1.0)
- max_tokens                // Max response length
- file_analysis_enabled     // Enable file uploads
- uploaded_files            // JSON array of uploaded files
- processed_content         // Extracted text from files
- is_active                 // Enable/disable assistant
- use_case_tags             // Categories (faq, product, onboarding, etc.)
- openai_assistant_id       // OpenAI Assistant ID (if synced)
- openai_vector_store_id    // OpenAI Vector Store ID (for file search)
- last_synced_at            // Last sync timestamp
```

**Available Models:**
```php
'gpt-3.5-turbo'       => 'GPT-3.5 Turbo'
'gpt-3.5-turbo-16k'   => 'GPT-3.5 Turbo (16K Context)'
'gpt-4'               => 'GPT-4'
'gpt-4-turbo'         => 'GPT-4 Turbo'
'gpt-4o-mini'         => 'GPT-4o Mini (Fast & Cost-effective)'
```

**Use Cases:**
```php
'faq'        => 'FAQs Automation'
'product'    => 'Product Enquiries'
'onboarding' => 'Onboarding & Setup Help'
'csv'        => 'CSV Lookups'
'sop'        => 'Internal SOPs or Team Guides'
'general'    => 'General Purpose'
```

---

### 2. **AiConversation Model**
**Location:** `app/Models/Tenant/AiConversation.php`

**Purpose:** Maintains conversation threads and history

**Key Fields:**
```php
- id                    // Primary key
- tenant_id             // Tenant isolation
- contact_id            // Contact ID
- contact_phone         // Contact phone number
- thread_id             // Internal conversation ID
- system_prompt         // System instructions snapshot
- conversation_data     // JSON: messages + openai_thread_id
- last_activity_at      // Last message timestamp
- expires_at            // Thread expiration (24 hours)
- is_active             // Active status
- message_count         // Total messages in conversation
- total_tokens_used     // Approximate token usage
```

**Conversation Persistence:**
- Threads are kept active for **24 hours** after last activity
- OpenAI thread IDs are stored in `conversation_data['openai_thread_id']`
- Reuses existing threads to maintain context
- Auto-expires inactive conversations

---

### 3. **Contact Model (AI Toggle)**
**Location:** `app/Models/Tenant/Contact.php`

**AI Control Field:**
```php
- ai_disabled  // Boolean: true = AI OFF, false = AI ON
```

**Helper Methods:**
```php
$contact->isAiEnabled()   // Check if AI is enabled
$contact->isAiDisabled()  // Check if AI is disabled
$contact->enableAi()      // Enable AI
$contact->disableAi()     // Disable AI
$contact->toggleAi()      // Toggle AI status
```

---

## 🔄 Message Trigger Flow

### Step-by-Step Process:

#### **1. WhatsApp Message Received**
```
User sends: "What are your business hours?"
      ↓
WhatsAppWebhookController receives webhook
      ↓
Duplicate prevention checks (5 layers)
      ↓
Message stored in database
```

#### **2. Bot Flow Execution**
```
processBotFlow() is called
      ↓
Checks for active flows matching trigger
      ↓
Finds flow with "AI Assistant" node
      ↓
Executes flow nodes sequentially
```

#### **3. AI Assistant Node Triggered**
**Location:** `app/Traits/WhatsApp.php` → `sendFlowAiMessage()`

**Line 2145-2155: AI Disabled Check**
```php
// ✅ CHECK IF AI IS DISABLED FOR THIS CONTACT
if ($contactData && isset($contactData->ai_disabled) && $contactData->ai_disabled) {
    // Return success without sending anything - flow will continue to next node
    return [
        'status' => true,
        'ai_disabled' => true,
        'message' => 'AI disabled for this contact',
        'data' => [],
    ];
}
```

**Line 2157-2168: Mode Selection**
```php
// Prioritize Personal Assistant mode if:
// 1. Mode is explicitly set to 'personal', OR
// 2. A specific assistant is selected (selectedAssistantId exists)
if ($assistantMode === 'personal' || $hasSelectedAssistant) {
    return $this->sendFlowPersonalAssistantMessage($to, $nodeData, $phoneNumberId, $contactData, $context);
}
```

#### **4. Personal Assistant Processing**
**Location:** `app/Traits/WhatsApp.php` → `sendFlowPersonalAssistantMessage()`

**Line 2290-2323: Assistant Validation**
```php
$selectedAssistantId = $nodeData['selectedAssistantId'] ?? null;

if (!$selectedAssistantId) {
    return error message: "AI Assistant not configured"
}

// ✅ SECURITY: Get the SPECIFIC assistant by ID with tenant verification
$assistant = \App\Models\PersonalAssistant::findForTenant($selectedAssistantId, $tenantId);

if (!$assistant) {
    return error message: "AI Assistant not found"
}

if (!$assistant->is_active) {
    return error message: "AI Assistant is currently disabled"
}
```

**Line 2344-2408: Call AI Service**
```php
$userMessage = $context['trigger_message'] ?? 'Hello';

$aiResult = $this->personalAssistantResponse(
    $userMessage,
    $conversationHistory,
    $assistant,
    $contactId,
    $contactPhone,
    $tenantId
);
```

---

## 🧠 Response Generation

### Two API Modes:

#### **Mode 1: OpenAI Assistants API (Preferred)**
**Used when:** `assistant->openai_assistant_id` is set (assistant is synced)

**Location:** `app/Traits/Ai.php` → `useOpenAIAssistantsAPI()`

**Process:**
```
1. Get or create OpenAI thread for contact
   ├─ Check AiConversation table for existing thread
   ├─ Reuse thread if < 24 hours old
   └─ Create new thread if needed

2. Add user message to thread
   POST /threads/{threadId}/messages

3. Run assistant on thread
   POST /threads/{threadId}/runs
   {
     "assistant_id": "asst_xxxxx"
   }

4. Poll for completion (max 30 seconds)
   GET /threads/{threadId}/runs/{runId}
   Wait until status = "completed"

5. Retrieve assistant response
   GET /threads/{threadId}/messages
   Extract latest assistant message

6. Format response (Markdown → WhatsApp)
   **bold** → *bold*
   `code` → ```code```
   ~~strike~~ → ~strike~

7. Send to user
```

**Advantages:**
- ✅ Uses vector stores for file search
- ✅ Better file understanding
- ✅ Automatic thread management
- ✅ More accurate responses

---

#### **Mode 2: Chat Completions API (Fallback)**
**Used when:** Assistant is NOT synced with OpenAI

**Location:** `app/Traits/Ai.php` → `personalAssistantResponse()`

**Process:**
```
1. Build message array
   [
     { role: 'system', content: system_instructions + processed_content },
     { role: 'user', content: 'Previous message 1' },
     { role: 'assistant', content: 'Previous response 1' },
     { role: 'user', content: 'Current message' }
   ]

2. Call OpenAI Chat Completions
   POST /chat/completions
   {
     "model": "gpt-4",
     "messages": [...],
     "temperature": 0.7,
     "max_tokens": 500
   }

3. Format response (Markdown → WhatsApp)

4. Send to user
```

**Advantages:**
- ✅ Simpler implementation
- ✅ Works without syncing
- ✅ Faster for simple queries

**Disadvantages:**
- ❌ No vector search
- ❌ Manual context management
- ❌ Limited file understanding

---

## 🛑 How to Stop/Control It

### **Method 1: Disable AI for Specific Contact (Recommended)**

**Via UI:**
1. Open chat with contact
2. Click "AI ON" badge in header
3. Badge turns red "AI OFF"
4. AI is now disabled for this contact only

**Via API:**
```php
POST /{subdomain}/toggle-contact-ai
{
  "contact_id": 123,
  "chat_id": 456
}
```

**Via Code:**
```php
$contact = Contact::find($contactId);
$contact->disableAi();  // Disable AI
// or
$contact->enableAi();   // Enable AI
```

**Effect:**
- ✅ AI Personal Assistant will NOT respond
- ✅ E-commerce AI will NOT respond
- ✅ Flow continues to next node (no error)
- ✅ Saves OpenAI API costs!

---

### **Method 2: Deactivate Entire Assistant**

**Via Livewire Component:**
```
Navigate to: AI → Personal Assistants
Toggle: "Is Active" switch to OFF
```

**Via Code:**
```php
$assistant = PersonalAssistant::find($assistantId);
$assistant->update(['is_active' => false]);
```

**Effect:**
- ❌ Assistant will NOT respond to ANY contact
- ✅ Returns error: "AI Assistant is currently disabled"

---

### **Method 3: Remove AI Node from Flow**

**Via Flow Builder:**
1. Open flow editor
2. Delete "AI Assistant" node
3. Save flow

**Effect:**
- ✅ AI will never be triggered for this flow
- ✅ Flow continues without AI

---

### **Method 4: Disable OpenAI Globally**

**Via Settings:**
```
Settings → WhatsApp → AI Settings
Toggle: "Enable OpenAI in Chat" to OFF
```

**Effect:**
- ❌ ALL AI features disabled tenant-wide
- ❌ Personal Assistant, E-commerce AI, Custom AI all stop

---

### **Method 5: Remove OpenAI API Key**

**Via Settings:**
```
Settings → WhatsApp → AI Settings
Clear: "OpenAI Secret Key"
```

**Effect:**
- ❌ AI cannot make API calls
- ✅ Returns error: "OpenAI API key not configured"

---

## ⚙️ Configuration

### **Creating a Personal Assistant**

**Via Livewire Component:**
```
Location: app/Livewire/Tenant/AI/PersonalAssistantManager.php
Route: /{subdomain}/ai/personal-assistants
```

**Steps:**
1. Click "Create New Assistant"
2. Fill in:
   - **Name**: "Customer Support Bot"
   - **Description**: "Handles customer inquiries"
   - **Model**: gpt-4o-mini (recommended for cost)
   - **Temperature**: 0.7 (balanced creativity)
   - **Max Tokens**: 500 (response length)
   - **System Instructions**: Your AI prompt
   - **Use Cases**: Select relevant tags
3. Upload knowledge files (optional):
   - PDF, TXT, CSV, MD, JSON
   - Max 5MB per file
   - Content is extracted and added to context
4. Click "Save"

**Syncing with OpenAI (Optional but Recommended):**
1. Click "Sync with OpenAI" button
2. Creates OpenAI Assistant with vector store
3. Uploads files to vector store
4. Enables file search capability

---

### **Adding to Bot Flow**

**Via Flow Builder:**
1. Open flow editor
2. Add "AI Assistant" node
3. Configure node:
   - **Assistant Mode**: "Personal Assistant"
   - **Selected Assistant**: Choose from dropdown
4. Connect to trigger node
5. Save flow

**Node Configuration:**
```json
{
  "type": "aiAssistant",
  "data": {
    "assistantMode": "personal",
    "selectedAssistantId": 123,
    "output": [...]
  }
}
```

---

## 💡 Recommendations

### **1. Cost Optimization**

#### **Use AI Toggle Feature:**
```php
// Disable AI for contacts who are:
- Negotiating prices (need human touch)
- Just browsing (not ready to buy)
- Spamming or testing
- VIP customers (prefer human agents)
```

**Savings Example:**
- 1000 messages/day × $0.002/message = $2/day
- With 50% AI disabled = $1/day saved
- **$365/year saved!**

---

#### **Choose Cost-Effective Models:**
```php
// Recommended models by use case:

// Simple FAQs, quick responses:
'gpt-4o-mini' => Fastest, cheapest ($0.00015/1K tokens)

// Product support, detailed answers:
'gpt-3.5-turbo' => Balanced ($0.0015/1K tokens)

// Complex reasoning, document analysis:
'gpt-4-turbo' => Most capable ($0.01/1K tokens)
```

---

### **2. Performance Optimization**

#### **Sync Assistants with OpenAI:**
```
Benefits:
✅ 3x faster responses (uses threads)
✅ Better file search (vector stores)
✅ Automatic context management
✅ More accurate answers

How:
1. Click "Sync with OpenAI" in assistant settings
2. Wait for sync to complete
3. Test with a message
```

---

#### **Limit File Sizes:**
```php
// Current limits:
Max file size: 5MB
Max content per file: 50,000 chars
Max total content: 250,000 chars (5 files × 50K)

// Recommendations:
- Keep files under 2MB for faster processing
- Use plain text formats (TXT, CSV, MD) when possible
- Avoid large PDFs (extract text first)
```

---

### **3. Conversation Management**

#### **Thread Expiration:**
```php
// Current: 24 hours
// Recommendation: Adjust based on use case

// For customer support (long conversations):
'expires_at' => now()->addHours(48)

// For quick FAQs (short conversations):
'expires_at' => now()->addHours(2)

// Modify in: app/Traits/Ai.php line 482
```

---

#### **Cleanup Expired Conversations:**
```php
// Run daily via cron:
php artisan schedule:run

// Or manually:
\App\Models\Tenant\AiConversation::cleanupExpired();
```

---

### **4. Security Best Practices**

#### **Tenant Isolation:**
```php
// ✅ ALWAYS use findForTenant() in webhooks:
$assistant = PersonalAssistant::findForTenant($assistantId, $tenantId);

// ❌ NEVER use find() directly:
$assistant = PersonalAssistant::find($assistantId); // Cross-tenant risk!
```

---

#### **API Key Protection:**
```php
// ✅ Store in database (encrypted)
// ✅ Use tenant-specific keys
// ✅ Validate before each API call
// ❌ Never hardcode in code
// ❌ Never expose in frontend
```

---

### **5. Monitoring & Debugging**

#### **Log Files:**
```
storage/logs/aipersonaldebug.log  // Personal Assistant detailed logs
storage/logs/laravel.log          // General application logs
storage/logs/whatsapp.log         // WhatsApp webhook logs
```

#### **Enable Debug Logging:**
```php
// In app/Traits/Ai.php and app/Traits/WhatsApp.php
// Logs are already comprehensive, check:
- Assistant selection
- API calls
- Response times
- Errors and exceptions
```

---

### **6. Error Handling**

#### **Graceful Fallbacks:**
```php
// Current implementation:
1. Try OpenAI Assistants API
2. If fails → Try Chat Completions API
3. If fails → Send error message to user

// Recommendation: Add retry logic
try {
    $response = $this->personalAssistantResponse(...);
} catch (\Exception $e) {
    // Retry once after 2 seconds
    sleep(2);
    $response = $this->personalAssistantResponse(...);
}
```

---

### **7. User Experience**

#### **Response Formatting:**
```php
// Current: Markdown → WhatsApp conversion
**bold** → *bold*
`code` → ```code```
~~strike~~ → ~strike~

// Recommendation: Add more formatting
- Lists (numbered/bulleted)
- Links (clickable)
- Emojis (for friendliness)
```

---

#### **Response Time:**
```php
// Current: 1-5 seconds (Assistants API)
//          0.5-2 seconds (Chat Completions)

// Recommendation: Show typing indicator
// (WhatsApp doesn't support this natively, but you can:)
1. Send "Typing..." message
2. Delete it after AI responds
3. Send actual response
```

---

## 📊 Summary

### **What You Have:**
✅ Advanced AI Personal Assistant system  
✅ Two API modes (Assistants API + Chat Completions)  
✅ Document-based knowledge (file uploads)  
✅ Conversation persistence (24-hour threads)  
✅ Multi-assistant support  
✅ Per-contact AI toggle (cost savings!)  
✅ WhatsApp flow integration  
✅ Comprehensive logging  
✅ Tenant isolation  
✅ Graceful error handling  

### **How to Control It:**
1. **Per-Contact:** Toggle AI ON/OFF badge in chat header
2. **Per-Assistant:** Deactivate in assistant settings
3. **Per-Flow:** Remove AI node from flow
4. **Globally:** Disable OpenAI in settings

### **Best Practices:**
1. Use `gpt-4o-mini` for cost efficiency
2. Sync assistants with OpenAI for better performance
3. Enable AI toggle for cost-sensitive contacts
4. Monitor logs regularly
5. Clean up expired conversations
6. Keep files small and optimized

---

**Need Help?**
- Check logs: `storage/logs/aipersonaldebug.log`
- Review code: `app/Traits/Ai.php` (line 158-785)
- Test manually: Use Livewire component at `/{subdomain}/ai/personal-assistants`

---

**Last Updated:** December 11, 2025  
**Version:** 1.0  
**Status:** ✅ Production Ready
