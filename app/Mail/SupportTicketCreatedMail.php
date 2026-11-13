<?php

namespace App\Mail;

use App\Models\SiteSetting;
use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public ?string $accessToken = null
    ) {}

    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'FIX Facility Management');
        $noReplyEmail = SiteSetting::get('no_reply_email', 'no-reply@fix.com.bd');
        $fromName = "{$siteName} (No-Reply)";

        return new Envelope(
            from: new Address($noReplyEmail, $fromName),
            replyTo: [
                new Address($noReplyEmail, 'Do Not Reply'),
            ],
            subject: "[No-Reply] Support Ticket #{$this->ticket->ticket_number} Received – {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.support-ticket-created',
            with: [
                'ticket' => $this->ticket,
                'accessToken' => $this->accessToken,
                'trackUrl' => $this->accessToken
                    ? route('support.show', ['ticketNumber' => $this->ticket->ticket_number, 'token' => $this->accessToken])
                    : route('support.track'),
            ],
        );
    }
}
