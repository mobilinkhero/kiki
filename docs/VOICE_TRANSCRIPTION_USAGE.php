<?php

/**
 * HOW TO USE VOICE TRANSCRIPTION WITH OPENAI WHISPER
 * ===================================================
 * 
 * This feature automatically transcribes voice messages from WhatsApp
 * using your existing OpenAI API key (same one used for ChatGPT).
 * 
 * USAGE EXAMPLE:
 * --------------
 * 
 * In your WhatsApp webhook handler, when you receive an audio message:
 * 
 * ```php
 * // 1. Detect if message is audio/voice
 * if ($message->type === 'audio' || $message->type === 'voice') {
 *     
 *     // 2. Get the audio URL from WhatsApp
 *     $audioUrl = $message->audio->link; // or however your structure stores it
 *     
 *     // 3. Call the transcription method
 *     $result = $this->transcribeVoiceMessage($audioUrl);
 *     
 *     // 4. Check if transcription was successful
 *     if ($result['status']) {
 *         $transcribedText = $result['text'];
 *         
 *         // 5. Now use the text just like a regular message!
 *         // Feed it to AI bot
 *         $aiResponse = $this->personalAssistantResponse($transcribedText, ...);
 *         
 *         // Or save to database
 *         $chatMessage->message = $transcribedText;
 *         $chatMessage->transcription = $transcribedText;
 *         $chatMessage->save();
 *         
 *         // Or show to agent
 *         // Agent sees: "User said (voice): Hello, I need help with my order"
 *         
 *     } else {
 *         // Transcription failed
 *         whatsapp_log('Voice transcription failed', 'error', $result);
 *     }
 * }
 * ```
 * 
 * INTEGRATION POINTS:
 * -------------------
 * 
 * Add transcription in these places:
 * 
 * 1. **WhatsAppWebhookController** - Process incoming voice messages
 * 2. **WhatsApp Trait** - Before feeding message to AI
 * 3. **Chat Storage** - Save transcription alongside original audio
 * 
 * COST:
 * -----
 * - $0.006 per minute of audio
 * - 100 voice messages (30 sec avg) = $0.30/month
 * - Uses same OpenAI account as ChatGPT
 * 
 * BENEFITS:
 * ---------
 * ✅ AI can respond to voice messages
 * ✅ Agents can read instead of listen
 * ✅ Searchable voice content
 * ✅ Multi-language support (Arabic, Urdu, English, etc.)
 * ✅ 90%+ accuracy even with accents
 * 
 * LOGS:
 * -----
 * Check transcription logs at:
 * storage/logs/voice_transcription.log
 * 
 */
