<?php

namespace App\Traits;

use App\Exceptions\WhatsAppException;
use App\Models\PersonalAssistant;
use LLPhant\Chat\OpenAIChat;
use LLPhant\OpenAIConfig;
use OpenAI;

trait Ai
{
    /**
     * Store AI settings to avoid multiple database calls
     */
    protected $aiSettings;

    /**
     * Load all AI settings in a single batch call
     */
    protected function loadAiSettings()
    {
        if (!isset($this->aiSettings)) {
            $this->aiSettings = get_batch_settings([
                'whats-mark.chat_model',
            ]);
        }

        return $this->aiSettings;
    }

    public function listModel(): array
    {
        try {
            $openAiKey = $this->getOpenAiKey();
            $openAi = new OpenAI;
            $client = $openAi->client($openAiKey);
            $response = $client->models()->list();

            if ($response === null || !is_object($response)) {
                throw new \RuntimeException('Invalid response format from OpenAI API.');
            }

            if (property_exists($response, 'error')) {
                save_tenant_setting('whats-mark', 'is_open_ai_key_verify', false);

                return [
                    'status' => false,
                    'message' => $response->error->message ?? 'Unknown error occurred.',
                ];
            }

            save_tenant_setting('whats-mark', 'is_open_ai_key_verify', true);

            return [
                'status' => true,
                'data' => 'Model list fetched successfully.',
            ];
        } catch (\Throwable $th) {
            save_tenant_setting('whats-mark', 'is_open_ai_key_verify', false);
            whatsapp_log('OpenAI Model List Error', 'error', [
                'error' => $th->getMessage(),
            ], $th);

            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
            throw new WhatsAppException($th->getMessage());
        }
    }

    /**
     * Sends a request to the OpenAI API to get a response based on provided data.
     *
     * @param  array  $data  The data to be sent to the OpenAI API.
     * @return array Contains status and message of the response.
     */
    public function aiResponse(array $data)
    {
        try {
            $config = new OpenAIConfig;
            $config->apiKey = $this->getOpenAiKey();

            // Load settings and use from batch
            $this->loadAiSettings();
            $config->model = $this->aiSettings['whats-mark.chat_model'] ?? 'gpt-3.5-turbo';

            $chat = new OpenAIChat($config);
            $message = $data['input_msg'];
            $menuItem = $data['menu'];
            $submenuItem = $data['submenu'];
            $status = true;

            $prompt = match ($menuItem) {
                'Simplify Language' => 'You will be provided with statements, and your task is to convert them to Simplify Language. but don\'t change inputed language.',
                'Fix Spelling & Grammar' => 'You will be provided with statements, and your task is to convert them to standard Language. but don\'t change inputed language.',
                'Translate' => 'You will be provided with a sentence, and your task is to translate it into ' . $submenuItem . ', only give translated sentence',
                'Change Tone' => 'You will be provided with statements, and your task is to change tone into ' . $submenuItem . '. but don\'t change inputed language.',
                'Custom Prompt' => $submenuItem,
            };

            $messages = [
                ['role' => 'system', 'content' => $prompt],
                ['role' => 'user', 'content' => $message],
            ];

            // Send the structured messages to OpenAI's chat API
            $response = $chat->generateChat($messages);
        } catch (\Throwable $th) {
            whatsapp_log('OpenAI Chat Generation Error', 'error', [
                'error' => $th->getMessage(),
            ], $th);

            $status = false;
            $message = $th->getMessage();
        }

        return [
            'status' => $status,
            'message' => $status ? $response : $message,
        ];
    }

    /**
     * Retrieves the OpenAI API key from the options.
     *
     * @param int|null $tenantId Optional tenant ID. If not provided, uses current tenant context.
     * @return string|null The OpenAI API key.
     */
    public function getOpenAiKey($tenantId = null)
    {
        // If tenant ID is explicitly provided, use it
        if ($tenantId !== null) {
            return get_tenant_setting_by_tenant_id('whats-mark', 'openai_secret_key', null, $tenantId);
        }

        // Try to get tenant ID from wa_tenant_id property if available (WhatsApp trait)
        if (property_exists($this, 'wa_tenant_id') && !empty($this->wa_tenant_id)) {
            return get_tenant_setting_by_tenant_id('whats-mark', 'openai_secret_key', null, $this->wa_tenant_id);
        }

        // Fallback to current tenant context
        return get_tenant_setting_from_db('whats-mark', 'openai_secret_key');
    }

    /**
     * Transcribe voice/audio message to text using OpenAI Whisper
     *
     * @param string $audioUrl URL to the audio file from WhatsApp
     * @param int|null $tenantId Optional tenant ID for API key retrieval
     * @return array Contains status, text transcription, and error if any
     */
    public function transcribeVoiceMessage(string $audioUrl, $tenantId = null): array
    {
        $logFile = storage_path('logs/voice_transcription.log');
        $timestamp = now()->format('Y-m-d H:i:s');

        $this->logToFile($logFile, "[$timestamp] Voice Transcription Started");
        $this->logToFile($logFile, "Audio URL: $audioUrl");

        try {
            // Get OpenAI API key
            $openAiKey = $this->getOpenAiKey($tenantId);

            if (empty($openAiKey)) {
                $this->logToFile($logFile, "ERROR: OpenAI API key not found");
                return [
                    'status' => false,
                    'message' => 'OpenAI API key not configured',
                    'text' => null
                ];
            }

            // Handle both local file paths and URLs
            $deleteAfter = false;
            if (file_exists($audioUrl)) {
                // It's a local file path
                $this->logToFile($logFile, "Using local file: $audioUrl");
                $tempFile = $audioUrl;
            } else {
                // It's a URL - download it
                $this->logToFile($logFile, "Downloading audio file from URL...");
                $audioContent = file_get_contents($audioUrl);

                if ($audioContent === false) {
                    $this->logToFile($logFile, "ERROR: Failed to download audio file");
                    return [
                        'status' => false,
                        'message' => 'Failed to download audio file',
                        'text' => null
                    ];
                }

                // Save to temporary file
                $tempFile = sys_get_temp_dir() . '/voice_' . uniqid() . '.ogg';
                file_put_contents($tempFile, $audioContent);
                $deleteAfter = true;
                $this->logToFile($logFile, "Audio saved to: $tempFile");
            }

            // Create OpenAI client
            $openAi = new \OpenAI;
            $client = $openAi->client($openAiKey);

            // Call Whisper API for transcription
            $this->logToFile($logFile, "Sending to OpenAI Whisper API...");
            $response = $client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($tempFile, 'rb'),
                'response_format' => 'json',
            ]);

            // Clean up temporary file only if we created it
            if ($deleteAfter) {
                @unlink($tempFile);
            }

            // Extract transcription text
            $transcription = $response->text ?? '';

            $this->logToFile($logFile, "SUCCESS: Transcription completed");
            $this->logToFile($logFile, "Transcribed Text: $transcription");

            return [
                'status' => true,
                'text' => $transcription,
                'message' => 'Voice transcribed successfully'
            ];

        } catch (\Throwable $e) {
            $this->logToFile($logFile, "ERROR: " . $e->getMessage());
            whatsapp_log('Voice Transcription Error', 'error', [
                'audio_url' => $audioUrl,
                'error' => $e->getMessage(),
            ], $e);

            return [
                'status' => false,
                'message' => $e->getMessage(),
                'text' => null
            ];
        }
    }

    /**
     * Send message to personal assistant with context
     *
     * @param string $message User message
     * @param array $conversationHistory Previous messages for context
     * @param PersonalAssistant|null $assistant The assistant to use
     * @param int|null $contactId Contact ID for thread persistence
     * @param string|null $contactPhone Contact phone for thread persistence
     * @param int|null $tenantId Tenant ID for thread persistence
     * @return array Contains status and response
     */
    public function personalAssistantResponse(
        string $message,
        array $conversationHistory = [],
        ?PersonalAssistant $assistant = null,
        ?int $contactId = null,
        ?string $contactPhone = null,
        ?int $tenantId = null,
        ?string $imageUrl = null
    ): array {
        $logFile = storage_path('logs/aipersonaldebug.log');
        $timestamp = now()->format('Y-m-d H:i:s');

        // Get tenant ID for API key retrieval
        $tenantId = null;
        if (property_exists($this, 'wa_tenant_id') && !empty($this->wa_tenant_id)) {
            $tenantId = $this->wa_tenant_id;
        } elseif (function_exists('tenant_id')) {
            $tenantId = tenant_id();
        }

        // Log request start
        $this->logToFile($logFile, "================================================================================");
        $this->logToFile($logFile, "[$timestamp] PERSONAL AI ASSISTANT - REQUEST START");
        $this->logToFile($logFile, "================================================================================");
        $this->logToFile($logFile, "USER MESSAGE: " . $message);
        $this->logToFile($logFile, "CONVERSATION HISTORY COUNT: " . count($conversationHistory));
        $this->logToFile($logFile, "TENANT ID: " . ($tenantId ?? 'N/A'));

        try {
            // Use provided assistant or fall back to getForCurrentTenant for backward compatibility
            if (!$assistant) {
                // SECURITY FIX: In webhook contexts (WhatsApp), Laravel tenant context may not be set
                // Use wa_tenant_id property if available to ensure correct tenant isolation
                if (property_exists($this, 'wa_tenant_id') && !empty($this->wa_tenant_id)) {
                    $this->logToFile($logFile, "FALLBACK: Using wa_tenant_id ({$this->wa_tenant_id}) to get assistant");
                    $assistant = PersonalAssistant::getForTenant($this->wa_tenant_id);
                } else {
                    $this->logToFile($logFile, "FALLBACK: Using getCurrentTenant() to get assistant");
                    $assistant = PersonalAssistant::getForCurrentTenant();
                }
            }

            if (!$assistant) {
                $this->logToFile($logFile, "ERROR: No personal assistant configured for this tenant");
                $this->logToFile($logFile, "RESPONSE: No personal assistant configured");
                $this->logToFile($logFile, "================================================================================\n");

                return [
                    'status' => false,
                    'message' => 'No personal assistant configured for this tenant.',
                ];
            }

            // Log assistant info
            $this->logToFile($logFile, "ASSISTANT FOUND:");
            $this->logToFile($logFile, "  - ID: " . $assistant->id);
            $this->logToFile($logFile, "  - Name: " . $assistant->name);
            $this->logToFile($logFile, "  - Model: " . $assistant->model);
            $this->logToFile($logFile, "  - OpenAI Assistant ID: " . ($assistant->openai_assistant_id ?? 'NOT SYNCED'));
            $this->logToFile($logFile, "  - Temperature: " . $assistant->temperature);
            $this->logToFile($logFile, "  - Max Tokens: " . $assistant->max_tokens);
            $this->logToFile($logFile, "  - Is Active: " . ($assistant->is_active ? 'Yes' : 'No'));
            $this->logToFile($logFile, "  - Files Loaded: " . $assistant->getFileCount());

            if (!$assistant->is_active) {
                $this->logToFile($logFile, "ERROR: Personal assistant is disabled");
                $this->logToFile($logFile, "RESPONSE: Assistant currently disabled");
                $this->logToFile($logFile, "================================================================================\n");

                return [
                    'status' => false,
                    'message' => 'Personal assistant is currently disabled.',
                ];
            }

            // Check if assistant is synced with OpenAI - use Assistants API if available
            if ($assistant->openai_assistant_id) {
                $this->logToFile($logFile, "USING OPENAI ASSISTANTS API (Real Assistant)");
                return $this->useOpenAIAssistantsAPI($assistant, $message, $conversationHistory, $logFile, $timestamp, $contactId, $contactPhone, $tenantId, $imageUrl);
            }

            // Fallback to Chat Completions API if not synced
            $this->logToFile($logFile, "USING CHAT COMPLETIONS API (Fallback - Assistant not synced)");

            // Get and validate API key with tenant ID
            $openAiKey = $this->getOpenAiKey($tenantId);
            if (empty($openAiKey)) {
                $this->logToFile($logFile, "ERROR: OpenAI API key is not configured");
                $this->logToFile($logFile, "  - Tenant ID: " . ($tenantId ?? 'N/A'));
                $this->logToFile($logFile, "  - Setting Key: whats-mark.openai_secret_key");
                $this->logToFile($logFile, "  - Current Tenant Context: " . (tenant_id() ?? 'N/A'));
                $this->logToFile($logFile, "  - Has wa_tenant_id: " . (property_exists($this, 'wa_tenant_id') ? ($this->wa_tenant_id ?? 'NULL') : 'NO PROPERTY'));

                return [
                    'status' => false,
                    'message' => 'OpenAI API key is not configured. Please configure it in settings.',
                ];
            }

            $config = new OpenAIConfig;
            $config->apiKey = $openAiKey;
            $config->model = $assistant->model;

            $config->model = $assistant->model;

            // Auto-upgrade removed based on user request. 
            // User must ensure their selected model supports vision if sending images.

            $chat = new OpenAIChat($config);

            // Build message array with system context
            $messages = [];

            // Add system instructions with knowledge base
            $systemContext = $assistant->getFullSystemContext();

            // ✅ FEATURE: Inject Button Instructions
            if ($assistant->allow_buttons) {
                $systemContext .= "\n\nYou can attach up to 3 interactive buttons to your response using the format: {{BUTTON:Label}}. labels must be short (max 20 chars). Use buttons for choices, confirmations, or next steps. Example: {{BUTTON:Yes}} {{BUTTON:No}}";
            }

            // ✅ FEATURE: Business Scope Enforcement
            $systemContext .= "\n\n🎯 STAY IN YOUR BUSINESS SCOPE:\nYou are an AI assistant for THIS SPECIFIC BUSINESS ONLY. Your knowledge base defines what services this business provides.\n\n⚠️ CRITICAL RULES:\n1. ONLY answer questions related to YOUR business/services\n2. If user asks about something OUTSIDE your scope → Politely decline and redirect\n3. Don't provide generic advice for unrelated topics\n4. Stay focused on what THIS business does\n\n📝 EXAMPLES:\n\nIF Business = Visa Consultancy:\n- User: 'How to get Canada visa?' → Answer (IN SCOPE)\n- User: 'How to register a company?' → 'I specialize in visa consultation. For company registration, you may need a business consultant. Can I help you with any visa-related queries?' (OUT OF SCOPE)\n\nIF Business = Restaurant:\n- User: 'What's on your menu?' → Answer (IN SCOPE)  \n- User: 'How to get visa?' → 'We're a restaurant. For visa services, please contact a visa consultant. Would you like to see our menu?' (OUT OF SCOPE)\n\n💡 HOW TO DECLINE:\n- Be polite and professional\n- Briefly explain what you DO help with\n- Redirect to your actual services\n- Don't make up information outside your expertise";

            // ✅ FEATURE: Smart Language Matching
            $systemContext .= "\n\n🌍 LANGUAGE MATCHING - CRITICAL RULE:\nALWAYS respond in the EXACT SAME language and script format the user is using.\n\n⚠️ HOW TO DETECT:\n1. Look at the user's PREVIOUS messages in conversation history\n2. Identify their language pattern (Roman Urdu, Urdu Script, English, etc.)\n3. MAINTAIN that same format in ALL your responses\n4. Even if current message format is different (like voice transcription), use the format from their TEXT messages\n\n📝 EXAMPLES:\n\n**Scenario 1: User writes in Roman Urdu**\nUser (text): 'assalam o alaikum'\nUser (text): 'main visa ke bare main puchna chahta hun'\nUser (voice): [Transcribed to any format]\n→ You respond: 'Walaikum assalam! Main aapki madad kar sakta hun...' (ROMAN URDU)\n\n**Scenario 2: User writes in Urdu Script**  \nUser (text): 'السلام علیکم'\nUser (text): 'میں ویزا کے بارے میں پوچھنا چاہتا ہوں'\nUser (voice): [Transcribed to any format]\n→ You respond: 'وعلیکم السلام! میں آپ کی مدد...' (URDU SCRIPT)\n\n**Scenario 3: User writes in English**\nUser (text): 'Hello'\nUser (text): 'I want to ask about visa'\nUser (voice): [Transcribed to any format]  \n→ You respond: 'Sure! I can help you...' (ENGLISH)\n\n🚨 CRITICAL:\n- If conversation history has Roman Urdu → Use ONLY Roman Urdu\n- If conversation history has Urdu Script → Use ONLY Urdu Script\n- NEVER switch formats mid-conversation\n- Voice transcription doesn't change the language format you should use";

            // ✅ FEATURE: Human Handoff - Smart & Universal Protocol (Conditional)
            if ($assistant->allow_handoff ?? true) {
                $systemContext .= "\n\n🚨 INTELLIGENT HANDOFF PROTOCOL\nAnalyze each situation intelligently. START your response with {{HANDOFF}} when:\n\n1. USER SENDS MEDIA FOR VERIFICATION:\n   - Images, screenshots, documents, receipts\n   - ANY file requiring human review/verification\n   → If they mention sending proof: Ask for it first\n   → When they SEND the file: {{HANDOFF}} immediately\n\n2. EXPLICIT HUMAN REQUEST:\n   - User asks for: human, agent, support, staff, manager, representative\n   - User says: \"talk to someone\", \"speak to person\", \"I want help\"\n   → {{HANDOFF}} immediately\n\n3. BEYOND YOUR CAPABILITIES:\n   - Account access/permissions you don't have\n   - Actions requiring admin/staff intervention\n   - Database changes, refunds, cancellations\n   - Anything you CANNOT do yourself\n   → Be honest: {{HANDOFF}}\n\n4. USER BEHAVIOR ISSUES:\n   - User is abusive, threatening, or harassing\n   - Repeating same question 3+ times (ignoring your answer)\n   - Spam or malicious behavior\n   → {{HANDOFF}} for human moderation\n\n5. HIGH-STAKES OR SENSITIVE:\n   - Legal matters, complaints, disputes\n   - Billing errors, unauthorized charges\n   - Privacy concerns, data requests\n   - VIP/urgent customer issues\n   → {{HANDOFF}} for careful handling\n\n6. WHEN UNCERTAIN:\n   - You're not confident in your answer\n   - Information might be outdated\n   - Situation is ambiguous or complex\n   → Better safe than sorry: {{HANDOFF}}\n\n💡 SMART FLOW EXAMPLE:\n- User: 'I paid yesterday' → You: 'Please send payment proof/screenshot'\n- User: [Sends image] → You: {{HANDOFF}}\n\n⚠️ REMEMBER: Don't handoff unnecessarily. Answer what you CAN, handoff what you CANNOT.";
            }


            $messages[] = ['role' => 'system', 'content' => $systemContext];

            $this->logToFile($logFile, "SYSTEM CONTEXT:");
            $this->logToFile($logFile, "  - System Instructions Length: " . strlen($assistant->system_instructions) . " chars");
            $this->logToFile($logFile, "  - Processed Content Length: " . strlen($assistant->processed_content ?? '') . " chars");
            $this->logToFile($logFile, "  - Total Context Length: " . strlen($systemContext) . " chars");

            // Add conversation history if provided
            if (!empty($conversationHistory)) {
                $this->logToFile($logFile, "CONVERSATION HISTORY:");
                foreach ($conversationHistory as $index => $historyMessage) {
                    if (isset($historyMessage['role']) && isset($historyMessage['content'])) {
                        $messages[] = [
                            'role' => $historyMessage['role'],
                            'content' => $historyMessage['content']
                        ];
                        $this->logToFile($logFile, "  [$index] " . strtoupper($historyMessage['role']) . ": " . substr($historyMessage['content'], 0, 100) . "...");
                    }
                }
            }

            // Add current user message (with image if provided)
            if ($imageUrl) {
                // ✅ ENHANCEMENT: Context-aware prompt - Forces action over description
                $aiUserMessage = $message === '[Image]' ? "User sent an image. Analyze it and take the appropriate action based on our conversation context and your instructions. Do not just describe it unless necessary." : $message;

                $this->logToFile($logFile, "ADDING IMAGE TO REQUEST: " . substr($imageUrl, 0, 50) . "...");
                $messages[] = [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $aiUserMessage
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageUrl
                            ]
                        ]
                    ]
                ];
            } else {
                $messages[] = ['role' => 'user', 'content' => $message . ($assistant->allow_buttons ? "\n\n(System Note: You can use {{BUTTON:Label}} format to add up to 3 interactive buttons. Use them for choices/actions. IMPORTANT: If using buttons, keep text BRIEF (under 800 chars) to meet WhatsApp limits.)" : "")];
            }

            // Configure chat parameters
            $config->temperature = $assistant->temperature;
            $config->maxTokens = $assistant->max_tokens;

            // Log what's being sent to OpenAI
            $this->logToFile($logFile, "SENDING TO OPENAI:");
            $this->logToFile($logFile, "  - Model: " . $assistant->model);
            $this->logToFile($logFile, "  - Temperature: " . $assistant->temperature);
            $this->logToFile($logFile, "  - Max Tokens: " . $assistant->max_tokens);
            $this->logToFile($logFile, "  - Total Messages: " . count($messages));
            $this->logToFile($logFile, "  - API Call Time: " . now()->format('H:i:s'));
            $this->logToFile($logFile, "");
            $this->logToFile($logFile, "MESSAGES BEING SENT TO OPENAI:");
            foreach ($messages as $idx => $msg) {
                $role = $msg['role'] ?? 'unknown';
                $content = $msg['content'] ?? '';
                $preview = strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
                $this->logToFile($logFile, "  Message " . ($idx + 1) . " [{$role}]: " . $preview);
            }

            // Generate response
            $apiStartTime = microtime(true);
            $response = $chat->generateChat($messages);
            $apiEndTime = microtime(true);
            $apiDuration = round(($apiEndTime - $apiStartTime) * 1000, 2);

            $this->logToFile($logFile, "");
            $this->logToFile($logFile, "OPENAI RESPONSE RECEIVED:");
            $this->logToFile($logFile, "  - Response Time: " . $apiDuration . " ms");
            $this->logToFile($logFile, "  - Response Length: " . strlen($response) . " chars");
            $this->logToFile($logFile, "  - Response Preview: " . substr($response, 0, 200) . "...");
            $this->logToFile($logFile, "");
            $this->logToFile($logFile, "FULL AI RESPONSE:");
            $this->logToFile($logFile, "---");
            $this->logToFile($logFile, $response);
            $this->logToFile($logFile, "---");

            // Convert markdown formatting to WhatsApp formatting
            $formattedResponse = $this->convertMarkdownToWhatsApp($response);

            $result = [
                'status' => true,
                'message' => $formattedResponse,
                'assistant_name' => $assistant->name,
                'model_used' => $assistant->model,
                'tokens_used' => $assistant->max_tokens, // Approximate
            ];

            $this->logToFile($logFile, "FINAL RESPONSE TO USER:");
            $this->logToFile($logFile, "  - Status: SUCCESS");
            $this->logToFile($logFile, "  - Assistant: " . $assistant->name);
            $this->logToFile($logFile, "  - Model: " . $assistant->model);
            $this->logToFile($logFile, "  - Message: " . $response);
            $this->logToFile($logFile, "[$timestamp] PERSONAL AI ASSISTANT - REQUEST END (SUCCESS)");
            $this->logToFile($logFile, "================================================================================\n");

            return $result;

        } catch (\Throwable $th) {
            $this->logToFile($logFile, "EXCEPTION OCCURRED:");
            $this->logToFile($logFile, "  - Error: " . $th->getMessage());
            $this->logToFile($logFile, "  - File: " . $th->getFile());
            $this->logToFile($logFile, "  - Line: " . $th->getLine());
            $this->logToFile($logFile, "  - Trace:");
            $this->logToFile($logFile, $th->getTraceAsString());
            $this->logToFile($logFile, "");
            $this->logToFile($logFile, "FINAL RESPONSE TO USER:");
            $this->logToFile($logFile, "  - Status: ERROR");
            $this->logToFile($logFile, "  - Message: Assistant temporarily unavailable: " . $th->getMessage());
            $this->logToFile($logFile, "[$timestamp] PERSONAL AI ASSISTANT - REQUEST END (ERROR)");
            $this->logToFile($logFile, "================================================================================\n");

            whatsapp_log('Personal Assistant Error', 'error', [
                'error' => $th->getMessage(),
                'message' => $message,
            ], $th);

            return [
                'status' => false,
                'message' => 'Assistant temporarily unavailable: ' . $th->getMessage(),
            ];
        }
    }

    /**
     * Use OpenAI Assistants API with threads
     */
    protected function useOpenAIAssistantsAPI(
        PersonalAssistant $assistant,
        string $message,
        array $conversationHistory,
        $logFile,
        $timestamp,
        ?int $contactId = null,
        ?string $contactPhone = null,
        ?int $tenantId = null,
        ?string $imageUrl = null
    ): array {
        try {
            // Get tenant ID for API key retrieval
            $tenantId = null;
            if (property_exists($this, 'wa_tenant_id') && !empty($this->wa_tenant_id)) {
                $tenantId = $this->wa_tenant_id;
            } elseif (function_exists('tenant_id')) {
                $tenantId = tenant_id();
            }

            $apiKey = $this->getOpenAiKey($tenantId);

            if (empty($apiKey)) {
                $this->logToFile($logFile, "ERROR: OpenAI API key is not configured");
                $this->logToFile($logFile, "  - Tenant ID Used: " . ($tenantId ?? 'N/A'));
                $this->logToFile($logFile, "  - Current Tenant Context: " . (tenant_id() ?? 'N/A'));
                $this->logToFile($logFile, "  - Setting Key: whats-mark.openai_secret_key");
                $this->logToFile($logFile, "  - Has wa_tenant_id: " . (property_exists($this, 'wa_tenant_id') ? ($this->wa_tenant_id ?? 'NULL') : 'NO PROPERTY'));

                throw new \Exception('OpenAI API key is not configured. Please configure it in settings.');
            }

            $baseUrl = 'https://api.openai.com/v1';
            $assistantId = $assistant->openai_assistant_id;

            $this->logToFile($logFile, "OPENAI ASSISTANTS API CALL:");
            $this->logToFile($logFile, "  - Assistant ID: " . $assistantId);
            $this->logToFile($logFile, "  - User Message: " . $message);
            $this->logToFile($logFile, "  - Message Length: " . strlen($message) . " chars");
            $this->logToFile($logFile, "  - Conversation History: " . count($conversationHistory) . " messages");
            $this->logToFile($logFile, "  - Contact ID: " . ($contactId ?? 'N/A'));
            $this->logToFile($logFile, "  - Contact Phone: " . ($contactPhone ?? 'N/A'));
            if ($imageUrl) {
                $this->logToFile($logFile, "  - Has Image: Yes");
            }

            // Step 1: Get or create OpenAI thread for this contact
            $threadId = null;
            $aiConversation = null;

            if ($contactId && $tenantId) {
                // Try to get existing conversation with OpenAI thread_id stored in conversation_data
                $aiConversation = \App\Models\Tenant\AiConversation::where('tenant_id', $tenantId)
                    ->where('contact_id', $contactId)
                    ->where('is_active', true)
                    ->where('last_activity_at', '>', now()->subHours(24)) // Use same thread for 24 hours
                    ->first();

                if ($aiConversation) {
                    $conversationData = $aiConversation->conversation_data ?? [];
                    $threadId = $conversationData['openai_thread_id'] ?? null;

                    if ($threadId) {
                        $this->logToFile($logFile, "  - Reusing existing OpenAI Thread ID: " . $threadId);
                        $this->logToFile($logFile, "  - Conversation ID: " . $aiConversation->id);
                    }
                }
            }

            // If no existing thread, create a new one
            if (!$threadId) {
                $this->logToFile($logFile, "  - Creating new OpenAI thread...");
                $threadResponse = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2',
                ])->post("{$baseUrl}/threads", []);

                if (!$threadResponse->successful()) {
                    throw new \Exception('Failed to create thread: ' . $threadResponse->body());
                }

                $threadData = $threadResponse->json();
                $threadId = $threadData['id'] ?? null;

                if (!$threadId) {
                    throw new \Exception('Thread ID not returned from OpenAI');
                }

                $this->logToFile($logFile, "  - New OpenAI Thread ID: " . $threadId);

                // Store the OpenAI thread_id in the conversation record
                if ($contactId && $tenantId) {
                    if (!$aiConversation) {
                        // Create new conversation record
                        $conversationData = [
                            'messages' => [],
                            'openai_thread_id' => $threadId,
                        ];

                        $aiConversation = \App\Models\Tenant\AiConversation::create([
                            'tenant_id' => $tenantId,
                            'contact_id' => $contactId,
                            'contact_phone' => $contactPhone ?? '',
                            'thread_id' => 'conv_' . uniqid(), // Internal conversation ID
                            'system_prompt' => $assistant->getFullSystemContext(),
                            'conversation_data' => $conversationData,
                            'last_activity_at' => now(),
                            'expires_at' => now()->addHours(24),
                            'is_active' => true,
                            'message_count' => 0,
                            'total_tokens_used' => 0,
                        ]);
                    } else {
                        // Update existing conversation with OpenAI thread_id
                        $conversationData = $aiConversation->conversation_data ?? [];
                        $conversationData['openai_thread_id'] = $threadId;

                        $aiConversation->update([
                            'conversation_data' => $conversationData,
                            'last_activity_at' => now(),
                            'expires_at' => now()->addHours(24),
                        ]);
                    }
                    $this->logToFile($logFile, "  - Stored OpenAI Thread ID in conversation record");
                }
            }

            // Step 2: Add conversation history to thread (only if this is a new thread)
            // If we're reusing an existing thread, it already has all the messages
            if (!$aiConversation && !empty($conversationHistory)) {
                $this->logToFile($logFile, "ADDING CONVERSATION HISTORY TO NEW THREAD:");
                foreach ($conversationHistory as $historyMessage) {
                    if (isset($historyMessage['role']) && isset($historyMessage['content']) && $historyMessage['role'] !== 'system') {
                        $role = $historyMessage['role'] === 'assistant' ? 'assistant' : 'user';
                        \Illuminate\Support\Facades\Http::withHeaders([
                            'Authorization' => 'Bearer ' . $apiKey,
                            'Content-Type' => 'application/json',
                            'OpenAI-Beta' => 'assistants=v2',
                        ])->post("{$baseUrl}/threads/{$threadId}/messages", [
                                    'role' => $role,
                                    'content' => $historyMessage['content'],
                                ]);
                    }
                }
            } elseif ($aiConversation) {
                $this->logToFile($logFile, "SKIPPING CONVERSATION HISTORY - Using existing thread with full context");
            }

            // Step 3: Add current user message to thread
            $userMessageContent = $message . ($assistant->allow_buttons ? "\n\n(System Note: You can use {{BUTTON:Label}} format to add up to 3 interactive buttons. Use them for choices/actions. IMPORTANT: If using buttons, keep text BRIEF (under 800 chars) to meet WhatsApp limits.)" : "");
            if ($imageUrl) {
                // ✅ ENHANCEMENT: Context-aware prompt - Forces action over description
                $aiUserMessage = $message === '[Image]' ? "User sent an image. Analyze it and take the appropriate action based on our conversation context and your instructions. Do not just describe it unless necessary." : $message;

                // For Assistants API, we should use File ID instead of Base64 URL for reliability
                $fileId = $this->uploadImageToOpenAI($imageUrl, $apiKey);

                if ($fileId) {
                    $this->logToFile($logFile, "  - Uploaded image to OpenAI with File ID: " . $fileId);
                    $userMessageContent = [
                        [
                            'type' => 'text',
                            'text' => $aiUserMessage . ($assistant->allow_buttons ? "\n\n(System Note: You can use {{BUTTON:Label}} format to add up to 3 interactive buttons. Use them for choices/actions. IMPORTANT: If using buttons, keep text BRIEF (under 800 chars) to meet WhatsApp limits.)" : "")
                        ],
                        [
                            'type' => 'image_file',
                            'image_file' => [
                                'file_id' => $fileId
                            ]
                        ]
                    ];
                } else {
                    // Fallback to image_url with base64 (might fail but worth a try if upload failed)
                    $this->logToFile($logFile, "  - Image upload failed, falling back to Base64 URL");
                    $userMessageContent = [
                        [
                            'type' => 'text',
                            'text' => $aiUserMessage
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageUrl
                            ]
                        ]
                    ];
                }
            }

            \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])->post("{$baseUrl}/threads/{$threadId}/messages", [
                        'role' => 'user',
                        'content' => $userMessageContent,
                    ]);

            $this->logToFile($logFile, "RUNNING ASSISTANT ON THREAD...");
            $runRequestData = [
                'assistant_id' => $assistantId,
                'additional_instructions' => ($assistant->allow_buttons ? "\nYou can attach up to 3 interactive buttons to your response using the format: {{BUTTON:Label}}. labels must be short (max 20 chars). Use buttons for choices, confirmations, or next steps. Example: {{BUTTON:Buy Basic}} {{BUTTON:More Info}}" : "") . "\n\n🎯 SCOPE: ONLY answer about YOUR business (in knowledge base). Off-topic → Politely decline.\n\n🌍 LANGUAGE: Check conversation HISTORY to detect user's language format (Roman Urdu/Urdu Script/English). Use SAME format consistently, even for voice messages." . ($assistant->allow_handoff ?? true ? "\n\n🚨 HANDOFF: {{HANDOFF}} when: user sends file, asks for human, beyond capability, abusive, high-stakes, uncertain." : ""),
            ];

            // ✅ AUTO-UPGRADE REMOVED: Respecting user's model setting
            // if ($imageUrl) { ... }

            // Note: max_completion_tokens is not a valid parameter for Assistants API
            // The max_tokens setting is handled by the model itself

            $runResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])->post("{$baseUrl}/threads/{$threadId}/runs", $runRequestData);

            if (!$runResponse->successful()) {
                throw new \Exception('Failed to run assistant: ' . $runResponse->body());
            }

            $runData = $runResponse->json();
            $runId = $runData['id'] ?? null;

            if (!$runId) {
                throw new \Exception('Run ID not returned from OpenAI');
            }

            $this->logToFile($logFile, "  - Run ID: " . $runId);

            // Step 5: Poll for completion
            $maxAttempts = 30;
            $attempt = 0;
            $status = 'queued';

            while ($status !== 'completed' && $status !== 'failed' && $attempt < $maxAttempts) {
                sleep(1);
                $attempt++;

                $statusResponse = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'OpenAI-Beta' => 'assistants=v2',
                ])->get("{$baseUrl}/threads/{$threadId}/runs/{$runId}");

                if (!$statusResponse->successful()) {
                    throw new \Exception('Failed to check run status: ' . $statusResponse->body());
                }

                $statusData = $statusResponse->json();
                $status = $statusData['status'] ?? 'unknown';

                $this->logToFile($logFile, "  - Run Status (Attempt {$attempt}): " . $status);
            }

            if ($status !== 'completed') {
                throw new \Exception("Run did not complete. Status: {$status}");
            }

            // Step 6: Retrieve messages from thread
            $messagesResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'OpenAI-Beta' => 'assistants=v2',
            ])->get("{$baseUrl}/threads/{$threadId}/messages");

            if (!$messagesResponse->successful()) {
                throw new \Exception('Failed to retrieve messages: ' . $messagesResponse->body());
            }

            $messagesData = $messagesResponse->json();
            $messages = $messagesData['data'] ?? [];

            // Get the latest assistant message
            $response = '';
            foreach ($messages as $msg) {
                if (isset($msg['role']) && $msg['role'] === 'assistant') {
                    if (isset($msg['content'][0]['text']['value'])) {
                        $response = $msg['content'][0]['text']['value'];
                        break;
                    }
                }
            }

            if (empty($response)) {
                throw new \Exception('No response from assistant');
            }

            $this->logToFile($logFile, "");
            $this->logToFile($logFile, "OPENAI ASSISTANTS API RESPONSE RECEIVED:");
            $this->logToFile($logFile, "  - Thread ID: " . $threadId);
            $this->logToFile($logFile, "  - Run ID: " . $runId);
            $this->logToFile($logFile, "  - Response Length: " . strlen($response) . " chars");
            $this->logToFile($logFile, "  - Response Preview: " . substr($response, 0, 200) . "...");
            $this->logToFile($logFile, "");
            $this->logToFile($logFile, "FULL AI RESPONSE:");
            $this->logToFile($logFile, "---");
            $this->logToFile($logFile, $response);
            $this->logToFile($logFile, "---");

            // Convert markdown formatting to WhatsApp formatting
            $formattedResponse = $this->convertMarkdownToWhatsApp($response);

            // ✅ FEATURE: Human Handoff Detection
            $handoff = false;
            if (strpos($formattedResponse, '{{HANDOFF}}') !== false) {
                $handoff = true;
                $formattedResponse = str_replace('{{HANDOFF}}', '', $formattedResponse);
                $formattedResponse = trim($formattedResponse);
                $this->logToFile($logFile, "  - HANDOFF DETECTED: Flagged for human agent.");
            }

            // ✅ FEATURE: Parse Buttons
            $parsedResponse = $this->parseAiResponseButtons($formattedResponse);
            $finalText = $parsedResponse['text'];
            $buttons = $parsedResponse['buttons'];

            // Update conversation record with last activity
            if ($aiConversation) {
                $aiConversation->update([
                    'last_activity_at' => now(),
                ]);
            }

            $result = [
                'status' => true,
                'message' => $finalText,
                'buttons' => $buttons, // New field
                'handoff' => $handoff, // New field
                'assistant_name' => $assistant->name,
                'model_used' => $assistant->model,
                'tokens_used' => $assistant->max_tokens, // Approximate
            ];

            $this->logToFile($logFile, "FINAL RESPONSE TO USER:");
            $this->logToFile($logFile, "  - Status: SUCCESS");
            $this->logToFile($logFile, "  - Assistant: " . $assistant->name);
            $this->logToFile($logFile, "  - Model: " . $assistant->model);
            $this->logToFile($logFile, "  - Message: " . $response);
            $this->logToFile($logFile, "[$timestamp] PERSONAL AI ASSISTANT - REQUEST END (SUCCESS - ASSISTANTS API)");
            $this->logToFile($logFile, "================================================================================\n");

            return $result;

        } catch (\Exception $e) {
            $this->logToFile($logFile, "OPENAI ASSISTANTS API ERROR:");
            $this->logToFile($logFile, "  - Error: " . $e->getMessage());
            $this->logToFile($logFile, "[$timestamp] PERSONAL AI ASSISTANT - REQUEST END (ERROR - ASSISTANTS API)");
            $this->logToFile($logFile, "================================================================================\n");

            // Fallback to Chat Completions API
            $this->logToFile($logFile, "FALLING BACK TO CHAT COMPLETIONS API...");

            $config = new OpenAIConfig;
            $config->apiKey = $this->getOpenAiKey();
            $config->model = $assistant->model;
            // ✅ AUTO-UPGRADE REMOVED: Respecting user's model setting
            $config->temperature = $assistant->temperature;
            $config->maxTokens = $assistant->max_tokens;

            $chat = new OpenAIChat($config);

            $messages = [];
            $messages = [];
            $systemContext = $assistant->getFullSystemContext();

            // ✅ FEATURE: Inject Button Instructions for Fallback
            $systemContext .= "\n\nYou can attach up to 3 interactive buttons to your response using the format: {{BUTTON:Label}}.";

            $messages[] = ['role' => 'system', 'content' => $systemContext];

            if (!empty($conversationHistory)) {
                foreach ($conversationHistory as $historyMessage) {
                    if (isset($historyMessage['role']) && isset($historyMessage['content'])) {
                        $messages[] = [
                            'role' => $historyMessage['role'],
                            'content' => $historyMessage['content']
                        ];
                    }
                }
            }

            if ($imageUrl) {
                // ✅ ENHANCEMENT: Context-aware prompt - Forces action over description
                $aiUserMessage = $message === '[Image]' ? "User sent an image. Analyze it and take the appropriate action based on our conversation context and your instructions. Do not just describe it unless necessary." : $message;

                $messages[] = [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $aiUserMessage
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageUrl
                            ]
                        ]
                    ]
                ];
            } else {
                $messages[] = ['role' => 'user', 'content' => $message];
            }

            try {
                $response = $chat->generateChat($messages);

                return [
                    'status' => true,
                    'message' => $response,
                    'assistant_name' => $assistant->name,
                    'model_used' => $assistant->model,
                    'tokens_used' => $assistant->max_tokens,
                ];
            } catch (\Exception $fallbackError) {
                return [
                    'status' => false,
                    'message' => 'Assistant temporarily unavailable: ' . $fallbackError->getMessage(),
                ];
            }
        }
    }

    /**
     * Log to dedicated file
     */
    private function logToFile($filePath, $message)
    {
        try {
            // Ensure directory exists
            $directory = dirname($filePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($filePath, $message . "\n", FILE_APPEND);
        } catch (\Exception $e) {
            // Silently fail if logging fails
        }
    }

    /**
     * Get personal assistant info for current tenant
     */
    public function getPersonalAssistantInfo(): ?array
    {
        $assistant = PersonalAssistant::getForCurrentTenant();

        if (!$assistant) {
            return null;
        }

        return [
            'id' => $assistant->id,
            'name' => $assistant->name,
            'description' => $assistant->description,
            'model' => $assistant->model,
            'is_active' => $assistant->is_active,
            'has_files' => $assistant->hasUploadedFiles(),
            'file_count' => $assistant->getFileCount(),
            'use_cases' => $assistant->getUseCaseBadges(),
        ];
    }

    /**
     * Check if personal assistant is available and configured
     */
    public function hasPersonalAssistant(): bool
    {
        $assistant = PersonalAssistant::getForCurrentTenant();
        return $assistant && $assistant->is_active;
    }

    /**
     * Convert markdown formatting to WhatsApp formatting
     * 
     * Converts:
     * - **text** (markdown bold) → *text* (WhatsApp bold)
     * - `text` (markdown code) → ```text``` (WhatsApp monospace)
     * - ~~text~~ (markdown strikethrough) → ~text~ (WhatsApp strikethrough)
     * 
     * @param string $text Text with markdown formatting
     * @return string Text with WhatsApp formatting
     */
    protected function convertMarkdownToWhatsApp(string $text): string
    {
        // Convert markdown bold **text** to WhatsApp bold *text*
        $text = preg_replace('/\*\*(.+?)\*\*/', '*$1*', $text);

        // Convert markdown code `text` to WhatsApp monospace ```text```
        $text = preg_replace('/`([^`]+)`/', '```$1```', $text);

        // Convert markdown strikethrough ~~text~~ to WhatsApp strikethrough ~text~
        $text = preg_replace('/~~(.+?)~~/', '~$1~', $text);

        return $text;
    }

    /**
     * Upload a Base64 image to OpenAI Files API
     * Returns the File ID or null
     */
    protected function uploadImageToOpenAI($base64Image, $apiKey)
    {
        try {
            // Check if valid base64 string with header
            if (strpos($base64Image, 'data:') !== 0) {
                return null;
            }

            // Parse base64
            $parts = explode(',', $base64Image);
            if (count($parts) < 2) {
                return null;
            }

            $content = base64_decode($parts[1]);
            $tempPath = sys_get_temp_dir() . '/ai_upload_' . uniqid() . '.jpg';
            file_put_contents($tempPath, $content);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->attach('file', fopen($tempPath, 'r'), 'image.jpg')
                ->post('https://api.openai.com/v1/files', [
                    'purpose' => 'vision'
                ]);

            @unlink($tempPath); // Clean up

            if ($response->successful()) {
                $data = $response->json();
                return $data['id'] ?? null;
            } else {
                whatsapp_log('OpenAI Image Upload Failed', 'error', ['payload' => $response->body()]);
            }
        } catch (\Exception $e) {
            whatsapp_log('OpenAI Image Upload Exception', 'error', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Parse {{BUTTON:Label}} tags from AI response
     */
    protected function parseAiResponseButtons($text)
    {
        $buttons = [];
        $pattern = '/\{\{BUTTON:(.*?)\}\}/';

        if (preg_match_all($pattern, $text, $matches)) {
            // Get first 3 buttons (WhatsApp limit for reply buttons)
            $buttons = array_slice($matches[1], 0, 3);

            // Remove tags from text, keep optional surrounding whitespace clean up
            $text = preg_replace($pattern, '', $text);
            $text = trim($text);

            // WhatsApp Interactive Message Body Limit is 1024 chars
            // If text is too long, truncate it to ensure delivery
            if (strlen($text) > 1000) {
                $text = mb_substr($text, 0, 997) . '...';
            }
        }

        return ['text' => $text, 'buttons' => $buttons];
    }
}
