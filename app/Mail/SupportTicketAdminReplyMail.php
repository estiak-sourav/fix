<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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
        return new Envelope(
            subject: "New Response on Ticket #{$this->ticket->ticket_number} – FIX Helpdesk",
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
