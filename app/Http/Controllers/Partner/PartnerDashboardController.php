<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationDispatcher;
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

        $allPartnerBookings = Booking::where('partner_id', $partner->id)
            ->with(['service', 'amcPlan', 'assignedCrew'])
            ->latest()
            ->get();

        $activeJobs = $allPartnerBookings->filter(function ($b) {
            return in_array($b->partner_job_status, ['assigned', 'accepted', 'on_the_way', 'in_progress']);
        });

        $completedJobs = $allPartnerBookings->filter(function ($b) {
            return $b->partner_job_status === 'completed' || $b->status === 'completed';
        });

        $todayJobs = $allPartnerBookings->filter(function ($b) {
            return ($b->preferred_date && $b->preferred_date->isToday())
                || ($b->estimated_arrival_at && $b->estimated_arrival_at->isToday());
        });

        // Calculate partner earnings based on partner's assigned earning model (commission, hourly, daily, monthly, per_job)
        if ($partner->earning_model === 'monthly') {
            $totalEarned = (float) ($partner->payout_rate ?? 0);
        } else {
            $totalEarned = (float) $completedJobs->sum(function ($b) use ($partner) {
                return $partner->calculateBookingEarning($b);
            });
        }

        $recentJobs = $allPartnerBookings->take(6);

        $stats = [
            'active_count' => $activeJobs->count(),
            'completed_count' => $completedJobs->count(),
            'today_count' => $todayJobs->count(),
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
        ];

        return view('partner.dashboard', compact('partner', 'stats', 'activeJobs', 'todayJobs', 'recentJobs'));
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
