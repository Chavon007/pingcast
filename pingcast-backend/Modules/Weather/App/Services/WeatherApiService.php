<?php

namespace Modules\Weather\App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WeatherApiService
{
    
    /**
     * Fetch current weather plus basic forecast for given coordinates.
     */

    public function getWeatherForLocation(string $location): array
    {
        $response = Http::get("https://api.weatherapi.com/v1/forecast.json", [
            "key" => config("services.weatherapi.key"),
            "q" => $location,
            "days" => 1,
            "aqi" => "no",
            "alerts" => "no", 
        ]);


        if(!$response->successful()){
            Log::error("Falied to fetch weather data", [
                "location" => $location,
                "status" => $response->status(),
                "body" => $response->body(),
            ]);

            throw new Exception(("Failed to fetch weather data"));
        }

        $data = $response->json();
        $forecastDay = $data['forecast']['forecastday'][0]['day'] ?? [];
          return [
            "location" => $data['location']['name'] ?? $location,
            "country" => $data['location']['country'] ?? null,
            "current" => [
                "temperature_2m" => $data['current']['temp_c'] ?? null,
            ],
            "daily" => [
                "temperature_2m_max" => [$forecastDay['maxtemp_c'] ?? null],
                "temperature_2m_min" => [$forecastDay['mintemp_c'] ?? null],
                "precipitation_probability_max" => [$forecastDay['daily_chance_of_rain'] ?? null],
            ],
        ];
    }
}