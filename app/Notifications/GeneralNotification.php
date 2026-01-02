<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends BaseNotification
{
    use Queueable;
    public $notification;

    /**
     * Create a new notification instance.
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    // BaseNotification handles via()

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->notification['subject'])
                    ->line($this->notification['body'])
                    ->action($this->notification['action'], url('/'.$this->notification['path']))
                    ->line('Thank you for trusting PILLPOINT!');
    }

    /**
     * Get the array representation of the notification (Database/In-App).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->notification['subject'],
            'body' => $this->notification['body'],
            'action_url' => url('/'.$this->notification['path']),
            'created_at' => now(),
        ];
    }
    
    /**
     * SMS Channel
     */
    public function toSms($notifiable)
    {
        return strip_tags($this->notification['subject'] . ": " . $this->notification['body']);
    }

    /**
     * WhatsApp Channel
     */
    public function toWhatsapp($notifiable)
    {
        return strip_tags("*" . $this->notification['subject'] . "*\n\n" . $this->notification['body'] . "\n\nLink: " . url('/'.$this->notification['path']));
    }
}
