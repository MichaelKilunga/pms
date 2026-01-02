<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\MetaWhatsAppService;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    protected $whatsappService;

    public function __construct(MetaWhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function send($notifiable, Notification $notification)
    {
        // Check User Preferences
        if (method_exists($notifiable, 'wantsNotificationChannel') && !$notifiable->wantsNotificationChannel('whatsapp')) {
            return;
        }
        
        // If notifiable is NOT a User (e.g., anonymous), maybe skip preference check or default to send?
        // Assuming mostly Users.

        if (!method_exists($notification, 'toWhatsapp')) {
            return;
        }

        $message = $notification->toWhatsapp($notifiable);
        $to = $notifiable->routeNotificationFor('whatsapp') ?? $notifiable->phone_number; // Fallback to phone column

        if (!$to) {
            Log::warning("WhatsAppChannel: No phone number for user {$notifiable->id}");
            return;
        }

        // Use global settings if not pharmacy context? 
        // The service logic should handle picking global vs local. 
        // But here we just pass 'to' and 'message'.

        if (is_array($message) && isset($message['type']) && $message['type'] === 'document') {
             // Future: Implement document sending
             $this->whatsappService->sendDocumentLink($to, $message['link'], $message['caption'] ?? '');
        } else {
            // String or simple array
             $text = is_array($message) ? ($message['text'] ?? '') : $message;
             $this->whatsappService->sendMessage($to, $text);
        }
    }
}
