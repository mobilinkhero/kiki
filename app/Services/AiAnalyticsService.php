<?php

namespace App\Services;

class AiAnalyticsService
{
    /**
     * Detect language from text
     */
    public static function detectLanguage(string $text): string
    {
        // Remove extra spaces and normalize
        $text = trim($text);

        // Check for Urdu script (Unicode range)
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return 'urdu_script';
        }

        // Check for Roman Urdu patterns (common words)
        $romanUrduPatterns = [
            'aap',
            'mein',
            'hai',
            'hain',
            'ka',
            'ki',
            'ke',
            'kya',
            'kaise',
            'kaisay',
            'kab',
            'kahan',
            'kon',
            'kyun',
            'theek',
            'salam',
            'walaikum',
            'assalam',
            'chahiye',
            'chahte',
            'hoon'
        ];

        $lowerText = strtolower($text);
        foreach ($romanUrduPatterns as $pattern) {
            if (strpos($lowerText, $pattern) !== false) {
                return 'roman_urdu';
            }
        }

        // Default to English
        return 'english';
    }

    /**
     * Analyze sentiment from text
     */
    public static function analyzeSentiment(string $text): string
    {
        $text = strtolower($text);

        // Positive indicators (English + Roman Urdu)
        $positiveWords = [
            // English
            'good',
            'great',
            'excellent',
            'amazing',
            'wonderful',
            'perfect',
            'thanks',
            'thank you',
            'helpful',
            'appreciate',
            'happy',
            'love',
            // Roman Urdu
            'acha',
            'achha',
            'behtreen',
            'zabardast',
            'shukriya',
            'bahut acha',
            'khush',
            'maza',
            'pasand'
        ];

        // Negative indicators (English + Roman Urdu)
        $negativeWords = [
            // English
            'bad',
            'terrible',
            'awful',
            'worst',
            'hate',
            'angry',
            'frustrated',
            'problem',
            'issue',
            'error',
            'wrong',
            'not working',
            'useless',
            // Roman Urdu
            'bura',
            'kharab',
            'ghalat',
            'mushkil',
            'pareshani',
            'naraz',
            'problem',
            'masla',
            'nahi',
            'bekar'
        ];

        // Neutral/Question indicators
        $questionWords = ['?', 'kya', 'what', 'how', 'when', 'where', 'kaise', 'kab', 'kahan'];

        $positiveCount = 0;
        $negativeCount = 0;
        $hasQuestion = false;

        // Count positive words
        foreach ($positiveWords as $word) {
            if (strpos($text, $word) !== false) {
                $positiveCount++;
            }
        }

        // Count negative words
        foreach ($negativeWords as $word) {
            if (strpos($text, $word) !== false) {
                $negativeCount++;
            }
        }

        // Check for questions
        foreach ($questionWords as $word) {
            if (strpos($text, $word) !== false) {
                $hasQuestion = true;
                break;
            }
        }

        // Determine sentiment
        if ($negativeCount > $positiveCount) {
            return 'negative';
        } elseif ($positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($hasQuestion) {
            return 'neutral'; // Questions are neutral
        }

        return 'neutral';
    }

    /**
     * Get sentiment emoji
     */
    public static function getSentimentEmoji(string $sentiment): string
    {
        return match ($sentiment) {
            'positive' => '😊',
            'negative' => '😠',
            'neutral' => '😐',
            default => '😐'
        };
    }

    /**
     * Calculate response time from start timestamp
     */
    public static function calculateResponseTime(float $startTime): int
    {
        return (int) ((microtime(true) - $startTime) * 1000); // Convert to milliseconds
    }
}
