<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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
        return new Envelope(
            subject: "Support Ticket #{$this->ticket->ticket_number} Received – FIX Helpdesk",
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
