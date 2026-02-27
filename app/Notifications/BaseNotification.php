<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Channels\SmsChannel;

class BaseNotification extends Notification
{
    public $forcedChannels = null;

    public function withChannels(array $channels)
    {
        $this->forcedChannels = $channels;
        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $potentialChannels = [];

        // Database (In-App)
        if ($this->checkUserChannelPreference($notifiable, 'database')) {
            $potentialChannels['database'] = 'database';
        }

        // SMS
        if (method_exists($this, 'toSms')) {
            if ($this->checkUserChannelPreference($notifiable, 'sms')) {
                $potentialChannels['sms'] = SmsChannel::class;
            }
        }

        // WhatsApp
        if (method_exists($this, 'toWhatsapp')) {
            if ($this->checkUserChannelPreference($notifiable, 'whatsapp')) {
                $potentialChannels['whatsapp'] = WhatsAppChannel::class;
            }
        }

        // Mail
        if (method_exists($this, 'toMail')) {
             if ($this->checkUserChannelPreference($notifiable, 'mail')) {
                $potentialChannels['mail'] = 'mail';
            }
        }

        // If forcedChannels is set, filter the potentials
        if (is_array($this->forcedChannels)) {
            $finalChannels = [];
            foreach ($this->forcedChannels as $forced) {
                if (isset($potentialChannels[$forced])) {
                    $finalChannels[] = $potentialChannels[$forced];
                }
            }
            return $finalChannels;
        }

        return array_values($potentialChannels);
    }

    protected function checkUserChannelPreference($notifiable, $channel)
    {
        if (method_exists($notifiable, 'wantsNotificationChannel')) {
            return $notifiable->wantsNotificationChannel($channel);
        }
        return true; // Default to true if no preference method
    }
}
