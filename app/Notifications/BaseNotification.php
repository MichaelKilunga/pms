<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Channels\SmsChannel;

class BaseNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];

        // Database (In-App)
        if ($this->checkUserChannelPreference($notifiable, 'database')) {
            $channels[] = 'database';
        }

        // SMS
        if (method_exists($this, 'toSms')) {
            if ($this->checkUserChannelPreference($notifiable, 'sms')) {
                $channels[] = SmsChannel::class;
            }
        }

        // WhatsApp
        if (method_exists($this, 'toWhatsapp')) {
            if ($this->checkUserChannelPreference($notifiable, 'whatsapp')) {
                $channels[] = WhatsAppChannel::class;
            }
        }

        // Mail
        if (method_exists($this, 'toMail')) {
             if ($this->checkUserChannelPreference($notifiable, 'mail')) {
                $channels[] = 'mail';
            }
        }

        return $channels;
    }

    protected function checkUserChannelPreference($notifiable, $channel)
    {
        if (method_exists($notifiable, 'wantsNotificationChannel')) {
            return $notifiable->wantsNotificationChannel($channel);
        }
        return true; // Default to true if no preference method
    }
}
