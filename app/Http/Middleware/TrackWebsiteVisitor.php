<?php

namespace App\Http\Middleware;

use App\Models\DailyAnalytics;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackWebsiteVisitor
{
    /**
     * Handle an incoming request to track public web visitors.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests on user-facing portal
        if ($request->isMethod('GET') && $response->getStatusCode() < 400) {
            // Exclude admin panel and API endpoints
            if ($request->is('admin*') || str_starts_with($request->getHost(), 'admin.')) {
                return $response;
            }

            try {
                $path = strtolower($request->path());
                $isService = str_contains($path, 'service') || $request->routeIs('services.*');
                $isAmc = str_contains($path, 'amc') || $request->has('plan') || $request->has('amc_plan');

                $isNew = !$request->hasCookie('fix_returning_user');
                if ($isNew) {
                    cookie()->queue(cookie()->forever('fix_returning_user', '1'));
                }

                $todayStr = today()->toDateString();
                $sessionKey = 'visited_' . $todayStr;

                if (!$request->session()->has($sessionKey)) {
                    $request->session()->put($sessionKey, true);
                    DailyAnalytics::recordVisitor($isService, $isAmc, $isNew);
                } else {
                    // Track specific section engagement even if visitor was already counted today
                    if ($isService || $isAmc) {
                        $record = DailyAnalytics::forDate($todayStr);
                        if ($isService && !$request->session()->has('visited_srv_' . $todayStr)) {
                            $request->session()->put('visited_srv_' . $todayStr, true);
                            $record->increment('service_visitors');
                        }
                        if ($isAmc && !$request->session()->has('visited_amc_' . $todayStr)) {
                            $request->session()->put('visited_amc_' . $todayStr, true);
                            $record->increment('amc_visitors');
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Silently continue so analytics never break user traffic
            }
        }

        return $response;
    }
}
