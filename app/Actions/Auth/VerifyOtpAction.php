<?php

namespace App\Actions\Auth;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VerifyOtpAction
{
    /**
     * Verify OTP code and authenticate user via web guard for Login or Registration.
     *
     * @param  array{email: string, otp_code: string}  $data
     * @throws ValidationException
     */
    public function execute(array $data, Request $request): User
    {
        $email = strtolower(trim($data['email']));
        $inputCode = trim($data['otp_code']);

        $otpRecord = EmailOtp::where('email', $email)
            ->where('is_consumed', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            throw ValidationException::withMessages([
                'otp_code' => ['The verification code is invalid or has expired. Please request a new code.'],
            ]);
        }

        if ($otpRecord->attempts >= 5) {
            throw ValidationException::withMessages([
                'otp_code' => ['Too many failed verification attempts. Please request a new code.'],
            ]);
        }

        if (!Hash::check($inputCode, $otpRecord->otp_code_hash)) {
            $otpRecord->increment('attempts');
            throw ValidationException::withMessages([
                'otp_code' => ['Invalid OTP code. Please check and try again.'],
            ]);
        }

        $otpRecord->update(['is_consumed' => true]);

        $payload = $otpRecord->payload ?? [];

        if ($otpRecord->purpose === 'register') {
            $user = User::create([
                'name' => $payload['name'] ?? explode('@', $email)[0],
                'email' => $email,
                'phone' => $payload['phone'] ?? null,
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            \App\Models\DailyAnalytics::recordRegistration();
        } else {
            \App\Models\DailyAnalytics::recordLogin();
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $payload['name'] ?? explode('@', $email)[0],
                    'email' => $email,
                    'phone' => $payload['phone'] ?? null,
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            } else {
                if (!$user->is_active) {
                    throw ValidationException::withMessages([
                        'email' => ['This account has been deactivated. Please contact support.'],
                    ]);
                }

                if (empty($user->phone) && !empty($payload['phone'])) {
                    $user->update(['phone' => $payload['phone']]);
                }

                $user->update(['email_verified_at' => now()]);
            }
        }

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return $user;
    }
}
