<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerLeave;
use App\Services\NotificationDispatcher;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerAvailabilityController extends Controller
{
    /**
     * Display availability calendar & leave management view.
     */
    public function index(): View
    {
        $partner = Auth::guard('partner')->user();

        $leaves = $partner->leaves()
            ->where('end_at', '>=', now()->subDays(1))
            ->orderBy('start_at')
            ->get();

        $pastLeaves = $partner->leaves()
            ->where('end_at', '<', now()->subDays(1))
            ->orderByDesc('end_at')
            ->take(5)
            ->get();

        return view('partner.availability.index', compact('partner', 'leaves', 'pastLeaves'));
    }

    /**
     * Schedule a new leave or busy period.
     */
    public function storeLeave(Request $request): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        $validated = $request->validate([
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'reason' => ['nullable', 'string', 'max:255'],
        ], [
            'start_at.required' => 'Please select the start date and time of your leave.',
            'end_at.required' => 'Please select the end date and time of your leave.',
            'end_at.after' => 'Leave end time must be after the start time.',
        ]);

        $start = Carbon::parse($validated['start_at']);
        $end = Carbon::parse($validated['end_at']);

        PartnerLeave::create([
            'partner_id' => $partner->id,
            'start_at' => $start,
            'end_at' => $end,
            'reason' => $validated['reason'] ?: 'Scheduled Leave / Personal Time',
        ]);

        NotificationDispatcher::notifyAdminSection(
            'partners',
            "Partner Time-Off Scheduled: {$partner->company_name}",
            "Partner '{$partner->company_name}' scheduled unavailability from {$start->format('M d, Y h:i A')} to {$end->format('M d, Y h:i A')}. Reason: ".($validated['reason'] ?: 'Scheduled Leave'),
            route('admin.partners.show', $partner),
            [
                'Partner' => $partner->company_name,
                'From' => $start->format('M d, Y h:i A'),
                'To' => $end->format('M d, Y h:i A'),
                'Reason' => $validated['reason'] ?: 'Scheduled Leave',
            ]
        );

        return back()->with('success', 'Leave period registered successfully! Fix Admin will not assign work during this interval.');
    }

    /**
     * Cancel / remove a scheduled leave period.
     */
    public function destroyLeave(PartnerLeave $leave): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $leave->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $leave->delete();

        return back()->with('success', 'Leave period cancelled. Your availability has been restored.');
    }
}
