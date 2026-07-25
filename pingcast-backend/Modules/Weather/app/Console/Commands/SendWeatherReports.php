<?php

namespace Modules\Weather\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Weather\App\Models\Subscription;
use Modules\Weather\App\Services\WeatherReportService;

class SendWeatherReports extends Command{
    protected $signature = "weather:send-reports";
    protected $description = 'Check all subscriptions and send weather reports that are due right now';

    public function handle(WeatherReportService $weatherReportService): void{
        $subscription = Subscription::all();

        foreach($subscriptions as $subscription){
            $weatherReportService->processSubscription($subscription);
        }
        $this->info('Weather report check completed for ' . $subscriptions->count() . ' subscriptions.')
    }
}