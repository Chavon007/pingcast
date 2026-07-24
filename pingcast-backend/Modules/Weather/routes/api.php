<?php

use Illuminate\Support\Facades\Route;
use Modules\Weather\App\Http\Controllers\WeatherController;


Route::get("/", function(){
    return "Pingcast backend is running";
});
Route::post("/subscribe", [WeatherController::class, "store"])->name("subscribers.store");