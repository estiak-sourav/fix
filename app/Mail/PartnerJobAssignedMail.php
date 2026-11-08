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

class PartnerJobAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Partner $partner,
        public Booking $booking
    ) {}

    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'FIX');

        return new Envelope(
            subject: "New Job Assigned: #{$this->booking->booking_number} | {$siteName} Field Dispatch",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-job-assigned',
            with: [
                'partner' => $this->partner,
                'booking' => $this->booking,
                'portalUrl' => route('partner.jobs.show', $this->booking),
            ],
        );
    }
}
