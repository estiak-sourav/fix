<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatePartner
{
    /**
     * Handle an incoming request for the Partner/Vendor portal.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('partner')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated partner.'], 401);
            }

            return redirect()->route('partner.login')->with('error', 'Please log in to access the Partner Portal.');
        }

        $partner = Auth::guard('partner')->user();

        if ($partner->status !== 'approved') {
            Auth::guard('partner')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($partner->status) {
                'pending' => 'Your partner application is currently under review by FIX Admin. You will be able to log in once approved.',
                'rejected' => 'Your partner application was not approved. Please contact FIX support.',
                'suspended' => 'Your partner account has been suspended. Please contact FIX operations.',
                default => 'Your account is not authorized to access the partner portal.',
            };

            return redirect()->route('partner.login')->with('error', $message);
        }

        return $next($request);
    }
}
