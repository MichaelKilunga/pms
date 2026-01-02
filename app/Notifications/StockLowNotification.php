<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Stock;

class StockLowNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'warning',
            'title' => 'Low Stock Alert',
            'body' => "The stock for {$this->stock->medicine_name} is low ({$this->stock->remain_Quantity} remaining).",
            'action_url' => route('stock.show', $this->stock->id),
            'created_at' => now(),
        ];
    }

    public function toSms($notifiable)
    {
        return "Low Stock: {$this->stock->medicine_name} is down to {$this->stock->remain_Quantity}. Please restocking.";
    }

    public function toWhatsapp($notifiable)
    {
        return "*Low Stock Alert*\n\nProduct: {$this->stock->medicine_name}\nRemaining: {$this->stock->remain_Quantity}\n\nPlease restock soon.";
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Low Stock Alert: ' . $this->stock->medicine_name)
            ->line("The stock for {$this->stock->medicine_name} is running low.")
            ->line("Remaining Quantity: {$this->stock->remain_Quantity}")
            ->action('View Stock', route('stock.show', $this->stock->id));
    }
}
