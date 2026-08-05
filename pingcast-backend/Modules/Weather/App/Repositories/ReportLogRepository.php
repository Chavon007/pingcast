<?php

namespace Modules\Weather\App\Repositories;

use Modules\Weather\App\Interfaces\ReportLogRepositoryInterface;
use Modules\Weather\App\Models\ReportLog;
use Carbon\Carbon;


class ReportLogRepository implements ReportLogRepositoryInterface{
    protected ReportLog $model;

    public function __construct(ReportLog $model)
    {
        $this->model = $model;
    }

   

    public function findOrCreateForToday(string $subscriptionId): ReportLog
    {
        $today = Carbon::today()->toDateString();

        $log = $this->model->where("subscription_id", $subscriptionId)->where("date", $today)->first();

        if($log){
            return $log;
        }

        return $this->model->create([
            'subscription_id' => $subscriptionId,
            'date' => $today,
            'first_report' => 'pending',
            'second_report' => 'pending',
            'third_report' => 'pending',
        ]);
    }

    
        public function markSent(string $subscriptionId, string $slot): void
        {
            $log = $this->findOrCreateForToday($subscriptionId);
            $log->update([$slot => "sent"]);
        }


        public function markFailed(string $subscriptionId, string $slot): void
        {
            $log = $this->findOrCreateForToday($subscriptionId);
            if($log->{$slot} === "sent"){
                return;
            }
            $log->update([$slot => "failed"]);
        }
}