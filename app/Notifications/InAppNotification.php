<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InAppNotification extends BaseNotification
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->notification['title'] ?? $this->notification['subject'] ?? 'New Notification';
        $body = $this->notification['message'] ?? $this->notification['body'] ?? '';
        $action = $this->notification['action_url'] ?? $this->notification['url'] ?? url('/');

        return (new MailMessage)
                    ->subject($subject)
                    ->line($body)
                    ->action('View Details', $action)
                    ->line('Thank you for using our application!');
    }

    /**
     * SMS Channel
     */
    public function toSms($notifiable)
    {
        $title = $this->notification['title'] ?? $this->notification['subject'] ?? '';
        $body = $this->notification['message'] ?? $this->notification['body'] ?? '';
        return strip_tags(($title ? $title . ": " : "") . $body);
    }

    /**
     * WhatsApp Channel
     */
    public function toWhatsapp($notifiable)
    {
        $title = $this->notification['title'] ?? $this->notification['subject'] ?? '';
        $body = $this->notification['message'] ?? $this->notification['body'] ?? '';
        $action = $this->notification['action_url'] ?? $this->notification['url'] ?? '';

        $msg = ($title ? "*" . $title . "*\n\n" : "") . $body;
        if ($action) {
            $msg .= "\n\nLink: " . $action;
        }
        return strip_tags($msg);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->notification;
    }
}
