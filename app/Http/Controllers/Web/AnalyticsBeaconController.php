<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DailyAnalytics;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsBeaconController extends Controller
{
    /**
     * Ingest lightweight client-side analytics beacon for real human visitors.
     */
    public function ping(Request $request): JsonResponse
    {
        // 1. Bot & automated runner rejection
        $userAgent = strtolower($request->userAgent() ?? '');
        $isBot = (bool) preg_match('/bot|crawl|spider|slurp|google|bing|yandex|baidu|duckduckgo|facebookexternalhit|whatsapp|telegram|curl|wget|python|postman|headless|scanner|uptimerobot|censys|shodan|pingdom/i', $userAgent);

        if (! app()->runningUnitTests() && empty($userAgent)) {
            $isBot = true;
        }

        if ($isBot || $request->boolean('is_webdriver')) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $isNewVisit = $request->boolean('is_new_visit');
        $isNewUser = $request->boolean('is_new_user');
        $isService = $request->boolean('is_service');
        $isAmc = $request->boolean('is_amc');

        if (! $isNewVisit && ! $isService && ! $isAmc) {
            return response()->json(['status' => 'noop'], 200);
        }

        try {
            $record = DailyAnalytics::forDate();

            $updates = [];
            if ($isNewVisit) {
                $updates['total_visitors'] = DB::raw('total_visitors + 1');
                if ($isNewUser) {
                    $updates['new_visitors'] = DB::raw('new_visitors + 1');
                }
            }
            if ($isService) {
                $updates['service_visitors'] = DB::raw('service_visitors + 1');
            }
            if ($isAmc) {
                $updates['amc_visitors'] = DB::raw('amc_visitors + 1');
            }

            if (! empty($updates)) {
                DailyAnalytics::where('id', $record->id)->update($updates);
            }

            return response()->json(['status' => 'recorded'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error'], 200);
        }
    }
}
