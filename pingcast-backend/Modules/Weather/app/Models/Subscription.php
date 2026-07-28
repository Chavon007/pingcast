<?php

namespace Modules\Weather\App\Models;


use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
   

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
