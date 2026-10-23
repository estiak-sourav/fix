<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeAdminPermission
{
    /**
     * Handle an incoming request and check if admin has required permission.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login');
        }

        if (! $admin->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Forbidden: You do not have the required permission ({$permission}).",
                ], 403);
            }

            abort(403, "Access Denied: You do not have permission ({$permission}) to access or perform actions on this section. Please contact your Super Administrator.");
        }

        return $next($request);
    }
}
