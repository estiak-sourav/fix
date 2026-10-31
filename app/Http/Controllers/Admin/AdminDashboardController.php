<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Booking\UpdateBookingStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\AdminNotification;
use App\Models\AmcInvoice;
use App\Models\AmcPlan;
use App\Models\Booking;
use App\Models\DailyAnalytics;
use App\Models\OutreachTask;
use App\Models\Service;
use App\Models\SupportTicket;
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
        if ($range === '7days') {
            $range = 'week';
        }
        if ($range === '30days') {
            $range = 'month';
        }

        $singleDateInput = $request->query('date') ?? $request->query('single_date');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');

        // Resolve active date range
        if ($singleDateInput && ! $startDateInput) {
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
            $prevPeriodLabel = 'Yesterday ('.$prevStartDate->format('M d, Y').')';
        } else {
            $diffInDays = $startDate->diffInDays($endDate) + 1;
            $prevEndDate = $startDate->copy()->subSecond();
            $prevStartDate = $startDate->copy()->subDays($diffInDays)->startOfDay();
            $prevDate = $startDate->copy()->subDays($diffInDays)->format('Y-m-d');
            $nextDate = $endDate->copy()->addDay()->format('Y-m-d');
            $prevPeriodLabel = 'Prior Period ('.$prevStartDate->format('M d').' – '.$prevEndDate->format('M d, Y').')';
        }

        // Fetch pre-aggregated records for the selected period
        $analyticsRecords = DailyAnalytics::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(fn ($item) => $item->date->format('Y-m-d'));

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
        $serviceBookings = $bookingsInRange->filter(fn ($b) => empty($b->amc_plan_id) && $b->service_type !== 'amc_contract');
        $currentServiceRequests = $serviceBookings->count();
        $currentServiceBook = $serviceBookings->filter(fn ($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

        // AMC Requests vs AMC Book
        $amcBookings = $bookingsInRange->filter(fn ($b) => ! empty($b->amc_plan_id) || $b->service_type === 'amc_contract');
        $currentAmcRequests = $amcBookings->count();
        $currentAmcBook = $amcBookings->filter(fn ($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

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

        $prevServiceBookings = $prevBookings->filter(fn ($b) => empty($b->amc_plan_id) && $b->service_type !== 'amc_contract');
        $prevServiceRequests = $prevServiceBookings->count();
        $prevServiceBook = $prevServiceBookings->filter(fn ($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

        $prevAmcBookings = $prevBookings->filter(fn ($b) => ! empty($b->amc_plan_id) || $b->service_type === 'amc_contract');
        $prevAmcRequests = $prevAmcBookings->count();
        $prevAmcBook = $prevAmcBookings->filter(fn ($b) => in_array($b->status, ['accepted', 'assigned', 'in_progress', 'completed']))->count();

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

        // Running AMC Contracts count
        $runningAmcContracts = Booking::where(function ($q) {
            $q->where('service_type', 'amc_contract')
                ->orWhereNotNull('amc_plan_id');
        })->where('contract_status', 'active')
            ->where(function ($q) {
                $q->whereNull('contract_end_date')
                    ->orWhereDate('contract_end_date', '>=', today());
            })->get();
        $runningAmcCount = $runningAmcContracts->count();

        // Calculate AMC Monthly Recurring Revenue (MRR)
        $amcMrr = (float) $runningAmcContracts->sum(function ($c) {
            $amount = (float) ($c->recurring_amount ?: $c->final_cost ?: 0);

            return match ($c->billing_cycle) {
                'daily' => $amount * 30,
                'weekly' => $amount * 4.33,
                'half_monthly' => $amount * 2,
                'quarterly' => $amount / 3,
                'yearly' => $amount / 12,
                default => $amount,
            };
        });

        // AMC Bill Collection Alert (Due or Overdue Invoices)
        $amcBillsDue = AmcInvoice::with(['booking.user', 'booking.amcPlan'])
            ->where('status', '!=', 'paid')
            ->where(function ($q) {
                $q->where('status', 'overdue')
                    ->orWhere('due_date', '<=', today()->addDays(7));
            })
            ->orderBy('due_date')
            ->get();

        $amcCollectionAlert = [
            'has_due' => $amcBillsDue->isNotEmpty(),
            'count' => $amcBillsDue->count(),
            'total_amount' => (float) $amcBillsDue->sum('amount'),
            'invoices' => $amcBillsDue->take(4),
        ];

        // Period Revenue calculation: Regular Completed Services + Paid AMC Bills in Range
        $regularServiceRevenue = (float) $bookingsInRange->filter(fn ($b) => ! $b->is_amc)->where('status', 'completed')->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0);
        $amcCollectedInPeriod = (float) AmcInvoice::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');
        if ($amcCollectedInPeriod === 0.0) {
            $amcCollectedInPeriod = (float) $bookingsInRange->filter(fn ($b) => $b->is_amc && $b->contract_status === 'active')->sum(fn ($b) => $b->recurring_amount ?: $b->final_cost ?: 0);
        }
        $periodRevenue = $regularServiceRevenue + $amcCollectedInPeriod;

        // Summary Statistics for the Active Period
        $stats = [
            'range' => $range,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'single_date' => $startDate->isSameDay($endDate) ? $startDate->format('Y-m-d') : null,
            'prev_date' => $prevDate,
            'next_date' => $nextDate,
            'range_label' => match ($range) {
                'yesterday' => 'Yesterday ('.Carbon::yesterday()->format('M d, Y').')',
                'today' => 'Today ('.Carbon::today()->format('M d, Y').')',
                'date' => 'Date: '.$startDate->format('M d, Y'),
                'week' => 'Week ('.$startDate->format('M d').' – '.$endDate->format('M d, Y').')',
                'month' => 'Month ('.$startDate->format('M d').' – '.$endDate->format('M d, Y').')',
                'year' => 'Year ('.Carbon::today()->year.')',
                default => 'Custom: '.$startDate->format('M d, Y').' – '.$endDate->format('M d, Y'),
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
            'running_amc_contracts' => $runningAmcCount,
            'amc_mrr' => $amcMrr,
            'period_bookings' => $bookingsInRange->count(),
            'period_revenue' => $periodRevenue,

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
            ->groupBy(fn ($b) => $b->service?->category?->name ?? ($b->amcPlan ? 'AMC Packages' : 'General Facility'))
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        // Regional Operations Hotspots (Countrywide / Filterable by Division)
        $selectedHotspotDivision = $request->query('hotspot_division', 'all');

        $hotspotsBase = $bookingsInRange->filter(fn ($b) => ! empty($b->area));

        if ($selectedHotspotDivision && $selectedHotspotDivision !== 'all') {
            $hotspotsBase = $hotspotsBase->filter(fn ($b) => strcasecmp((string) $b->division, (string) $selectedHotspotDivision) === 0);
        }

        $areaHotspots = $hotspotsBase
            ->groupBy('area')
            ->map(function ($group, $areaName) {
                $first = $group->first();

                return [
                    'area' => $areaName,
                    'district' => $first->district ?? '',
                    'division' => $first->division ?? '',
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(8);

        // Division breakdown across Bangladesh in the active period
        $divisionCounts = $bookingsInRange
            ->filter(fn ($b) => ! empty($b->division))
            ->groupBy('division')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        // Recent work orders in period
        $recentBookings = $bookingsInRange->isNotEmpty()
            ? $bookingsInRange->sortByDesc('created_at')->take(8)
            : Booking::with(['user', 'service', 'amcPlan'])->latest()->take(8)->get();

        $unreadNotifications = AdminNotification::latest()->take(6)->get();

        // 3. Earning analytics per month (Real database bookings & revenue)
        $currentYear = Carbon::today()->year;
        $currentMonth = Carbon::today()->month;

        $currentYearBookings = Booking::with(['service.category', 'amcPlan'])
            ->whereYear('created_at', $currentYear)
            ->get();

        $prevYearDecRevenue = (float) Booking::whereYear('created_at', $currentYear - 1)
            ->whereMonth('created_at', 12)
            ->get()
            ->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0);

        $bookingsByMonth = $currentYearBookings->groupBy(fn ($b) => (int) $b->created_at->format('n'));

        $earningMonthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthBookings = $bookingsByMonth->get($m, collect());
            $thisMonthRev = (float) $monthBookings->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0);

            if ($m === 1) {
                $prevMonthRev = $prevYearDecRevenue;
            } else {
                $prevMonthBookings = $bookingsByMonth->get($m - 1, collect());
                $prevMonthRev = (float) $prevMonthBookings->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0);
            }

            if ($prevMonthRev > 0) {
                $diff = $thisMonthRev - $prevMonthRev;
                $growthPct = ($diff / $prevMonthRev) * 100;
                $growthStr = ($growthPct >= 0 ? '+ ' : '- ').number_format(abs($growthPct), 1).'%';
            } elseif ($thisMonthRev > 0) {
                $growthStr = '+ 100.0%';
            } else {
                $growthStr = '0.0%';
            }

            $daysInMonth = Carbon::createFromDate($currentYear, $m, 1)->daysInMonth;
            $bucketValues = array_fill(0, 12, 0);
            foreach ($monthBookings as $b) {
                $day = (int) $b->created_at->format('j');
                $bucketIndex = min(11, (int) floor((($day - 1) / $daysInMonth) * 12));
                $bucketValues[$bucketIndex] += (float) ($b->final_cost ?? $b->estimated_cost ?? 0);
            }

            $earningMonthlyData[$m] = [
                'thisMonth' => '৳ '.number_format($thisMonthRev, 2),
                'thisMonthRaw' => $thisMonthRev,
                'lastMonth' => '৳ '.number_format($prevMonthRev, 2),
                'lastMonthRaw' => $prevMonthRev,
                'growth' => $growthStr,
                'values' => $bucketValues,
            ];
        }

        // 4. Sales analytics (Category revenue share & distribution)
        $salesPool = $bookingsInRange->filter(fn ($b) => ($b->final_cost ?? $b->estimated_cost ?? 0) > 0);
        if ($salesPool->isEmpty()) {
            $salesPool = $currentYearBookings->filter(fn ($b) => ($b->final_cost ?? $b->estimated_cost ?? 0) > 0);
        }
        if ($salesPool->isEmpty()) {
            $salesPool = Booking::with(['service.category', 'amcPlan'])
                ->get()
                ->filter(fn ($b) => ($b->final_cost ?? $b->estimated_cost ?? 0) > 0);
        }

        $categorySales = $salesPool
            ->groupBy(fn ($b) => $b->service?->category?->name ?? ($b->amcPlan ? 'AMC Contracts' : 'General Maintenance'))
            ->map(fn ($group) => (float) $group->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0))
            ->filter(fn ($rev) => $rev > 0)
            ->sortDesc();

        $totalSales = (float) $categorySales->sum();
        $palette = ['#4361ee', '#2ec4b6', '#ff5c5c', '#f59e0b', '#8b5cf6'];
        $salesCategories = [];

        if ($totalSales > 0) {
            if ($categorySales->count() <= 3) {
                $idx = 0;
                foreach ($categorySales as $name => $amount) {
                    $pct = round(($amount / $totalSales) * 100, 1);
                    $salesCategories[] = [
                        'name' => $name,
                        'amount' => $amount,
                        'amount_formatted' => '৳ '.number_format($amount, 0),
                        'percentage' => $pct,
                        'color' => $palette[$idx % count($palette)],
                    ];
                    $idx++;
                }
            } else {
                $top2 = $categorySales->take(2);
                $othersSum = (float) $categorySales->skip(2)->sum();

                $idx = 0;
                foreach ($top2 as $name => $amount) {
                    $pct = round(($amount / $totalSales) * 100, 1);
                    $salesCategories[] = [
                        'name' => $name,
                        'amount' => $amount,
                        'amount_formatted' => '৳ '.number_format($amount, 0),
                        'percentage' => $pct,
                        'color' => $palette[$idx],
                    ];
                    $idx++;
                }

                $otherPct = round(($othersSum / $totalSales) * 100, 1);
                $salesCategories[] = [
                    'name' => 'Other Services',
                    'amount' => $othersSum,
                    'amount_formatted' => '৳ '.number_format($othersSum, 0),
                    'percentage' => $otherPct,
                    'color' => $palette[2],
                ];
            }
        } else {
            $salesCategories = [
                [
                    'name' => 'No Sales Yet',
                    'amount' => 0.0,
                    'amount_formatted' => '৳ 0',
                    'percentage' => 0.0,
                    'color' => '#cbd5e1',
                ],
            ];
        }

        $salesAnalytics = [
            'total' => $totalSales,
            'total_formatted' => '৳ '.number_format($totalSales, 0),
            'categories' => $salesCategories,
            'labels' => array_column($salesCategories, 'name'),
            'data' => array_column($salesCategories, 'percentage'),
            'colors' => array_column($salesCategories, 'color'),
        ];

        // 4. Outreach Tasks (Automatic unreachable/callback + Manual tasks)
        // Self-heal/sync: ensure any pending_review bookings with unreachable/call_back_later have an OutreachTask
        $legacyUnsyncedBookings = Booking::whereIn('call_status', ['unreachable', 'call_back_later'])
            ->where('status', 'pending_review')
            ->whereDoesntHave('outreachTasks', fn ($q) => $q->where('status', 'pending'))
            ->get();

        foreach ($legacyUnsyncedBookings as $legacyBooking) {
            $type = $legacyBooking->call_status === 'unreachable' ? 'call_unreachable' : 'call_back_later';
            $title = ($legacyBooking->call_status === 'unreachable' ? 'Retry Call: ' : 'Follow-up Call: ').$legacyBooking->customer_name;
            OutreachTask::create([
                'booking_id' => $legacyBooking->id,
                'title' => $title,
                'task_type' => $type,
                'customer_name' => $legacyBooking->customer_name,
                'customer_phone' => $legacyBooking->customer_phone,
                'priority' => $legacyBooking->urgency === 'emergency_1h' ? 'urgent' : 'high',
                'status' => 'pending',
                'due_at' => now()->addMinutes(30),
                'notes' => $legacyBooking->call_notes,
                'is_automated' => true,
            ]);
        }

        $outreachTasks = OutreachTask::with(['booking.service', 'user', 'createdByAdmin', 'completedByAdmin'])
            ->where('status', 'pending')
            ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 WHEN 'normal' THEN 3 ELSE 4 END")
            ->orderBy('due_at', 'asc')
            ->take(12)
            ->get();

        $recentPendingBookings = Booking::where('status', 'pending_review')->latest()->take(20)->get();
        $taskBookings = Booking::with(['service', 'user'])->latest()->take(60)->get();
        $taskUsers = User::latest()->take(60)->get(['id', 'name', 'email', 'phone', 'default_address', 'division', 'district', 'area', 'created_at']);

        // 5. Dynamic Top Selling Services & AMC Packages (Item 8)
        $popularServices = Service::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();
        $popularAmc = AmcPlan::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(2)
            ->get();

        $topItems = collect();
        foreach ($popularServices as $srv) {
            $topItems->push((object) [
                'name' => $srv->title,
                'subtitle' => 'On-Demand Service',
                'orders_count' => $srv->bookings_count,
                'type' => 'Service',
            ]);
        }
        foreach ($popularAmc as $plan) {
            $topItems->push((object) [
                'name' => $plan->name,
                'subtitle' => 'AMC Contract Plan',
                'orders_count' => $plan->bookings_count,
                'type' => 'AMC Package',
            ]);
        }
        $totalOrdersCount = max($topItems->sum('orders_count'), 1);
        $itemPalette = ['#4361ee', '#2ec4b6', '#ff5c5c', '#f59e0b', '#8b5cf6'];
        $idx = 0;
        $topSellingDynamic = $topItems->map(function ($it) use ($totalOrdersCount, $itemPalette, &$idx) {
            $pct = round(($it->orders_count / $totalOrdersCount) * 100);
            $color = $itemPalette[$idx % count($itemPalette)];
            $idx++;
            $it->pct = $pct;
            $it->color = $color;

            return $it;
        });

        // 6. Latest Support Ticket for Dashboard Chat Widget (Item 9)
        $latestSupportTicket = SupportTicket::with(['messages', 'user'])
            ->latest('updated_at')
            ->first();

        return view('admin.dashboard', compact(
            'stats',
            'metricChart',
            'categoryBreakdown',
            'areaHotspots',
            'selectedHotspotDivision',
            'divisionCounts',
            'recentBookings',
            'unreadNotifications',
            'earningMonthlyData',
            'salesAnalytics',
            'currentMonth',
            'outreachTasks',
            'recentPendingBookings',
            'taskBookings',
            'taskUsers',
            'topSellingDynamic',
            'latestSupportTicket',
            'amcCollectionAlert'
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
            'alerts' => $unplayed->map(fn ($n) => [
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

    /**
     * Display comprehensive area-level operations and hotspot analytics directory.
     */
    public function areaAnalytics(Request $request): View
    {
        $selectedDivision = $request->query('division', 'all');
        $search = trim((string) $request->query('search', ''));
        $range = $request->query('range', 'all_time');

        $query = Booking::with(['service.category', 'amcPlan', 'user'])
            ->whereNotNull('area')
            ->where('area', '!=', '');

        // Date range filter
        if ($range === 'today') {
            $query->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);
        } elseif ($range === 'week') {
            $query->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()]);
        } elseif ($range === 'month') {
            $query->whereBetween('created_at', [Carbon::today()->subDays(29)->startOfDay(), Carbon::today()->endOfDay()]);
        }

        // Division filter
        if ($selectedDivision && $selectedDivision !== 'all') {
            $query->where('division', $selectedDivision);
        }

        // Search filter
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('area', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%");
            });
        }

        $allBookings = $query->latest()->get();

        // Aggregate statistics per area
        $areasCollection = $allBookings
            ->groupBy('area')
            ->map(function ($group, $areaName) {
                $first = $group->first();
                $totalCount = $group->count();
                $pendingCount = $group->where('status', 'pending_review')->count();
                $activeCount = $group->whereIn('status', ['accepted', 'assigned', 'in_progress'])->count();
                $completedCount = $group->where('status', 'completed')->count();
                $emergencyCount = $group->whereIn('urgency', ['emergency_1h', 'urgent_4h', 'same_day_4h'])->count();
                $totalRevenue = (float) $group->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0);

                // Most popular service in area
                $topService = $group->groupBy(fn ($b) => $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service'))
                    ->sortByDesc(fn ($sGroup) => $sGroup->count())
                    ->keys()
                    ->first() ?? 'General Facility Maintenance';

                // Recent 3 bookings in this area
                $recentOrders = $group->sortByDesc('created_at')->take(3);

                return [
                    'area' => $areaName,
                    'district' => $first->district ?? 'Not specified',
                    'division' => $first->division ?? 'Not specified',
                    'total_count' => $totalCount,
                    'pending_count' => $pendingCount,
                    'active_count' => $activeCount,
                    'completed_count' => $completedCount,
                    'emergency_count' => $emergencyCount,
                    'total_revenue' => $totalRevenue,
                    'top_service' => $topService,
                    'latest_booking_at' => $group->max('created_at'),
                    'recent_orders' => $recentOrders,
                ];
            })
            ->sortByDesc('total_count');

        // High-level KPI metrics
        $kpis = [
            'total_areas' => $areasCollection->count(),
            'total_bookings' => $allBookings->count(),
            'top_hotspot' => $areasCollection->first()['area'] ?? 'None',
            'top_hotspot_count' => $areasCollection->first()['total_count'] ?? 0,
            'emergency_total' => $allBookings->whereIn('urgency', ['emergency_1h', 'urgent_4h', 'same_day_4h'])->count(),
            'total_revenue' => (float) $allBookings->sum(fn ($b) => $b->final_cost ?? $b->estimated_cost ?? 0),
        ];

        // Divisions count for filter tabs
        $divisionList = Booking::whereNotNull('division')
            ->where('division', '!=', '')
            ->groupBy('division')
            ->selectRaw('division, count(*) as count')
            ->pluck('count', 'division')
            ->toArray();

        return view('admin.analytics.area-details', compact(
            'areasCollection',
            'kpis',
            'divisionList',
            'selectedDivision',
            'search',
            'range'
        ));
    }

    /**
     * Display dedicated revenue and earnings intelligence details directory.
     */
    public function earnings(Request $request): View
    {
        $range = $request->query('range', 'all_time');
        $division = $request->query('division', 'all');
        $serviceId = $request->query('service_id', 'all');
        $paymentStatus = $request->query('payment_status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Booking::with(['service.category', 'amcPlan', 'user'])
            ->where(function ($q) {
                $q->where('final_cost', '>', 0)
                    ->orWhere('estimated_cost', '>', 0);
            });

        // Time filter
        if ($range === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($range === 'week') {
            $query->where('created_at', '>=', Carbon::today()->subDays(6));
        } elseif ($range === 'month') {
            $query->where('created_at', '>=', Carbon::today()->subDays(29));
        } elseif ($range === 'year') {
            $query->where('created_at', '>=', Carbon::today()->startOfYear());
        }

        // Division filter
        if ($division !== 'all') {
            $query->where('division', $division);
        }

        // Service filter
        if ($serviceId !== 'all') {
            $query->where('service_id', $serviceId);
        }

        // Search filter
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        // Cloned query for overall KPIs & aggregates
        $allOrders = (clone $query)->get();

        $totalRevenue = (float) $allOrders->sum(fn ($b) => $b->final_cost > 0 ? $b->final_cost : ($b->estimated_cost ?: 0));
        $invoicedRevenue = (float) $allOrders->where('status', 'completed')->sum(fn ($b) => $b->final_cost ?: ($b->estimated_cost ?: 0));
        $pendingPipeline = (float) $allOrders->whereIn('status', ['pending_review', 'accepted', 'assigned', 'in_progress'])->sum(fn ($b) => $b->estimated_cost ?: ($b->final_cost ?: 0));
        $avgOrderValue = $allOrders->count() > 0 ? round($totalRevenue / $allOrders->count(), 2) : 0;

        // Breakdown by Service
        $revenueByService = $allOrders
            ->groupBy(fn ($b) => $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service'))
            ->map(fn ($group, $title) => [
                'title' => $title,
                'count' => $group->count(),
                'total' => (float) $group->sum(fn ($b) => $b->final_cost > 0 ? $b->final_cost : ($b->estimated_cost ?: 0)),
            ])
            ->sortByDesc('total')
            ->take(5);

        // Breakdown by Division
        $revenueByDivision = $allOrders
            ->whereNotNull('division')
            ->groupBy('division')
            ->map(fn ($group, $div) => [
                'division' => $div,
                'count' => $group->count(),
                'total' => (float) $group->sum(fn ($b) => $b->final_cost > 0 ? $b->final_cost : ($b->estimated_cost ?: 0)),
            ])
            ->sortByDesc('total');

        $bookings = $query->latest()->paginate(15)->withQueryString();

        $servicesList = Service::where('is_active', true)->orderBy('title')->get(['id', 'title']);

        return view('admin.analytics.earnings', compact(
            'bookings',
            'totalRevenue',
            'invoicedRevenue',
            'pendingPipeline',
            'avgOrderValue',
            'revenueByService',
            'revenueByDivision',
            'range',
            'division',
            'serviceId',
            'paymentStatus',
            'search',
            'servicesList'
        ));
    }
}
