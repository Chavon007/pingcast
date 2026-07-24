<?php

namespace Modules\Weather\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Weather\App\Requests\StoreSubscriptionRequest;
use Modules\Weather\App\Services\SubscriptionService;

class WeatherController extends Controller
{

public function __construct(protected SubscriptionService $subscriptionService){
}

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request) {
        $subscription = $this->subscriptionService->create($request->validated());

        return response()->json([
            "message" => "Successfully subscribed",
            "data" => $subscription
        ], 201);
    }
       

    
   
}
