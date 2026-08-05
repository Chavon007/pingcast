<?php
namespace Modules\Weather\App\Interfaces;

interface ReportLogRepositoryInterface{
    public function findOrCreateForToday(string $subscriptionId):mixed;
    public function markSent(string $subscriptionId, string $slot):void;
    public function markFailed(string $subscriptionId, string $slot):void;
}