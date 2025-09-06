<?php

namespace App\Mail;

use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyPharmacyReport extends Mailable
{
    use Queueable, SerializesModels;

    public $pharmacy;
    public $salesSummary;
    public $stockStatus;
    public $reportDate;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(Pharmacy $pharmacy, array $salesSummary, array $stockStatus, $reportDate, $message)
    {
        $this->pharmacy = $pharmacy;
        $this->salesSummary = $salesSummary;
        $this->stockStatus = $stockStatus;
        $this->reportDate = $reportDate;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->message === 'daily') {
            return new Envelope(
                subject: "Daily Report - {$this->pharmacy->name} ({$this->reportDate})",
            );
        }
        return new Envelope(
            subject: "Custom Report - {$this->pharmacy->name} ({$this->reportDate})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-pharmacy-report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
