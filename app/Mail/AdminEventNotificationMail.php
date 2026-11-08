<?php

namespace App\Mail;

use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminEventNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $section,
        public string $title,
        public string $eventMessage,
        public ?string $actionUrl = null,
        public array $details = []
    ) {}

    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'FIX');
        $sectionLabel = match ($this->section) {
            'orders' => 'Orders & Dispatch',
            'partners' => 'Partner Network',
            'support' => 'Customer Support',
            'payments' => 'Billing & Payouts',
            default => 'System Alert'
        };

        return new Envelope(
            subject: "[{$siteName} {$sectionLabel}] {$this->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-event-notification',
            with: [
                'section' => $this->section,
                'title' => $this->title,
                'eventMessage' => $this->eventMessage,
                'actionUrl' => $this->actionUrl,
                'details' => $this->details,
            ],
        );
    }
}
