<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request and check if website maintenance mode is active.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If maintenance mode is not active, allow traffic
        if (SiteSetting::get('maintenance_mode', '0') !== '1') {
            return $next($request);
        }

        // Allow admin routes, authentication, and management operations
        if ($request->is('admin*') || str_starts_with($request->getHost(), 'admin.') || $request->is('up')) {
            return $next($request);
        }

        // Allow authenticated administrators to preview the live website
        if (auth('admin')->check()) {
            return $next($request);
        }

        // Return custom maintenance page for public visitors with HTTP 503 Service Unavailable
        return response()->view('web.maintenance', [
            'maintenanceMessage' => SiteSetting::get(
                'maintenance_message',
                'We are currently performing scheduled maintenance to upgrade our systems. We will be back online shortly.'
            ),
        ], 503);
    }
}
