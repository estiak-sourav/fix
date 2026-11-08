<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Partner;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerJobUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Partner $partner,
        public Booking $booking,
        public string $updateNote = 'Job schedule or arrival window has been updated.'
    ) {}

    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'FIX');

        return new Envelope(
            subject: "Job Update: #{$this->booking->booking_number} Schedule/Details | {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-job-updated',
            with: [
                'partner' => $this->partner,
                'booking' => $this->booking,
                'updateNote' => $this->updateNote,
                'portalUrl' => route('partner.jobs.show', $this->booking),
            ],
        );
    }
}
