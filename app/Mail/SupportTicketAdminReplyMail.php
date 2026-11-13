<?php

namespace App\Mail;

use App\Models\SiteSetting;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketAdminReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public SupportTicketMessage $replyMessage,
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
            subject: "[No-Reply] New Response on Ticket #{$this->ticket->ticket_number} – {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.support-ticket-admin-reply',
            with: [
                'ticket' => $this->ticket,
                'replyMessage' => $this->replyMessage,
                'trackUrl' => $this->accessToken
                    ? route('support.show', ['ticketNumber' => $this->ticket->ticket_number, 'token' => $this->accessToken])
                    : route('support.track'),
            ],
        );
    }
}
