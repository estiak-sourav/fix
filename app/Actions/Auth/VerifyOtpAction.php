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
     * Verify OTP code and authenticate user via web guard.
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
                'otp_code' => ['Invalid OTP code. Please try again.'],
            ]);
        }

        $otpRecord->update(['is_consumed' => true]);

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => explode('@', $email)[0],
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['This account has been deactivated. Please contact support.'],
            ]);
        }

        $user->update(['email_verified_at' => now()]);

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return $user;
    }
}
