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
Route::get("/debug-logs", function(Illuminate\Http\Request $request){
    if ($request->query('key') !== env('ADMIN_SECRET_KEY')) {
        abort(403);
    }
    $logPath = storage_path('logs/laravel.log');
    if (!file_exists($logPath)) {
        return response()->json(['error' => 'log file not found']);
    }
    $lines = file($logPath);
    $lastLines = array_slice($lines, -60);
    return response()->json(['log_tail' => $lastLines]);
});
Route::get('/debug-test-and-log/{id}', function ($id, Illuminate\Http\Request $request) {
    if ($request->query('key') !== env('ADMIN_SECRET_KEY')) {
        abort(403);
    }

    $subscription = \Modules\Weather\App\Models\Subscription::find($id);
    if (!$subscription) {
        return response()->json(['error' => 'not found']);
    }

    $subscription->deliveryTime = now()->format('h:i A');
    $subscription->save();

    $result = ['triggered_at' => now()->toDateTimeString()];

    try {
        app(\Modules\Weather\App\Services\WeatherReportService::class)->processSubscription($subscription->fresh());
        $result['pipeline_result'] = 'ok';
    } catch (\Exception $e) {
        $result['pipeline_result'] = 'error';
        $result['pipeline_error'] = $e->getMessage();
    }

    $logPath = storage_path('logs/laravel.log');
    $result['log_tail'] = file_exists($logPath) ? array_slice(file($logPath), -20) : ['no log file'];

    return response()->json($result);
});
