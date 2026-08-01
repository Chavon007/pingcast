<?php

use Illuminate\Support\Facades\Route;
use Modules\Weather\App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Artisan;

Route::get("/", function(){
    return "Pingcast backend is running";
});
Route::post("/subscribe", [WeatherController::class, "store"])->name("subscribers.store");
Route::post("/telegram/link", [WeatherController::class, "linkTelegram"]);
Route::post("/telegram/webhook", [WeatherController::class, "telegramWebhook"]);
Route::get("/admin/subscriptions", function(Illuminate\Http\Request $request) {
    if ($request->query('key') !== env('ADMIN_SECRET_KEY')) {
        abort(403);
    }
    return \Modules\Weather\App\Models\Subscription::with("reportLogs")->get();
});
Route::get("/run-scheduler", function(){
Artisan::call("schedule:run");
return response()->json(["ok" => true]);
});
