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

class PartnerJobCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Partner $partner,
        public Booking $booking,
        public string $reason = 'Order cancelled or recalled by dispatch.'
    ) {}

    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'FIX');

        return new Envelope(
            subject: "Job Notice: Order #{$this->booking->booking_number} Cancelled | {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-job-cancelled',
            with: [
                'partner' => $this->partner,
                'booking' => $this->booking,
                'reason' => $this->reason,
                'portalUrl' => route('partner.jobs.index'),
            ],
        );
    }
}
