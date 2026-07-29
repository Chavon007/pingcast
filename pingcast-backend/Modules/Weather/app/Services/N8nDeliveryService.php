<?php

namespace Modules\Weather\App\Services;
use  Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class N8nDeliveryService {

protected string $webhookUrl;

public function __construct()
{
    $this->webhookUrl = config("services.n8n.webhook_url");
}

// send final message plus delivery details to n8n for actual delivery.

public function send(string $platform, string $platformHandle, string $message ): bool{
    $response = Http::post($this->webhookUrl, [
        "platform" => $platform,
        "platformHandle" => $platformHandle,
        "message" => $message
    ]);

    if(!$response->successful()){
        Log::error('n8n delivery failed.', [
        'platform' => $platform,
        'platformHandle' => $platformHandle,
        'status' => $response->status(),
        'body' => $response->body(),
    ]);
        throw new Exception(("Failed to send message via n8n:". $response->body()));
    }
    return true;
}
}