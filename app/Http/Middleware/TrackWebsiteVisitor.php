<?php

namespace App\Http\Middleware;

use App\Models\DailyAnalytics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackWebsiteVisitor
{
    /**
     * Handle an incoming request to track public web visitors.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Only track successful GET requests with 2xx status
        if (! $request->isMethod('GET') || $response->getStatusCode() >= 400) {
            return $response;
        }

        // 2. Exclude admin panel and admin subdomains
        if ($request->is('admin*') || str_starts_with($request->getHost(), 'admin.')) {
            return $response;
        }

        // 3. Exclude API, AJAX, JSON, and system health endpoints
        if ($request->is('api*', 'up') || $request->ajax() || $request->expectsJson() || $request->header('X-Requested-With')) {
            return $response;
        }

        // 4. Exclude static assets, uploads, and media requests
        if ($request->is('storage*', 'assets*', 'uploads*', 'build*', 'vendor*')) {
            return $response;
        }

        // 5. Exclude file extensions (images, scripts, styles, fonts, manifests)
        if (preg_match('/\.(?:ico|png|jpe?g|webp|gif|svg|css|js|map|txt|xml|json|woff2?|ttf|eot)$/i', $request->path())) {
            return $response;
        }

        // 6. Must accept HTML (actual webpage views by a human browser)
        if (! $request->acceptsHtml()) {
            return $response;
        }

        // 7. Filter out automated bots, crawlers, and scrapers
        $userAgent = strtolower($request->userAgent() ?? '');
        $isBot = (bool) preg_match('/bot|crawl|spider|slurp|google|bing|yandex|baidu|duckduckgo|facebookexternalhit|whatsapp|telegram|curl|wget|python|postman|headless|scanner|uptimerobot|censys|shodan|pingdom/i', $userAgent);

        if (! app()->runningUnitTests() && empty($userAgent)) {
            $isBot = true;
        }

        if ($isBot) {
            return $response;
        }

        try {
            $path = strtolower($request->path());
            $isService = str_contains($path, 'service') || $request->routeIs('services.*');
            $isAmc = str_contains($path, 'amc') || $request->has('plan') || $request->has('amc_plan');

            // 1. Identify first-time visitor vs returning visitor
            $session = $request->session();
            $isNew = ! $request->hasCookie('fix_returning_user') && ! $session->has('visit_seen_before');
            if ($isNew) {
                cookie()->queue(cookie()->forever('fix_returning_user', '1'));
                $session->put('visit_seen_before', true);
            }

            // 2. Determine if this request begins a new visit or continues an ongoing browsing session
            $hasActiveCookie = $request->hasCookie('fix_visit_active');
            $hasActiveSession = $session->has('visit_id');
            $lastActive = (int) $session->get('visit_last_active', 0);

            // A new visit starts when:
            // - The session has no active visit_id (brand new visit, incognito, or fresh session)
            // - OR the user was idle for more than 15 minutes (900 seconds) and returned
            // - OR the browser was closed (session cookie 'fix_visit_active' was removed by browser)
            $isNewVisit = ! $hasActiveSession
                || ($lastActive > 0 && (now()->timestamp - $lastActive) > 900)
                || (! app()->runningUnitTests() && ! $hasActiveCookie);

            if ($isNewVisit) {
                // Short debounce (5s) per IP+UA to prevent duplicate counts when opening multiple tabs simultaneously
                $fingerprint = hash('xxh64', $request->ip().'|'.$userAgent);
                $debounceKey = 'vst_deb_'.$fingerprint;

                if (app()->runningUnitTests() || ! Cache::has($debounceKey)) {
                    if (! app()->runningUnitTests()) {
                        Cache::put($debounceKey, true, now()->addSeconds(5));
                    }

                    $visitId = (string) Str::uuid();
                    $session->put('visit_id', $visitId);
                    $session->put('visit_last_active', now()->timestamp);
                    $session->forget(['visit_srv_seen', 'visit_amc_seen']);

                    // Active visit session cookie (expires when browser is closed)
                    cookie()->queue(cookie('fix_visit_active', '1', 0));

                    if ($isService) {
                        $session->put('visit_srv_seen', true);
                    }
                    if ($isAmc) {
                        $session->put('visit_amc_seen', true);
                    }

                    DailyAnalytics::recordVisitor($isService, $isAmc, $isNew);
                } else {
                    $session->put('visit_last_active', now()->timestamp);
                }
            } else {
                // Continuing ongoing browsing session: update active timestamp
                $session->put('visit_last_active', now()->timestamp);

                // Track section engagement during this visit
                if ($isService || $isAmc) {
                    $record = DailyAnalytics::forDate();
                    if ($isService && ! $session->has('visit_srv_seen')) {
                        $session->put('visit_srv_seen', true);
                        $record->increment('service_visitors');
                    }
                    if ($isAmc && ! $session->has('visit_amc_seen')) {
                        $session->put('visit_amc_seen', true);
                        $record->increment('amc_visitors');
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently continue so analytics never break user traffic
        }

        return $response;
    }
}
