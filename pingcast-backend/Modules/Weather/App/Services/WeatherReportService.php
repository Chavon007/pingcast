<?php

namespace Modules\Weather\App\Services;
use Modules\Weather\App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Modules\Weather\App\Interfaces\ReportLogRepositoryInterface;
use Carbon\Carbon;
use Exception;

class WeatherReportService
{
    public function __construct(
        protected WeatherApiService $weatherApiService,
        protected AiSummaryService $aiSummaryService,
        protected DeliveryService $DeliveryService,
        protected ReportLogRepositoryInterface $reportLogRepository,
    ) {}

    /**
     * Check if a report is due for this subscription right now,
     * and if so, send it and update the report log accordingly.
     */
public function processSubscription(Subscription $subscription): void
{
    $slot = $this->getDueSlot($subscription);

   
    if (!$slot) {
        return; // No report due right now
    }

    $reportLog = $this->reportLogRepository->findOrCreateForToday((string) $subscription->id);

    // Only skip if it was already successfully sent — failed attempts get retried
    if ($reportLog->{$slot} === 'sent') {
        return;
    }

    try {
        $this->sendReportFor($subscription);
        $this->reportLogRepository->markSent((string) $subscription->id, $slot);
    } catch (Exception $e) {
        Log::error('Report failed for subscription.', [
        'subscription_id' => $subscription->id,
        'slot' => $slot,
        'error' => $e->getMessage(),
    ]);
        $this->reportLogRepository->markFailed((string) $subscription->id, $slot);
    }
}
    /**
     * Run the actual weather -> AI -> n8n pipeline for a subscription.
     */
    protected function sendReportFor(Subscription $subscription): void
    {
        $weatherData = $this->weatherApiService->getWeatherForLocation($subscription->location);

        $message = $this->aiSummaryService->summarize($weatherData);

        $this->DeliveryService->send(
            $subscription->platform,
            $subscription->platformHandle,
            $message
        );
    }

    /**
     * Determine which slot (first_report, second_report, third_report) is due right now,
     * based on the subscriber's chosen deliveryTime. Returns null if none are due.
     */
protected function getDueSlot(Subscription $subscription): ?string
{
    $baseTime = Carbon::createFromFormat('h:i A', $subscription->deliveryTime);
    $now = Carbon::now();

    // Exact scheduled time - always attempt
    if ($now->format('H:i') === $baseTime->format('H:i')) {
        return 'first_report';
    }

    // If scheduled time has already passed today, retry if the last attempt failed
    if ($now->greaterThan($baseTime)) {
        $reportLog = $this->reportLogRepository->findOrCreateForToday((string) $subscription->id);

        if ($reportLog->first_report === 'failed') {
            return 'first_report';
        }
    }

    return null;
}
}