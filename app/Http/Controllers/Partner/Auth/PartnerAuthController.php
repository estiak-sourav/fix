<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerAuthController extends Controller
{
    /**
     * Show Partner / Field Crew login page.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('partner')->check()) {
            return redirect()->route('partner.dashboard');
        }

        return view('partner.auth.login');
    }

    /**
     * Authenticate partner using Phone + Password (No OTP required).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'Please enter your registered mobile number.',
            'password.required' => 'Please enter your partner password.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', (string) $credentials['phone']);
        // Normalize 88017... to 017...
        if (str_starts_with($phone, '8801')) {
            $phone = substr($phone, 2);
        }

        // Try phone match directly or normalized
        $partner = Partner::where('phone', $credentials['phone'])
            ->orWhere('phone', $phone)
            ->orWhere('phone', 'like', "%{$phone}")
            ->first();

        if (! $partner) {
            return back()->withInput($request->only('phone', 'remember'))
                ->withErrors(['phone' => 'No partner account found with this mobile number.']);
        }

        if (empty($partner->password)) {
            return back()->withInput($request->only('phone', 'remember'))
                ->withErrors(['password' => 'Your partner account has not been activated with a password. Please contact FIX Admin.']);
        }

        if (! Auth::guard('partner')->attempt(['id' => $partner->id, 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withInput($request->only('phone', 'remember'))
                ->withErrors(['password' => 'Incorrect password entered. Please try again.']);
        }

        // Check verification & approval state
        if ($partner->status !== 'approved') {
            Auth::guard('partner')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $errorMsg = match ($partner->status) {
                'pending' => 'Your enlistment application is currently pending admin review. You can log in once approved.',
                'rejected' => 'Your partner enlistment was not approved by FIX Operations.',
                'suspended' => 'Your partner account is temporarily suspended. Please contact FIX Admin.',
                default => 'Account is not authorized to access the Partner Portal.',
            };

            return back()->withInput($request->only('phone'))->withErrors(['phone' => $errorMsg]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('partner.dashboard'))
            ->with('success', "Welcome back, {$partner->contact_person}!");
    }

    /**
     * Terminate partner session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('partner')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('partner.login')->with('success', 'You have been logged out of the Partner Portal.');
    }
}
