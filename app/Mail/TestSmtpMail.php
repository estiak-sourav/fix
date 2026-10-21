<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientEmail,
        public string $configuredHost,
        public int $configuredPort
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FIX Facility Operations: SMTP Gateway Test Succeeded ✅',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test-smtp',
            with: [
                'recipientEmail' => $this->recipientEmail,
                'configuredHost' => $this->configuredHost,
                'configuredPort' => $this->configuredPort,
                'timestamp' => now()->toDayDateTimeString(),
            ],
        );
    }
}
