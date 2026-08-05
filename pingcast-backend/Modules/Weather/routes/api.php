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
Route::get("/debug-check-command", function(Illuminate\Http\Request $request){
    if ($request->query('key') !== env('ADMIN_SECRET_KEY')) {
        abort(403);
    }
    $commands = Artisan::all();
    return response()->json([
        'weather_command_exists' => isset($commands['weather:send-reports']),
        'all_command_names' => array_keys($commands),
    ]);
});
Route::get("/debug-module-status", function(Illuminate\Http\Request $request){
    if ($request->query('key') !== env('ADMIN_SECRET_KEY')) {
        abort(403);
    }

    $statusFile = base_path('modules_statuses.json');

    return response()->json([
        'modules_statuses_file_exists' => file_exists($statusFile),
        'modules_statuses_content' => file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : null,
        'weather_provider_loaded' => array_key_exists('Modules\Weather\App\Providers\WeatherServiceProvider', app()->getLoadedProviders()),
        'weather_folder_exists' => is_dir(base_path('Modules/Weather')),
        'weather_command_file_exists' => file_exists(base_path('Modules/Weather/App/Console/Commands/SendWeatherReports.php')),
    ]);
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