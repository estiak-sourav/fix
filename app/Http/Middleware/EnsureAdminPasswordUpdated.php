<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPasswordUpdated
{
    /**
     * Handle an incoming request.
     * Enforce mandatory password update on first login for admin staff.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && $admin->must_change_password) {
            $allowedRouteNames = [
                'admin.password.change',
                'admin.password.update',
                'admin.logout',
            ];

            if (! in_array($request->route()?->getName(), $allowedRouteNames, true)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Mandatory password change required before accessing the Admin Panel.',
                    ], 403);
                }

                return redirect()->route('admin.password.change');
            }
        }

        return $next($request);
    }
}
