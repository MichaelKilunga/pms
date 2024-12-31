<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Support\Facades\Http;

class SmsNotification extends Notification
{
    protected $message;
    protected $phoneNumber;

    public function __construct($phoneNumber, $message)
    {
        $this->message = $message;
        $this->phoneNumber = $phoneNumber;
    }

    public function via($notifiable)
    {
        return ['database', 'nexmo'];  // You can keep other channels if needed.
    }

    public function toNexmo($notifiable)
    {
        // You can implement custom logic for the Nexmo channel
    }

    public function toSms($notifiable)
    {
        // Send SMS using NextSMS API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('NEXTSMS_API_KEY'),
        ])->post(env('NEXTSMS_API_URL'), [
            'sender_id' => env('NEXTSMS_SENDER_ID'),
            'to' => $this->phoneNumber,
            'message' => $this->message,
        ]);

        if ($response->failed()) {
            dd($response->body());
            throw new \Exception("SMS sending failed: " . $response->body());
        }
        dd($response->body());

        return $response->body();
    }
}
