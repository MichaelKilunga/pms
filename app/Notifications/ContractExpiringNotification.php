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
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        return [
            'type' => 'info',
            'title' => $isAdmin ? 'Contract Expiring (Admin Alert)' : 'Contract Expiring Soon',
            'body' => $isAdmin
                ? "Contract for {$ownerName} ending on {$this->contract->end_date} expires in {$this->daysRemaining} days."
                : "Your contract ending on {$this->contract->end_date} expires in {$this->daysRemaining} days.",
            'action_url' => route('myContracts'),
            'created_at' => now(),
        ];
    }

    public function toSms($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        if ($isAdmin) {
            return "Admin Alert: Contract for {$ownerName} expires in {$this->daysRemaining} days.";
        }

        return "Reminder: Your contract expires in {$this->daysRemaining} days. Please renew to avoid service interruption.";
    }

    public function toWhatsapp($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        if ($isAdmin) {
            return "*Admin Alert: Contract Expiring*\n\nContract for *{$ownerName}* expires in *{$this->daysRemaining} days* ({$this->contract->end_date}).";
        }

        return "*Contract Expiration Reminder*\n\nYour contract expires in *{$this->daysRemaining} days* ({$this->contract->end_date}).\n\nPlease renew/upgrade via your dashboard.";
    }

    public function toMail($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        $subject = $isAdmin ? "Contract Expiring: {$ownerName}" : "Contract Expiring Soon";
        $line = $isAdmin
            ? "The contract for {$ownerName} is set to expire in {$this->daysRemaining} days."
            : "Your contract is set to expire in {$this->daysRemaining} days.";

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->line($line)
            ->action('View Contract', route('myContracts'));
    }
}
