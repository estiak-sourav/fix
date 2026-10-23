<?php

namespace App\Actions\Auth;

use App\Mail\AuthOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use App\Rules\BangladeshPhone;
use App\Services\MailConfigService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SendOtpAction
{
    /**
     * Generate, persist and dispatch email OTP for Login or Registration.
     *
     * @param  array{email: string, name?: string|null, phone?: string|null, purpose?: string|null}  $data
     * @return array{email: string, raw_otp: string, purpose: string}
     *
     * @throws ValidationException
     */
    public function execute(array $data, ?string $ip = null, ?string $userAgent = null): array
    {
        $email = strtolower(trim($data['email']));
        $purpose = $data['purpose'] ?? 'login';
        $phone = ! empty($data['phone']) ? BangladeshPhone::normalize($data['phone']) : null;
        $name = ! empty($data['name']) ? trim($data['name']) : null;

        // Validation for Registration
        if ($purpose === 'register') {
            if (User::where('email', $email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => ['This email address is already registered. Please sign in instead.'],
                ]);
            }

            if ($phone && User::where('phone', $phone)->exists()) {
                throw ValidationException::withMessages([
                    'phone' => ['This Bangladesh mobile number is already registered with another account.'],
                ]);
            }
        } else {
            // Validation for Login
            $existingUser = User::where('email', $email)->first();
            if (! $existingUser) {
                throw ValidationException::withMessages([
                    'email' => ['No account found with this email. Please register to create your account.'],
                ]);
            }

            if (! $existingUser->is_active) {
                throw ValidationException::withMessages([
                    'email' => ['Your account has been deactivated. Please contact support.'],
                ]);
            }

            $name = $name ?: $existingUser->name;
            $phone = $phone ?: $existingUser->phone;
        }

        $rawOtp = (string) random_int(100000, 999999);

        // Invalidate older unconsumed OTPs for this email to prevent replay attacks
        EmailOtp::where('email', $email)
            ->where('is_consumed', false)
            ->update(['is_consumed' => true]);

        EmailOtp::create([
            'email' => $email,
            'otp_code_hash' => Hash::make($rawOtp),
            'purpose' => $purpose,
            'payload' => [
                'name' => $name,
                'phone' => $phone,
            ],
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'is_consumed' => false,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);

        // Dynamically apply SMTP credentials from site_settings
        MailConfigService::applyDynamicConfig();

        $mailSent = false;
        try {
            Mail::to($email)->send(new AuthOtpMail($rawOtp, $email, $name, $purpose));
            $mailSent = true;
            Log::info("FIX Auth OTP successfully dispatched to {$email} via ".config('mail.default'));
        } catch (\Throwable $e) {
            Log::error("Failed to send OTP email via SMTP to {$email}: ".$e->getMessage());

            // If local or log driver, let local dev continue smoothly
            if (! app()->isLocal() && config('mail.default') === 'smtp') {
                throw ValidationException::withMessages([
                    'email' => ['Failed to send OTP email. Please ensure your SMTP gateway is configured in the admin panel.'],
                ]);
            }
        }

        return [
            'email' => $email,
            'raw_otp' => $rawOtp,
            'purpose' => $purpose,
            'mail_sent' => $mailSent,
        ];
    }
}
