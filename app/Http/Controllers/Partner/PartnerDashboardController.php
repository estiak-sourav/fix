<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\AmcShift;
use App\Models\Booking;
use App\Services\NotificationDispatcher;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerDashboardController extends Controller
{
    /**
     * Display Partner Command Center & Dispatch Workspace.
     */
    public function index(): View
    {
        $partner = Auth::guard('partner')->user();
        $partner->load('payouts');

        $allPartnerBookings = Booking::where('partner_id', $partner->id)
            ->with(['service', 'amcPlan', 'assignedCrew', 'user'])
            ->latest()
            ->get();

        $partner->setRelation('bookings', $allPartnerBookings);

        // All AMC Shifts assigned to this partner (either directly or via AMC booking)
        $allPartnerAmcShifts = AmcShift::where(function ($q) use ($partner) {
            $q->where('partner_id', $partner->id)
                ->orWhere(function ($q2) use ($partner) {
                    $q2->whereNull('partner_id')
                        ->whereHas('booking', fn ($b) => $b->where('partner_id', $partner->id));
                });
        })
            ->with(['booking.user', 'booking.amcPlan', 'assignedCrew'])
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // 1. TODAY'S SCHEDULE (Regular Service Bookings & Scheduled AMC Routine Shifts)
        $todayBookings = $allPartnerBookings->filter(function ($b) {
            // AMC routine schedules are represented by their specific AmcShifts
            if ($b->is_amc) {
                return false;
            }

            if (! in_array($b->partner_job_status, ['assigned', 'accepted', 'on_the_way', 'in_progress']) && $b->status !== 'confirmed') {
                return false;
            }

            return ($b->preferred_date && $b->preferred_date->isToday())
                || ($b->estimated_arrival_at && $b->estimated_arrival_at->isToday());
        });

        $todayAmcShifts = $allPartnerAmcShifts->filter(function ($s) {
            return $s->scheduled_date && $s->scheduled_date->isToday()
                && in_array($s->status, ['scheduled', 'in_progress', 'on_the_way', 'reassignment_needed']);
        });

        $todaySchedule = collect();

        foreach ($todayBookings as $b) {
            $todaySchedule->push([
                'id' => 'booking_'.$b->id,
                'item_type' => 'booking',
                'is_amc' => false,
                'booking' => $b,
                'shift' => null,
                'badge_label' => 'Service Order',
                'badge_class' => 'p-badge-info',
                'title' => $b->service?->title ?? 'Service Booking',
                'reference' => '#'.$b->booking_number,
                'client_name' => $b->customer_name,
                'client_phone' => $b->customer_phone,
                'area' => $b->area,
                'address' => $b->service_address ?: $b->address,
                'time_slot' => $b->arrival_window ?: ($b->preferred_time_slot ?: 'Today'),
                'status' => $b->partner_job_status,
                'url' => route('partner.jobs.show', $b),
            ]);
        }

        foreach ($todayAmcShifts as $s) {
            $shiftPayout = (float) ($s->payout_amount ?: ($s->booking?->payout_per_shift ?: 0));
            $todaySchedule->push([
                'id' => 'shift_'.$s->id,
                'item_type' => 'amc_shift',
                'is_amc' => true,
                'booking' => $s->booking,
                'shift' => $s,
                'badge_label' => 'Shift #'.$s->shift_number,
                'badge_class' => $s->status === 'in_progress' ? 'p-badge-success' : 'p-badge-purple',
                'title' => $s->title ?: ($s->booking?->amcPlan?->name ?? 'Routine Maintenance Shift'),
                'reference' => '#'.($s->booking?->booking_number ?? 'AMC').' • Shift #'.$s->shift_number,
                'client_name' => $s->booking?->customer_name ?? 'Client',
                'client_phone' => $s->booking?->customer_phone ?? '',
                'area' => $s->booking?->area ?? '',
                'address' => $s->booking?->service_address ?: $s->booking?->address,
                'time_slot' => $s->arrival_window ?: ($s->scheduled_time_slot ?: 'Today'),
                'status' => $s->status,
                'payout' => $shiftPayout,
                'url' => route('partner.jobs.show', $s->booking).'#tab-amc',
            ]);
        }

        // Backward compatibility for todayJobs
        $todayJobs = $todayBookings;

        // 2. UPCOMING SCHEDULE (NEXT 3 DAYS ONLY)
        $upcomingStart = Carbon::tomorrow()->startOfDay();
        $upcomingEnd = Carbon::today()->addDays(3)->endOfDay();

        $upcomingBookings = $allPartnerBookings->filter(function ($b) use ($upcomingStart, $upcomingEnd) {
            // AMC routine schedules are represented by their specific AmcShifts
            if ($b->is_amc) {
                return false;
            }

            if (! in_array($b->partner_job_status, ['assigned', 'accepted', 'on_the_way', 'in_progress']) && $b->status !== 'confirmed') {
                return false;
            }

            return $b->preferred_date && $b->preferred_date->betweenIncluded($upcomingStart, $upcomingEnd);
        });

        $upcomingNext3DaysShifts = $allPartnerAmcShifts->filter(function ($s) use ($upcomingStart, $upcomingEnd) {
            return $s->scheduled_date
                && $s->scheduled_date->betweenIncluded($upcomingStart, $upcomingEnd)
                && in_array($s->status, ['scheduled', 'in_progress', 'on_the_way', 'reassignment_needed']);
        });

        $upcomingThreeDaysSchedule = collect();

        foreach ($upcomingBookings as $b) {
            $upcomingThreeDaysSchedule->push([
                'id' => 'booking_'.$b->id,
                'item_type' => 'booking',
                'is_amc' => (bool) $b->is_amc,
                'date' => $b->preferred_date,
                'date_label' => $b->preferred_date->isTomorrow() ? 'Tomorrow ('.$b->preferred_date->format('M d').')' : $b->preferred_date->format('D, M d'),
                'booking' => $b,
                'shift' => null,
                'title' => $b->service?->title ?? $b->amcPlan?->name ?? 'Service Booking',
                'reference' => '#'.$b->booking_number,
                'client_name' => $b->customer_name,
                'client_phone' => $b->customer_phone,
                'area' => $b->area,
                'time_slot' => $b->arrival_window ?: ($b->preferred_time_slot ?: 'Routine Slot'),
                'status' => $b->partner_job_status,
                'url' => route('partner.jobs.show', $b),
            ]);
        }

        foreach ($upcomingNext3DaysShifts as $s) {
            $shiftPayout = (float) ($s->payout_amount ?: ($s->booking?->payout_per_shift ?: 0));
            $upcomingThreeDaysSchedule->push([
                'id' => 'shift_'.$s->id,
                'item_type' => 'amc_shift',
                'is_amc' => true,
                'date' => $s->scheduled_date,
                'date_label' => $s->scheduled_date->isTomorrow() ? 'Tomorrow ('.$s->scheduled_date->format('M d').')' : $s->scheduled_date->format('D, M d'),
                'booking' => $s->booking,
                'shift' => $s,
                'title' => $s->title ?: ($s->booking?->amcPlan?->name ?? 'Routine Maintenance Shift'),
                'reference' => '#'.($s->booking?->booking_number ?? 'AMC').' • Shift #'.$s->shift_number,
                'client_name' => $s->booking?->customer_name ?? 'Client',
                'client_phone' => $s->booking?->customer_phone ?? '',
                'area' => $s->booking?->area ?? '',
                'time_slot' => $s->arrival_window ?: ($s->scheduled_time_slot ?: 'Routine Slot'),
                'status' => $s->status,
                'payout' => $shiftPayout,
                'url' => route('partner.jobs.show', $s->booking).'#tab-amc',
            ]);
        }

        $upcomingThreeDaysSchedule = $upcomingThreeDaysSchedule->sortBy(function ($item) {
            return $item['date']->format('Y-m-d').' '.($item['time_slot'] ?? '');
        })->values();

        // 3. ACTIVE WORK (Regular Service Orders + Active AMC Facility Work)
        $activeServiceOrders = $allPartnerBookings->filter(function ($b) {
            return ! $b->is_amc && in_array($b->partner_job_status, ['assigned', 'accepted', 'on_the_way', 'in_progress']);
        });

        $activeAmcContracts = $allPartnerBookings->filter(function ($b) {
            return $b->is_amc
                && in_array($b->contract_status, ['active', 'in_progress', 'scheduled', 'pending'])
                && $b->status !== 'cancelled'
                && $b->partner_job_status !== 'declined';
        });

        // Combined active jobs for backward compatibility
        $activeJobs = $allPartnerBookings->filter(function ($b) {
            return in_array($b->partner_job_status, ['assigned', 'accepted', 'on_the_way', 'in_progress']);
        });

        // 4. RECENT WORK (LAST 3 DAYS HISTORY)
        $threeDaysAgo = Carbon::today()->subDays(3)->startOfDay();

        $completedJobs = $allPartnerBookings->filter(function ($b) {
            return $b->partner_job_status === 'completed' || $b->status === 'completed';
        });

        $recentCompletedBookings = $allPartnerBookings->filter(function ($b) use ($threeDaysAgo) {
            $isCompleted = ($b->partner_job_status === 'completed' || $b->status === 'completed');
            if (! $isCompleted) {
                return false;
            }
            $completedAt = $b->completed_at ?: $b->updated_at;

            return $completedAt && $completedAt >= $threeDaysAgo;
        });

        $recentCompletedShifts = $allPartnerAmcShifts->filter(function ($s) use ($threeDaysAgo) {
            if ($s->status !== 'completed') {
                return false;
            }
            $completedAt = $s->completed_at ?: $s->updated_at;

            return $completedAt && $completedAt >= $threeDaysAgo;
        });

        $recentWorkHistory = collect();

        foreach ($recentCompletedBookings as $b) {
            $completedAt = $b->completed_at ?: $b->updated_at;
            $earning = $partner->calculateBookingEarning($b);
            $recentWorkHistory->push([
                'id' => 'booking_'.$b->id,
                'item_type' => 'booking',
                'is_amc' => (bool) $b->is_amc,
                'reference' => '#'.$b->booking_number,
                'title' => $b->service?->title ?? $b->amcPlan?->name ?? 'Completed Service',
                'client_name' => $b->customer_name,
                'area' => $b->area,
                'completed_at' => $completedAt,
                'completed_at_label' => $completedAt ? $completedAt->diffForHumans() : 'Recently',
                'earnings' => $earning,
                'url' => route('partner.jobs.show', $b),
            ]);
        }

        foreach ($recentCompletedShifts as $s) {
            $completedAt = $s->completed_at ?: $s->updated_at;
            $payout = (float) ($s->payout_amount ?: ($s->booking?->payout_per_shift ?: 0));
            $recentWorkHistory->push([
                'id' => 'shift_'.$s->id,
                'item_type' => 'amc_shift',
                'is_amc' => true,
                'reference' => '#'.($s->booking?->booking_number ?? 'AMC').' • Shift #'.$s->shift_number,
                'title' => $s->title ?: ($s->booking?->amcPlan?->name ?? 'Routine Maintenance Shift'),
                'client_name' => $s->booking?->customer_name ?? 'Client',
                'area' => $s->booking?->area ?? '',
                'completed_at' => $completedAt,
                'completed_at_label' => $completedAt ? $completedAt->diffForHumans() : 'Recently',
                'earnings' => $payout,
                'url' => route('partner.jobs.show', $s->booking).'#tab-amc',
            ]);
        }

        $recentWorkHistory = $recentWorkHistory->sortByDesc('completed_at')->values();

        // Calculate partner earnings based on partner's assigned earning model
        if ($partner->earning_model === 'monthly') {
            $totalEarned = (float) ($partner->payout_rate ?? 0);
        } else {
            $totalEarned = (float) $completedJobs->sum(function ($b) use ($partner) {
                return $partner->calculateBookingEarning($b);
            });
            $totalEarned += (float) $allPartnerAmcShifts->where('status', 'completed')->sum(function ($s) {
                return (float) ($s->payout_amount ?: ($s->booking?->payout_per_shift ?: 0));
            });
        }

        $recentJobs = $allPartnerBookings->take(6);
        $upcomingAmcShifts = $upcomingNext3DaysShifts->take(5);

        $amcContractsCount = $allPartnerBookings->filter(fn ($b) => $b->is_amc || $b->service_type === 'amc_contract')->count();
        $pendingShiftsCount = $allPartnerAmcShifts->where('status', 'scheduled')->count();
        $completedShiftsCount = $allPartnerAmcShifts->where('status', 'completed')->count();

        $stats = [
            'active_count' => $activeServiceOrders->count() + $activeAmcContracts->count(),
            'completed_count' => $completedJobs->count() + $completedShiftsCount,
            'today_count' => $todaySchedule->count(),
            'total_earnings' => $totalEarned,
            'cash_collected' => $partner->cash_collected_by_crew,
            'total_paid' => $partner->total_paid,
            'net_balance' => $partner->net_balance,
            'admin_owes_crew' => $partner->admin_owes_crew,
            'crew_owes_admin' => $partner->crew_owes_admin,
            'pending_due' => $partner->payable_balance,
            'crew_due_amount' => $partner->crew_due_amount,
            'has_admin_payment_request' => $partner->payouts()->where('direction', 'partner_to_admin')->where('status', 'requested')->exists(),
            'is_offline' => $partner->is_currently_offline,
            'offline_until' => $partner->offline_until,
            'offline_reason' => $partner->offline_reason,
            'amc_contracts_count' => $amcContractsCount,
            'pending_shifts_count' => $pendingShiftsCount,
            'completed_shifts_count' => $completedShiftsCount,
        ];

        return view('partner.dashboard', compact(
            'partner',
            'stats',
            'activeJobs',
            'activeServiceOrders',
            'activeAmcContracts',
            'todayJobs',
            'todaySchedule',
            'upcomingThreeDaysSchedule',
            'recentWorkHistory',
            'recentJobs',
            'upcomingAmcShifts',
            'amcContractsCount',
            'pendingShiftsCount',
            'completedShiftsCount'
        ));
    }

    /**
     * Quick toggle online/offline availability directly from dashboard.
     */
    public function toggleAvailability(Request $request): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        $isCurrentlyOffline = (bool) $partner->is_currently_offline;
        $newOfflineState = ! $isCurrentlyOffline;

        $partner->update([
            'is_currently_offline' => $newOfflineState,
            'offline_until' => $newOfflineState ? ($request->filled('offline_until') ? $request->date('offline_until') : null) : null,
            'offline_reason' => $newOfflineState ? ($request->input('offline_reason') ?: 'Temporarily unavailable') : null,
        ]);

        if ($newOfflineState) {
            NotificationDispatcher::notifyAdminSection(
                'partners',
                "Partner Marked Offline: {$partner->company_name}",
                "Partner '{$partner->company_name}' switched status to OFFLINE. Reason: ".($partner->offline_reason ?: 'Temporarily unavailable'),
                route('admin.partners.show', $partner)
            );
        }

        $message = $newOfflineState
            ? 'You are now OFFLINE. Fix Admin will not assign new jobs to you while offline.'
            : 'You are now ONLINE and available to receive new service dispatch assignments!';

        return back()->with('success', $message);
    }
}
