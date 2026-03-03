<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Contract;

class BillingNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public $contract;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';
        
        return [
            'type' => 'billing',
            'title' => $isAdmin ? 'New Billing (Admin Alert)' : 'New Billing Generated',
            'body' => $isAdmin 
                ? "New bill generated for {$ownerName} (TZS " . number_format($this->contract->amount) . ")."
                : "Your contract has expired. A new bill (TZS " . number_format($this->contract->amount) . ") has been generated for the next period.",
            'action_url' => route('myContracts'),
            'created_at' => now(),
        ];
    }

    /**
     * SMS Channel
     */
    public function toSms($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        if ($isAdmin) {
            return "Admin Alert: New bill of TZS " . number_format($this->contract->amount) . " generated for {$ownerName}.";
        }

        return "New Billing: A new bill of TZS " . number_format($this->contract->amount) . " has been generated for your next contract period. Please pay to continue service.";
    }

    /**
     * WhatsApp Channel
     */
    public function toWhatsapp($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        if ($isAdmin) {
            return "*Admin Alert: New Billing*\n\nA new bill of *TZS " . number_format($this->contract->amount) . "* has been generated for *{$ownerName}*.\n\nPlease monitor for payment.";
        }

        return "*New Billing Generated*\n\nYour contract has expired. A new bill of *TZS " . number_format($this->contract->amount) . "* has been generated for the next period.\n\nPlease pay via your dashboard to activate.";
    }

    /**
     * Mail Channel
     */
    public function toMail($notifiable)
    {
        $isAdmin = $notifiable->role === 'super';
        $ownerName = $this->contract->owner ? $this->contract->owner->name : 'N/A';

        $subject = $isAdmin ? "New Billing Generated: {$ownerName}" : "New Billing Generated";
        $line = $isAdmin 
            ? "A new bill (TZS " . number_format($this->contract->amount) . ") has been generated for owner: {$ownerName}."
            : "Your contract has expired. A new bill (TZS " . number_format($this->contract->amount) . ") has been generated for the next period.";

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->line($line)
            ->action('View Billing', route('myContracts'));
    }
}
