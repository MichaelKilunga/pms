<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Welcome to PILLPOINT')
                    ->line('Welcome to PILLPOINT, your number one application for managing pharmacies.')
                    ->action('Login Now', url('/dashboard'))
                    ->line('Thank you for trusting PILLPOINT!');
    }

    /**
     * SMS Channel
     */
    public function toSms($notifiable)
    {
        return "Welcome to PILLPOINT! Your pharmacy management solution. Login at " . url('/dashboard');
    }

    /**
     * WhatsApp Channel
     */
    public function toWhatsapp($notifiable)
    {
        return "*Welcome to PILLPOINT*\n\nYour number one application for managing pharmacies.\n\nLogin Now: " . url('/dashboard');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'success',
            'title' => 'Welcome to PILLPOINT',
            'body' => 'Welcome to PILLPOINT, your number one application for managing pharmacies.',
            'action_url' => url('/dashboard'),
            'created_at' => now(),
        ];
    }
}
