<?php

namespace Modules\Weather\App\Models;


use MongoDB\Laravel\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = "mongodb";
    protected $collectin = "subscription";

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "location",
        "platform",
        "platformHandle",
        "deliveryTime"

    ];

    public function reportLogs(){
        return $this->hasMany(ReportLog::class, "subscription_id");
    }
    // protected static function newFactory(): SubscriptionFactory
    // {
    //     // return SubscriptionFactory::new();
    // }
}
