<?php

namespace Modules\Weather\App\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest{
    public function rules(){
        return [
        "location" => "required|string|max:255",
        "platform" => "required|string|in:whatsapp,sms,email,telegram",
        "platformHandle" => [
            "required",
            "string",
            "max:255",

            function($attribute, $value, $fail){
                $platform = $this->input("platform");

                if(in_array($platform, ["whatsapp", "sms"])){
                    if(!preg_match(`/^\+?[1-9]\d{7,14}$/`, $value)){
                        $fail("Enter a valid phone number.");
                    }
                }

                if($platform === "email"){
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $fail("Enter a valid email address.");
                    }
                }
                if($platform === "telegram" && strlen($value) < 1){
                    $fail("Enter a valid Telegram username.");
                }
            }
        ],
        "deliveryTime" => "required|date_format:h:i A"
        ];
    }
} 