<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Booking\UpdateBookingStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\DailyAnalytics;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display Admin Command Center dashboard with unified clean bar chart and date filters.
     */
    public function index(Request $request): View
    {
        $range = $request->query('range', 'today');
        if ($range === '7days') $range = 'week';
        if ($range === '30days') $range = 'month';

        $singleDateInput = $request->query('date') ?? $request->query('single_date');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');

        // Resolve active date range
        if ($singleDateInput && !$startDateInput) {
            $startDate = Carbon::parse($singleDateInput)->startOfDay();
            $endDate = Carbon::parse($singleDateInput)->endOfDay();
            $range = 'date';
        } elseif ($startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
            $range = 'custom';
        } elseif ($range === 'yesterday' || $range === 'day') {
            $startDate = Carbon::yesterday()->startOfDay();
            $endDate = Carbon::yesterday()->endOfDay();
            $range = 'yesterday';
        } elseif ($range === 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($range === 'week') {
            $startDate = Carbon::today()->subDays(6)->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($range === 'month') {
            $startDate = Carbon::today()->subDays(29)->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($range === 'year') {
            $startDate = Carbon::today()->startOfYear()->startOfDay();
            $endDate = Carbon::today()->endOfYear()->endOfDay();
        } else {
            // Default: today (Front-and-center daily operations)
            $range = 'today';
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        // Resolve previous period for comparison and day navigation
        if ($startDate->isSameDay($endDate)) {
            $prevStartDate = $startDate->copy()->subDay()->startOfDay();
            $prevEndDate = $startDate->copy()->subDay()->endOfDay();
            $prevDate = $startDate->copy()->subDay()->format('Y-m-d');
            $nextDate = $startDate->copy()->addDay()->format('Y-m-d');
            $prevPeriodLabel = 'Yesterday (' . $prevStartDate->format('M d, Y') . ')';
        } else {
            $diffInDays = $startDate->diffInDays($endDate) + 1;
            $prevEndDate = $startDate->copy()->subSecond();
            $prevStartDate = $startDate->copy()->subDays($diffInDays)->startOfDay();
            $prevDate = $startDate->copy()->subDays($diffInDays)->format('Y-m-d');
            $nextDate = $endDate->copy()->addDay()->format('Y-m-d');
            $prevPeriodLabel = 'Prior Period (' . $prevStartDate->format('M d') . ' – ' . $prevEndDate->format('M d, Y') . ')';
        }

        // Fetch pre-aggregated records for the selected period
        $analyticsRecords = DailyAnalytics::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(fn($item) => $item->date->format('Y-m-d'));

        $bookingsInRange = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with(['service.category', 'amcPlan', 'user'])
            ->get();

        $usersInRange = User::whereBetween('created_at', [$startDate, $endDate])->get();

        // 1. Current Period Metrics for each of the 10 dedicated Bars
        $currentTotalVisitors = (int) $analyticsRecords->sum('total_visitors');
        $currentNewVisitors = (int) $analyticsRecords->sum('new_visitors');
        if ($currentNewVisitors === 0 && $currentTotalVisitors > 0) {
            $currentNewVisitors = (int) round($currentTotalVisitors * 0.65);
        }

        $currentLogins = (int) $analyticsRecords->sum('logins_count');
        $currentRegs = max((int) $analyticsRecords->sum('registrations_count'), $usersInRange->count());

        $currentServiceVisitors = (int) $analyticsRecords->sum('service_visitors');
        $currentAmcVisitors = (int) $analyticsRecords->sum('amc_visitors');

        // Service Requests vs Service Book
        $serviceBookings = $bookingsInRange->filter(fn($b) => empty($b->amc_plan_id) && $b->service_type !== 'amc_contract');
        $currentServiceRequests = $serviceBookings->count();
        $currentServiceBook = $serviceBookings->filter(fn($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

        // AMC Requests vs AMC Book
        $amcBookings = $bookingsInRange->filter(fn($b) => !empty($b->amc_plan_id) || $b->service_type === 'amc_contract');
        $currentAmcRequests = $amcBookings->count();
        $currentAmcBook = $amcBookings->filter(fn($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

        // 2. Previous Period Metrics
        $prevAnalytics = DailyAnalytics::whereBetween('date', [$prevStartDate->toDateString(), $prevEndDate->toDateString()])->get();
        $prevBookings = Booking::whereBetween('created_at', [$prevStartDate, $prevEndDate])->get();
        $prevUsers = User::whereBetween('created_at', [$prevStartDate, $prevEndDate])->get();

        $prevTotalVisitors = (int) $prevAnalytics->sum('total_visitors');
        $prevNewVisitors = (int) $prevAnalytics->sum('new_visitors');
        if ($prevNewVisitors === 0 && $prevTotalVisitors > 0) {
            $prevNewVisitors = (int) round($prevTotalVisitors * 0.65);
        }

        $prevLogins = (int) $prevAnalytics->sum('logins_count');
        $prevRegs = max((int) $prevAnalytics->sum('registrations_count'), $prevUsers->count());

        $prevServiceVisitors = (int) $prevAnalytics->sum('service_visitors');
        $prevAmcVisitors = (int) $prevAnalytics->sum('amc_visitors');

        $prevServiceBookings = $prevBookings->filter(fn($b) => empty($b->amc_plan_id) && $b->service_type !== 'amc_contract');
        $prevServiceRequests = $prevServiceBookings->count();
        $prevServiceBook = $prevServiceBookings->filter(fn($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

        $prevAmcBookings = $prevBookings->filter(fn($b) => !empty($b->amc_plan_id) || $b->service_type === 'amc_contract');
        $prevAmcRequests = $prevAmcBookings->count();
        $prevAmcBook = $prevAmcBookings->filter(fn($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

        // Dedicated 10 Metric Bars Payload
        $metricChart = [
            'labels' => [
                'Total visitor',
                'New visitor',
                'Login',
                'Registration',
                'Service visitor',
                'Amc visitor',
                'Service Request',
                'Service Book',
                'AMC Request',
                'AMC Book',
            ],
            'values' => [
                $currentTotalVisitors,
                $currentNewVisitors,
                $currentLogins,
                $currentRegs,
                $currentServiceVisitors,
                $currentAmcVisitors,
                $currentServiceRequests,
                $currentServiceBook,
                $currentAmcRequests,
                $currentAmcBook,
            ],
            'previous_values' => [
                $prevTotalVisitors,
                $prevNewVisitors,
                $prevLogins,
                $prevRegs,
                $prevServiceVisitors,
                $prevAmcVisitors,
                $prevServiceRequests,
                $prevServiceBook,
                $prevAmcRequests,
                $prevAmcBook,
            ],
            'details' => [
                'total_visitors' => $currentTotalVisitors,
                'new_visitors' => $currentNewVisitors,
                'logins' => $currentLogins,
                'registrations' => $currentRegs,
                'service_visitors' => $currentServiceVisitors,
                'amc_visitors' => $currentAmcVisitors,
                'service_requests' => $currentServiceRequests,
                'service_book' => $currentServiceBook,
                'amc_requests' => $currentAmcRequests,
                'amc_book' => $currentAmcBook,
            ],
            'prev_label' => $prevPeriodLabel,
            'prev_date' => $prevDate,
            'next_date' => $nextDate,
        ];

        // Summary Statistics for the Active Period
        $stats = [
            'range' => $range,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'single_date' => $startDate->isSameDay($endDate) ? $startDate->format('Y-m-d') : null,
            'prev_date' => $prevDate,
            'next_date' => $nextDate,
            'range_label' => match ($range) {
                'yesterday' => 'Yesterday (' . Carbon::yesterday()->format('M d, Y') . ')',
                'today' => 'Today (' . Carbon::today()->format('M d, Y') . ')',
                'date' => 'Date: ' . $startDate->format('M d, Y'),
                'week' => 'Week (' . $startDate->format('M d') . ' – ' . $endDate->format('M d, Y') . ')',
                'month' => 'Month (' . $startDate->format('M d') . ' – ' . $endDate->format('M d, Y') . ')',
                'year' => 'Year (' . Carbon::today()->year . ')',
                default => 'Custom: ' . $startDate->format('M d, Y') . ' – ' . $endDate->format('M d, Y'),
            },

            // Traffic & Visitors
            'total_visitors' => $currentTotalVisitors,
            'new_visitors' => $currentNewVisitors,
            'service_visitors' => $currentServiceVisitors,
            'amc_visitors' => $currentAmcVisitors,
            'service_amc_visitors' => $currentServiceVisitors + $currentAmcVisitors,

            // Logins & Registrations
            'total_logins' => $currentLogins,
            'total_registrations' => $currentRegs,
            'login_registration' => $currentLogins + $currentRegs,

            // Bookings & Work Orders
            'service_requests' => $currentServiceRequests,
            'service_orders' => $currentServiceBook,
            'amc_requests' => $currentAmcRequests,
            'amc_orders' => $currentAmcBook,
            'period_bookings' => $bookingsInRange->count(),
            'period_revenue' => (float) $bookingsInRange->sum(fn($b) => $b->final_cost ?? $b->estimated_cost ?? 0),

            // Operations State
            'pending_review' => Booking::where('status', 'pending_review')->count(),
            'active_in_progress' => Booking::whereIn('status', ['accepted', 'assigned', 'in_progress'])->count(),
            'emergency_orders' => $bookingsInRange->whereIn('urgency', ['emergency_1h', 'urgent_4h'])->count(),
            'completed_jobs' => $bookingsInRange->where('status', 'completed')->count(),
            'all_time_bookings' => Booking::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'period_processing' => $bookingsInRange->whereIn('status', ['accepted', 'assigned', 'in_progress'])->count(),
            'period_pending' => $bookingsInRange->where('status', 'pending_review')->count(),
        ];

        // Service category share
        $categoryBreakdown = $bookingsInRange
            ->groupBy(fn($b) => $b->service?->category?->name ?? ($b->amcPlan ? 'AMC Packages' : 'General Facility'))
            ->map(fn($group) => $group->count())
            ->sortDesc();

        // Area hotspot distribution in Dhaka
        $areaHotspots = $bookingsInRange
            ->groupBy('area')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(6);

        // Recent work orders in period
        $recentBookings = $bookingsInRange->isNotEmpty()
            ? $bookingsInRange->sortByDesc('created_at')->take(8)
            : Booking::with(['user', 'service', 'amcPlan'])->latest()->take(8)->get();

        $unreadNotifications = AdminNotification::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'stats',
            'metricChart',
            'categoryBreakdown',
            'areaHotspots',
            'recentBookings',
            'unreadNotifications'
        ));
    }

    /**
     * Polling endpoint to check for incoming notifications with audible sound trigger.
     */
    public function checkNewAlerts(): JsonResponse
    {
        $unplayed = AdminNotification::where('sound_played', false)
            ->latest()
            ->get();

        $unreadCount = AdminNotification::where('is_read', false)->count();

        if ($unplayed->isNotEmpty()) {
            AdminNotification::whereIn('id', $unplayed->pluck('id'))->update(['sound_played' => true]);
        }

        return response()->json([
            'has_sound' => $unplayed->isNotEmpty(),
            'new_count' => $unplayed->count(),
            'unread_total' => $unreadCount,
            'alerts' => $unplayed->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'type' => $n->type,
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Backwards-compatible delegation to AdminBookingController.
     */
    public function bookings(Request $request): View
    {
        return app(AdminBookingController::class)->index($request);
    }

    public function showBooking(Booking $booking): View
    {
        return app(AdminBookingController::class)->show($booking);
    }

    public function updateBookingStatus(Booking $booking, UpdateBookingStatusRequest $request, UpdateBookingStatusAction $action): RedirectResponse
    {
        return app(AdminBookingController::class)->updateStatus($booking, $request, $action);
    }
}
