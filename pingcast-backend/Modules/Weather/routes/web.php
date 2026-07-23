<?php

use Illuminate\Support\Facades\Route;
use Modules\Weather\Http\Controllers\WeatherController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('weather', WeatherController::class)->names('weather');
});
