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

Route::get('/debug-report/{id}', function ($id) {
    $subscription = \Modules\Weather\App\Models\Subscription::find($id);
    if (!$subscription) {
        return response()->json(['error' => 'not found']);
    }

    $subscription->deliveryTime = now()->format('h:i A');
    $subscription->save();

    try {
        app(\Modules\Weather\App\Services\WeatherReportService::class)->processSubscription($subscription->fresh());
        return response()->json(['ok' => true, 'checked_at' => now()->toDateTimeString()]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});