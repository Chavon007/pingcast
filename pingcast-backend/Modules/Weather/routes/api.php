<?php

use Illuminate\Support\Facades\Route;
use Modules\Weather\Http\Controllers\WeatherController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('weather', WeatherController::class)->names('weather');
});
