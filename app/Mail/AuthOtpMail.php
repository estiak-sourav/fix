<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $email,
        public ?string $name = null,
        public string $purpose = 'login'
    ) {}

    public function envelope(): Envelope
    {
        $actionText = $this->purpose === 'register' ? 'Account Registration' : 'Sign In';

        return new Envelope(
            subject: "{$this->otpCode} is your FIX Facility verification code for {$actionText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-otp',
            with: [
                'otpCode' => $this->otpCode,
                'email' => $this->email,
                'name' => $this->name ?: explode('@', $this->email)[0],
                'purpose' => $this->purpose,
            ],
        );
    }
}
