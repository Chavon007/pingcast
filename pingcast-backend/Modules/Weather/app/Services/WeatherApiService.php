<?php

namespace Modules\Weather\App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class WeatherApiService
{
    /**
     * Convert a location string (e.g. "Lagos, Nigeria") into lat/lon coordinates.
     */
    public function geocodeLocation(string $location): array
    {
        $response = Http::get('https://geocoding-api.open-meteo.com/v1/search', [
            "name" => $location,
            "count" => 1,
            "language" => "en",
            "format" => "json",
        ]);

        if (!$response->successful() || empty($response->json("results"))) {
            throw new Exception("Could not find coordinates for location: {$location}");
        }

        $result = $response->json("results")[0];

        return [
            "latitude" => $result["latitude"],
            "longitude" => $result["longitude"],
            "resolved_name" => $result["name"] ?? $location,
            "country" => $result["country"] ?? null,
            "timezone" => $result["timezone"] ?? "auto",
        ];
    }

    /**
     * Fetch current weather plus basic forecast for given coordinates.
     */
    public function getWeather(float $latitude, float $longitude, string $timezone = "auto"): array
    {
        $response = Http::get("https://api.open-meteo.com/v1/forecast", [
            "latitude" => $latitude,
            "longitude" => $longitude,
            'current' => 'temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m',
            'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_probability_max,weather_code',
            'timezone' => $timezone,
            'forecast_days' => 1,
        ]);

        if (!$response->successful()) {
            throw new Exception("Failed to fetch weather data.");
        }

        return $response->json();
    }

    /**
     * Convenience method: geocode a location string and fetch its weather in one call.
     */
    public function getWeatherForLocation(string $location): array
    {
        $coordinates = $this->geocodeLocation($location);

        $weather = $this->getWeather(
            $coordinates["latitude"],
            $coordinates["longitude"],
            $coordinates["timezone"],
        );

        return [
            "location" => $coordinates["resolved_name"],
            "country" => $coordinates["country"],
            "current" => $weather["current"] ?? [],
            "daily" => $weather["daily"] ?? [],
        ];
    }
}