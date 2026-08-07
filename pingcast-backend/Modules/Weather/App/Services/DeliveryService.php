<?php

namespace Modules\Weather\App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class DeliveryService {

    // send final message plus delivery details to n8n for actual delivery.

    public function send(string $platform, string $platformHandle, string $message): bool
    {
        return match($platform){
            "telegram" => $this->sendTelegram($platformHandle, $message),
            "email" => $this->sendEmail($platformHandle, $message),
            default => throw new Exception("Unsupported platform: {$platform}"),
        };
    }

    protected function sendTelegram(string $chatId, string $message): bool
    {
        $token = config("services.telegram.token");

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            "chat_id" => $chatId,
            "text" => $message,
        ]);

        if (!$response->successful()) {
            Log::error('Telegram delivery failed.', [
                'chat_id' => $chatId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new Exception('Failed to send Telegram message: ' . $response->body());
        }
        return true;
    }

    protected function sendEmail(string $email, string $message): bool
    {
       $response = Http::withToken(config("services.resend.key"))->post("https://api.resend.com/emails", [
        "from" => "pingcast <hello@pingcast.site>",
        "to" => [$email],
        'subject' => 'Your Daily Weather Report',
        'html' => $message,
       ]);

       if(!$response->successful()){
        Log::error("Email delivert failed", [
            "email" => $email,
            "status" => $response->status(),
            "body" => $response->body(),
        ]);
        throw new Exception('Failed to send email: ' . $response->body());
       }
       return true;
    }
}