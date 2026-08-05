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
        try {
            Mail::html($message, function ($mail) use ($email) {
                $mail->to($email)
                     ->subject('Your Daily Weather Report');
            });
            return true;
        } catch (Exception $e) {
            Log::error("Email delivery failed", [
                "email" => $email,
                "error" => $e->getMessage(),
            ]);
            throw new Exception('Failed to send email: ' . $e->getMessage());
        }
    }
}