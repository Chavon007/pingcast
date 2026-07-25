<?php

namespace Modules\Weather\App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Modules\Weather\App\Models\Subscription;

class ReportLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'report_logs';

    protected $fillable = [
        'subscription_id',
        'date',
        'first_report',
        'second_report',
        'third_report',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}