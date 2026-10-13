<?php

namespace App\Actions\Auth;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SendOtpAction
{
    /**
     * Generate, persist and dispatch email OTP.
     *
     * @param  array{email: string, name?: string|null}  $data
     * @return array{email: string, raw_otp: string}
     */
    public function execute(array $data, ?string $ip = null, ?string $userAgent = null): array
    {
        $email = strtolower(trim($data['email']));
        $rawOtp = (string) random_int(100000, 999999);

        EmailOtp::create([
            'email' => $email,
            'otp_code_hash' => Hash::make($rawOtp),
            'purpose' => 'login',
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'is_consumed' => false,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);

        if (!empty($data['name'])) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'role' => 'customer',
                    'is_active' => true,
                ]
            );
        }

        // In production, dispatch email: Mail::to($email)->queue(new OtpMail($rawOtp))
        Log::info("FIX Auth OTP generated for {$email}: [{$rawOtp}]");

        return [
            'email' => $email,
            'raw_otp' => $rawOtp,
        ];
    }
}
