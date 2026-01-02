<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Contract;

class ContractExpiringNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public $contract;
    public $daysRemaining;

    public function __construct(Contract $contract, $daysRemaining)
    {
        $this->contract = $contract;
        $this->daysRemaining = $daysRemaining;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'info',
            'title' => 'Contract Expiring Soon',
            'body' => "Your contract ending on {$this->contract->end_date} expires in {$this->daysRemaining} days.",
            'action_url' => route('myContracts'),
            'created_at' => now(),
        ];
    }

    public function toSms($notifiable)
    {
        return "Reminder: Your contract expires in {$this->daysRemaining} days. Please renew to avoid service interruption.";
    }

    public function toWhatsapp($notifiable)
    {
        return "*Contract Expiration Reminder*\n\nYour contract expires in *{$this->daysRemaining} days* ({$this->contract->end_date}).\n\nPlease renew/upgrade via your dashboard.";
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Contract Expiring Soon')
            ->line("Your contract is set to expire in {$this->daysRemaining} days.")
            ->action('Renew Now', route('myContracts'));
    }
}
