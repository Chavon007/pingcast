<?php

namespace Modules\Weather\App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AiSummaryService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
    }

    public function summarize(array $weatherData): string
    {
        $prompt = $this->buildPrompt($weatherData);

        $response = Http::withToken($this->apiKey)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a friendly weather assistant. Keep responses under 120 words, warm tone, use emojis naturally.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
            ]);

        if (!$response->successful()) {
            Log::error('AI summary generation failed.', [
        'status' => $response->status(),
        'body' => $response->body(),
    ]);
            throw new Exception('Failed to generate AI summary: ' . $response->body());
        }

        return $response->json('choices.0.message.content');
    }

    protected function buildPrompt(array $weatherData): string
    {
        $location = $weatherData['location'] ?? 'your area';
        $current = $weatherData['current'] ?? [];
        $daily = $weatherData['daily'] ?? [];

        $temp = $current['temperature_2m'] ?? 'N/A';
        $rainChance = $daily['precipitation_probability_max'][0] ?? 'N/A';
        $maxTemp = $daily['temperature_2m_max'][0] ?? 'N/A';
        $minTemp = $daily['temperature_2m_min'][0] ?? 'N/A';

        return "You are PingCast's AI weather assistant.

Generate a personalized daily weather update for {$location} using the information below:

Current Temperature: {$temp}°C
Today's High: {$maxTemp}°C
Today's Low: {$minTemp}°C
Chance of Rain: {$rainChance}%

Requirements:
- Start with a friendly greeting.
- Summarize today's weather in one sentence.
- Explain what the weather means for someone's day.
- Recommend appropriate clothing.
- Suggest whether to carry an umbrella, sunscreen, or other essentials.
- Give travel advice based on the conditions.
- Recommend 2–3 activities that fit the weather.
- Include one health or wellness tip.
- End with an uplifting message.

Keep the response under 200 words, make it conversational and engaging, and use relevant emojis sparingly. Avoid repeating the raw weather values unless necessary.";
    }
}