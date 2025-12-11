<?php

namespace App\Services;

use App\Models\PersonalAssistant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AI Vision Service
 * 
 * Analyzes images using GPT-4 Vision API
 * Use cases:
 * - Product identification
 * - Receipt/invoice parsing
 * - Screenshot troubleshooting
 * - Damage assessment
 * - Visual search
 */
class AiVisionService
{
    protected $tenantId;
    protected $apiKey;

    public function __construct(?int $tenantId = null)
    {
        $this->tenantId = $tenantId ?? tenant_id();
        $this->apiKey = get_tenant_setting_by_tenant_id('whats-mark', 'openai_secret_key', null, $this->tenantId);
    }

    /**
     * Analyze image with AI Vision
     * 
     * @param string $imageUrl URL of the image to analyze
     * @param string $userMessage User's message accompanying the image
     * @param PersonalAssistant|null $assistant Optional assistant for context
     * @param string $analysisType Type of analysis (auto|product|receipt|troubleshoot|damage|general)
     * @return array Analysis result
     */
    public function analyzeImage(
        string $imageUrl,
        string $userMessage = '',
        ?PersonalAssistant $assistant = null,
        string $analysisType = 'auto'
    ): array {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'OpenAI API key not configured',
                    'analysis' => null,
                ];
            }

            // Build analysis prompt based on type
            $prompt = $this->buildVisionPrompt($userMessage, $analysisType, $assistant);

            // Log vision request
            whatsapp_log('AI Vision Analysis Started', 'info', [
                'image_url' => $imageUrl,
                'user_message' => substr($userMessage, 0, 100),
                'analysis_type' => $analysisType,
                'tenant_id' => $this->tenantId,
            ], null, $this->tenantId);

            // Call GPT-4 Vision API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o', // GPT-4o has vision capabilities
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    [
                                        'type' => 'text',
                                        'text' => $prompt,
                                    ],
                                    [
                                        'type' => 'image_url',
                                        'image_url' => [
                                            'url' => $imageUrl,
                                            'detail' => 'high', // high|low|auto
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'max_tokens' => 1000,
                        'temperature' => 0.3, // Lower for more factual analysis
                    ]);

            if (!$response->successful()) {
                throw new \Exception('Vision API request failed: ' . $response->body());
            }

            $data = $response->json();
            $analysis = $data['choices'][0]['message']['content'] ?? '';

            // Parse structured data if available
            $structuredData = $this->parseVisionResponse($analysis, $analysisType);

            // Log successful analysis
            whatsapp_log('AI Vision Analysis Completed', 'info', [
                'analysis_length' => strlen($analysis),
                'tokens_used' => $data['usage']['total_tokens'] ?? 0,
                'analysis_type' => $analysisType,
                'tenant_id' => $this->tenantId,
            ], null, $this->tenantId);

            return [
                'success' => true,
                'analysis' => $analysis,
                'structured_data' => $structuredData,
                'tokens_used' => $data['usage']['total_tokens'] ?? 0,
                'model' => 'gpt-4o',
            ];

        } catch (\Exception $e) {
            Log::error('AI Vision Analysis Failed', [
                'error' => $e->getMessage(),
                'image_url' => $imageUrl,
                'tenant_id' => $this->tenantId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'analysis' => null,
            ];
        }
    }

    /**
     * Build vision analysis prompt based on type
     */
    protected function buildVisionPrompt(string $userMessage, string $analysisType, ?PersonalAssistant $assistant): string
    {
        // Get assistant context if available
        $assistantContext = '';
        if ($assistant) {
            $assistantContext = "\n\nBusiness Context:\n" . $assistant->system_instructions;
            if ($assistant->processed_content) {
                $assistantContext .= "\n\nKnowledge Base:\n" . substr($assistant->processed_content, 0, 2000);
            }
        }

        // Auto-detect analysis type from user message
        if ($analysisType === 'auto') {
            $analysisType = $this->detectAnalysisType($userMessage);
        }

        $basePrompt = "Analyze this image and provide a helpful response.";

        switch ($analysisType) {
            case 'product':
                $basePrompt = <<<PROMPT
You are a product identification expert. Analyze this image and:

1. **Identify the product(s)** shown in the image
2. **Describe key features** (color, size, brand, condition)
3. **Suggest similar products** if available in our catalog
4. **Provide pricing estimate** if possible
5. **Answer the user's question** about the product

User's message: "$userMessage"

Format your response in a friendly, helpful way suitable for WhatsApp chat.
$assistantContext
PROMPT;
                break;

            case 'receipt':
                $basePrompt = <<<PROMPT
You are a receipt/invoice analyzer. Extract information from this image:

1. **Business/Store name**
2. **Date and time**
3. **Items purchased** (with quantities and prices)
4. **Total amount**
5. **Payment method** (if visible)
6. **Order/Transaction number** (if visible)

User's message: "$userMessage"

Provide the information in a clear, structured format. If the user is asking about a specific item or issue, address that directly.
$assistantContext
PROMPT;
                break;

            case 'troubleshoot':
                $basePrompt = <<<PROMPT
You are a technical troubleshooting expert. Analyze this screenshot/image to help solve the user's issue:

1. **Identify the problem** shown in the image
2. **Explain what's wrong** in simple terms
3. **Provide step-by-step solution**
4. **Suggest preventive measures**

User's message: "$userMessage"

Be clear, concise, and helpful. Use simple language.
$assistantContext
PROMPT;
                break;

            case 'damage':
                $basePrompt = <<<PROMPT
You are a damage assessment expert. Analyze this image to assess product condition:

1. **Describe the damage** visible in the image
2. **Assess severity** (minor/moderate/severe)
3. **Determine if returnable/refundable**
4. **Suggest next steps** for the customer

User's message: "$userMessage"

Be empathetic and professional. Focus on helping the customer.
$assistantContext
PROMPT;
                break;

            case 'general':
            default:
                $basePrompt = <<<PROMPT
Analyze this image and provide a helpful response based on what you see.

User's message: "$userMessage"

Describe what you see in the image and answer the user's question or provide relevant information.
Be conversational and helpful.
$assistantContext
PROMPT;
                break;
        }

        return $basePrompt;
    }

    /**
     * Detect analysis type from user message
     */
    protected function detectAnalysisType(string $message): string
    {
        $messageLower = strtolower($message);

        // Product identification
        if (
            str_contains($messageLower, 'what is this') ||
            str_contains($messageLower, 'identify') ||
            str_contains($messageLower, 'what product') ||
            str_contains($messageLower, 'similar to this')
        ) {
            return 'product';
        }

        // Receipt/invoice
        if (
            str_contains($messageLower, 'receipt') ||
            str_contains($messageLower, 'invoice') ||
            str_contains($messageLower, 'bill') ||
            str_contains($messageLower, 'order number')
        ) {
            return 'receipt';
        }

        // Damage assessment
        if (
            str_contains($messageLower, 'damage') ||
            str_contains($messageLower, 'broken') ||
            str_contains($messageLower, 'defect') ||
            str_contains($messageLower, 'wrong item') ||
            str_contains($messageLower, 'refund')
        ) {
            return 'damage';
        }

        // Troubleshooting
        if (
            str_contains($messageLower, 'error') ||
            str_contains($messageLower, 'not working') ||
            str_contains($messageLower, 'problem') ||
            str_contains($messageLower, 'issue') ||
            str_contains($messageLower, 'help')
        ) {
            return 'troubleshoot';
        }

        return 'general';
    }

    /**
     * Parse structured data from vision response
     */
    protected function parseVisionResponse(string $analysis, string $analysisType): ?array
    {
        // Try to extract JSON if present
        if (preg_match('/\{[^}]+\}/s', $analysis, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        // Extract key information based on type
        $structured = [
            'type' => $analysisType,
            'summary' => substr($analysis, 0, 200),
        ];

        switch ($analysisType) {
            case 'product':
                // Extract product name, price, etc.
                if (preg_match('/product[:\s]+([^\n]+)/i', $analysis, $matches)) {
                    $structured['product_name'] = trim($matches[1]);
                }
                if (preg_match('/price[:\s]+([^\n]+)/i', $analysis, $matches)) {
                    $structured['price'] = trim($matches[1]);
                }
                break;

            case 'receipt':
                // Extract total, date, etc.
                if (preg_match('/total[:\s]+([^\n]+)/i', $analysis, $matches)) {
                    $structured['total'] = trim($matches[1]);
                }
                if (preg_match('/date[:\s]+([^\n]+)/i', $analysis, $matches)) {
                    $structured['date'] = trim($matches[1]);
                }
                break;
        }

        return $structured;
    }

    /**
     * Analyze multiple images in sequence
     */
    public function analyzeMultipleImages(
        array $imageUrls,
        string $userMessage = '',
        ?PersonalAssistant $assistant = null
    ): array {
        $results = [];

        foreach ($imageUrls as $index => $imageUrl) {
            $result = $this->analyzeImage($imageUrl, $userMessage, $assistant);
            $results[] = [
                'image_index' => $index + 1,
                'image_url' => $imageUrl,
                'result' => $result,
            ];

            // Small delay to avoid rate limiting
            if ($index < count($imageUrls) - 1) {
                usleep(500000); // 0.5 seconds
            }
        }

        return $results;
    }

    /**
     * Compare two images
     */
    public function compareImages(
        string $imageUrl1,
        string $imageUrl2,
        string $comparisonType = 'difference'
    ): array {
        try {
            $prompt = match ($comparisonType) {
                'difference' => 'Compare these two images and describe the differences you see.',
                'similarity' => 'Compare these two images and describe their similarities.',
                'quality' => 'Compare the quality of these two images. Which is better and why?',
                default => 'Compare these two images.',
            };

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o',
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    ['type' => 'text', 'text' => $prompt],
                                    ['type' => 'image_url', 'image_url' => ['url' => $imageUrl1]],
                                    ['type' => 'image_url', 'image_url' => ['url' => $imageUrl2]],
                                ],
                            ],
                        ],
                        'max_tokens' => 800,
                    ]);

            $data = $response->json();
            $comparison = $data['choices'][0]['message']['content'] ?? '';

            return [
                'success' => true,
                'comparison' => $comparison,
                'tokens_used' => $data['usage']['total_tokens'] ?? 0,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
