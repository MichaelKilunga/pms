<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'wantsNotificationChannel') && !$notifiable->wantsNotificationChannel('sms')) {
            return;
        }

        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);
        $to = $notifiable->routeNotificationFor('sms') ?? $notifiable->phone_number;

        if (!$to) {
            return;
        }

        $this->smsService->send($to, $message);
    }
}
