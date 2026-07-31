<?php

namespace Modules\Weather\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Weather\App\Http\Requests\StoreSubscriptionRequest;
use Modules\Weather\App\Services\SubscriptionService;
use Illuminate\Http\Request;


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
       
    public function linkTelegram(Request $request){

    $validated = $request->validate([
        'subscription_id' => 'required|string',
            'chat_id' => 'required|string',
    ]);
        $subscription = $this->subscriptionService->updatePlatformHandle($validated["subscription_id"], $validated["chat_id"]);

        if(!$subscription){
             return response()->json([
                'message' => 'Subscription not found',
            ], 404);
        }

        return response()->json([
            "message" => "Telegram linked successfully",
            "data" => $subscription
        ], 200);
    }
    
   public function telegramWebhook(Request $request)
{
    $text = $request->input("message.text", "");
    $chatId = $request->input("message.chat.id");

    if (!$chatId || !str_starts_with($text, "/start")) {
        return response()->json(["ok" => true]);
    }

    $parts = explode(" ", $text);
    $subscriptionId = $parts[1] ?? null;

    if (!$subscriptionId) {
        return response()->json(["ok" => true]);
    }

    $this->subscriptionService->updatePlatformHandle($subscriptionId, (string) $chatId);
    return response()->json(["ok" => true]);
}
}
