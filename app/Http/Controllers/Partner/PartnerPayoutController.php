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

        if ($partner->earning_model === 'monthly') {
            $totalEarned = (float) ($partner->payout_rate ?? 0);
        } else {
            $totalEarned = (float) Booking::where('partner_id', $partner->id)
                ->where(function ($q) {
                    $q->where('partner_job_status', 'completed')
                        ->orWhere('status', 'completed');
                })
                ->get()
                ->sum(function ($b) use ($partner) {
                    return $partner->calculateBookingEarning($b);
                });
        }

        return view('partner.payouts.index', compact('partner', 'completedJobs', 'totalEarned'));
    }
}
