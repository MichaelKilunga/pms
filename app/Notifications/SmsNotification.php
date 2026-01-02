<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Support\Facades\Http;

class SmsNotification extends BaseNotification
{
    protected $message;
    protected $phoneNumber;

    public function __construct($phoneNumber, $message)
    {
        $this->message = $message;
        $this->phoneNumber = $phoneNumber;
    }

    // BaseNotification handles via() checking for toSms method

    public function toSms($notifiable)
    {
        return $this->message;
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'sms',
            'message' => $this->message,
            'to' => $this->phoneNumber
        ];
    }
}
