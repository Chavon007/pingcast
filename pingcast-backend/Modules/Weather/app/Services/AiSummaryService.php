<?php

namespace Modules\Weather\App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class AiSummaryService {
    protected string $apikey;

    public function __construct()
    {
        $this->apikey = config("services.openai.key");
    }

    // take raw weather data and return ai-generated advice

    public function summarize(array $weatherData): string{
        $prompt = $this->buildPrompt($weatherData);

        $response = Http::withToken($this->apikey)->post("https://api.openai.com/v1/chat/completions", [
            "model" => "gpt-4o-mini",
            "message" => [
                [
                    "role" => "system",
                    "content" => "You are a friendly weather assistant. Keep responses under 200 words, warm tone, use emojis naturally."
                ],
                [
                    "role" => "user",
                    "content" => $prompt,
                ],
            ],
            "temperature" => 0.7,
        ]);

        if(!$response->successful()){
            throw new Exception("Failed to generate AI summary:" . $response->body());
        }

        return $response->json("choice.0.message.content");
    }


    // Build the prompt text sent to the AI based on weather data.

    protected function buildPrompt(array $weatherData): string{
        $location = $weatherData["location"] ?? "your area";
        $current = $weatherData["current"] ?? [];
        $daily = $weatherData["daily"] ?? [];

        $temp = $current['temperature_2m'] ?? 'N/A';
        $rainChance = $daily['precipitation_probability_max'][0] ?? 'N/A';
        $maxTemp = $daily['temperature_2m_max'][0] ?? 'N/A';
        $minTemp = $daily['temperature_2m_min'][0] ?? 'N/A';


        return "Summarize today's weather for {$location}. "
            . "Current temperature: {$temp}°C. "
            . "Today's high: {$maxTemp}°C, low: {$minTemp}°C. "
            . "Chance of rain: {$rainChance}%. "
            . "Recommend clothing, travel tips, fun thing to do,  and health advice. Keep it under 200 words, friendly tone with emojis.";
    }
}