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
        $channels = ['database']; // Always in-app by default? Or make configurable?

        // Check if method exists to avoid errors if notification doesn't support the channel
        
        // SMS
        if (method_exists($this, 'toSms')) {
            // Check preferences
            if ($this->shouldSend($notifiable, 'sms')) {
                $channels[] = SmsChannel::class;
            }
        }

        // WhatsApp
        if (method_exists($this, 'toWhatsapp')) {
            if ($this->shouldSend($notifiable, 'whatsapp')) {
                $channels[] = WhatsAppChannel::class;
            }
        }

        // Mail
        if (method_exists($this, 'toMail')) {
             if ($this->shouldSend($notifiable, 'mail')) {
                $channels[] = 'mail';
            }
        }

        return $channels;
    }

    protected function shouldSend($notifiable, $channel)
    {
        if (method_exists($notifiable, 'wantsNotificationChannel')) {
            return $notifiable->wantsNotificationChannel($channel);
        }
        return true; // Default to true if no preference method
    }
}
