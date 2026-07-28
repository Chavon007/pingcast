<?php

namespace Modules\Weather\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Weather\App\Models\Subscription;

class ReportLog extends Model
{
    

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