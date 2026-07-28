<?php

namespace Modules\Weather\App\Services;

use Modules\Weather\App\Models\Subscription;
use Modules\Weather\App\Interfaces\ReportLogRepositoryInterface;
use Carbon\Carbon;
use Exception;

class WeatherReportService
{
    public function __construct(
        protected WeatherApiService $weatherApiService,
        protected AiSummaryService $aiSummaryService,
        protected N8nDeliveryService $n8nDeliveryService,
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

        $this->n8nDeliveryService->send(
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

        $slots = [
            'first_report' => $baseTime->copy(),
            'second_report' => $baseTime->copy()->addHours(6),
            'third_report' => $baseTime->copy()->addHours(12),
        ];

        $now = Carbon::now();

        foreach ($slots as $slot => $time) {
            // "Due" = current time is within the same minute as the slot's scheduled time
            if ($now->format('H:i') === $time->format('H:i')) {
                return $slot;
            }
        }

        return null;
    }
}