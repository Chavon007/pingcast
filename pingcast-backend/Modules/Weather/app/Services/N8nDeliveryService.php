<?php

namespace Modules\Weather\App\Services;
use  Illuminate\Support\Facades\Http;
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
        throw new Exception(("Failed to send message via n8n:". $response->body()));
    }
    return true;
}
}