<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\SendOtpAction;
use App\Actions\Auth\VerifyOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SendOtpRequest;
use App\Http\Requests\Web\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpAuthController extends Controller
{
    /**
     * Generate and dispatch email OTP.
     */
    public function sendOtp(SendOtpRequest $request, SendOtpAction $action): JsonResponse
    {
        $result = $action->execute($request->validated(), $request->ip(), $request->userAgent());

        $actionWord = ($result['purpose'] ?? 'login') === 'register' ? 'registration' : 'sign in';

        return response()->json([
            'success' => true,
            'message' => "Verification code sent to {$result['email']} for {$actionWord}.",
            'purpose' => $result['purpose'] ?? 'login',
        ]);
    }

    /**
     * Verify email OTP and establish authenticated web session.
     */
    public function verifyOtp(VerifyOtpRequest $request, VerifyOtpAction $action): JsonResponse
    {
        $user = $action->execute($request->validated(), $request);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'redirect' => $request->session()->pull('url.intended', route('customer.bookings')),
        ]);
    }

    /**
     * Terminate authenticated web session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
