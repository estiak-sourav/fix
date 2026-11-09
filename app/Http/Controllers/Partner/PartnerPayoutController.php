<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerPayoutController extends Controller
{
    /**
     * Display earnings, commission summary, and payout history.
     */
    public function index(): View
    {
        $partner = Auth::guard('partner')->user();

        $completedJobs = Booking::where('partner_id', $partner->id)
            ->where(function ($q) {
                $q->where('partner_job_status', 'completed')
                    ->orWhere('status', 'completed');
            })
            ->latest('completed_at')
            ->paginate(15);

        $allCompleted = Booking::where('partner_id', $partner->id)
            ->where(function ($q) {
                $q->where('partner_job_status', 'completed')
                    ->orWhere('status', 'completed');
            })
            ->get();

        if ($partner->earning_model === 'monthly') {
            $totalEarned = (float) ($partner->payout_rate ?? 0);
        } else {
            $totalEarned = (float) $allCompleted->sum(function ($b) use ($partner) {
                return $partner->calculateBookingEarning($b);
            });
        }

        $totalPaid = (float) $allCompleted->where('partner_payout_status', 'paid')->sum(function ($b) use ($partner) {
            return (float) ($b->partner_payout_amount ?? $partner->calculateBookingEarning($b));
        });

        $pendingDue = max(0.0, $totalEarned - $totalPaid);

        return view('partner.payouts.index', compact('partner', 'completedJobs', 'totalEarned', 'totalPaid', 'pendingDue'));
    }
}
