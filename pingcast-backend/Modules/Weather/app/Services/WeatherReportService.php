<?php

namespace Modules\Weather\App\Services;

use Exception;
use Modules\Weather\Models\Subscription;

class WeatherReportService {

public function __construct(
    protected WeatherApiService $weatherApiService,
    protected AiSummaryService $aiSummaryService,
    protected N8nDeliveryService $n8nDeliveryService,

)
{}

// send weather report

public function sendReportFor(Subscription $subscription): bool{
    try{
        $weatherData = $this->weatherApiService->getWeatherForLocation($subscription->location);
        $message = $this->aiSummaryService->summarize($weatherData);

        $this->n8nDeliveryService->send(
            $subscription->platform,
            $subscription->platformHnadle,
            $message
        );
        return true;
    }catch(Exception $e){
         // Once ReportLog is built, this is where we'd mark the slot as "failed".
         throw $e;
    };
}
}