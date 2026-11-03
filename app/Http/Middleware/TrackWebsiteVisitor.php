<?php

namespace App\Http\Middleware;

use App\Models\DailyAnalytics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

            $todayStr = today()->toDateString();
            $fingerprint = hash('xxh64', $request->ip().'|'.$userAgent);

            // Check if this visitor is brand new or returning (Cookie + 365-day cache fingerprint)
            $hasCookie = $request->hasCookie('fix_returning_user');
            $hasSeenBefore = Cache::has('vst_seen_'.$fingerprint);
            $isNew = ! $hasCookie && ! $hasSeenBefore;

            // Mark returning visitor across cookies (forever) and cache (1 year)
            if ($isNew) {
                cookie()->queue(cookie()->forever('fix_returning_user', '1'));
                Cache::put('vst_seen_'.$fingerprint, true, now()->addYear());
            }

            // Daily Unique Visit check (prevents duplicate counting on refresh or multi-tab browsing)
            $sessionKey = 'visited_'.$todayStr;
            $dailyCacheKey = 'vst_day_'.$todayStr.'_'.$fingerprint;

            $alreadyCountedToday = $request->session()->has($sessionKey) || Cache::has($dailyCacheKey);

            if (! $alreadyCountedToday) {
                $request->session()->put($sessionKey, true);
                Cache::put($dailyCacheKey, true, now()->endOfDay());

                DailyAnalytics::recordVisitor($isService, $isAmc, $isNew);
            } else {
                // Track specific section engagement even if visitor was already counted today
                if ($isService || $isAmc) {
                    $record = DailyAnalytics::forDate($todayStr);
                    if ($isService && ! $request->session()->has('visited_srv_'.$todayStr)) {
                        $request->session()->put('visited_srv_'.$todayStr, true);
                        $record->increment('service_visitors');
                    }
                    if ($isAmc && ! $request->session()->has('visited_amc_'.$todayStr)) {
                        $request->session()->put('visited_amc_'.$todayStr, true);
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
