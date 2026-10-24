<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Booking\UpdateBookingStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    /**
     * List all facility service bookings with comprehensive filters.
     */
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'service', 'amcPlan', 'statusLogs'])->latest();

        // Status filter (support both 'pending' and 'pending_review')
        if ($status = $request->query('status')) {
            if ($status === 'pending') {
                $query->where('status', 'pending_review');
            } else {
                $query->where('status', $status);
            }
        }

        // Division filter
        if ($division = $request->query('division')) {
            if ($division !== 'all') {
                $query->where('division', $division);
            }
        }

        // District filter
        if ($district = $request->query('district')) {
            if ($district !== 'all') {
                $query->where('district', $district);
            }
        }

        // Area filter
        if ($area = $request->query('area')) {
            if ($area !== 'all') {
                $query->where('area', $area);
            }
        }

        // Urgency filter
        if ($urgency = $request->query('urgency')) {
            if ($urgency !== 'all') {
                $query->where('urgency', $urgency);
            }
        }

        // Time range filter
        if ($range = $request->query('range')) {
            if ($range === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($range === 'week') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === 'month') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        // Quick search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        // Extract available values for interactive filter dropdowns
        $availableDivisions = Booking::whereNotNull('division')->where('division', '!=', '')->distinct()->orderBy('division')->pluck('division');
        $availableDistricts = Booking::whereNotNull('district')->where('district', '!=', '')->distinct()->orderBy('district')->pluck('district');
        $availableAreas = Booking::whereNotNull('area')->where('area', '!=', '')->distinct()->orderBy('area')->pluck('area');

        $statusCounts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending_review')->count(),
            'accepted' => Booking::where('status', 'accepted')->count(),
            'assigned' => Booking::where('status', 'assigned')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'availableDivisions', 'availableDistricts', 'availableAreas', 'statusCounts'));
    }

    /**
     * View detailed single booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'service', 'amcPlan', 'statusLogs.changedByUser', 'statusLogs.changedByAdmin']);

        $fieldCrews = User::whereIn('role', ['technician', 'vendor', 'partner'])
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'role', 'company_name']);

        $enlistedPartners = Partner::where('status', 'approved')->orderBy('company_name')->get();

        $crewOptions = collect();
        foreach ($enlistedPartners as $p) {
            $crewOptions->push((object) [
                'name' => "{$p->company_name} — {$p->contact_person}",
                'phone' => $p->phone,
                'role' => "Enlisted Partner • {$p->trade_domain}",
                'company_name' => $p->company_name,
            ]);
        }
        foreach ($fieldCrews as $u) {
            $crewOptions->push((object) [
                'name' => $u->name,
                'phone' => $u->phone,
                'role' => ucfirst($u->role),
                'company_name' => $u->company_name,
            ]);
        }

        return view('admin.bookings.show', [
            'booking' => $booking,
            'fieldCrews' => $crewOptions,
        ]);
    }

    /**
     * Log phone contact status (e.g. unreachable, call back later).
     */
    public function logCallStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'call_status' => ['required', 'string', 'in:unreachable,call_back_later,connected'],
            'unreachable_reason' => ['nullable', 'string', 'max:255'],
            'callback_time' => ['nullable', 'string', 'max:255'],
            'call_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();
        $notesCombined = $validated['call_notes'] ?? '';
        if ($validated['call_status'] === 'unreachable' && ! empty($validated['unreachable_reason'])) {
            $notesCombined = 'Reason: '.$validated['unreachable_reason'].($notesCombined ? ' • Note: '.$notesCombined : '');
        } elseif ($validated['call_status'] === 'call_back_later' && ! empty($validated['callback_time'])) {
            $notesCombined = 'Follow-up: '.$validated['callback_time'].($notesCombined ? ' • Note: '.$notesCombined : '');
        }

        $booking->update([
            'call_status' => $validated['call_status'],
            'call_notes' => $notesCombined ?: null,
        ]);

        $statusText = match ($validated['call_status']) {
            'unreachable' => 'Client Unreachable / No Answer over phone',
            'call_back_later' => 'Client requested Callback Later / Follow-up required',
            'connected' => 'Phone discussion connected with client',
            default => 'Phone call status updated'
        };
        $logComment = $statusText.($notesCombined ? ' ('.$notesCombined.')' : '');

        $booking->statusLogs()->create([
            'from_status' => $booking->status,
            'to_status' => $booking->status,
            'comment' => $logComment,
            'is_customer_visible' => false,
            'admin_id' => $admin?->id,
        ]);

        ActivityLogService::logBooking('booking.call_status', "Phone call log for #{$booking->booking_number}: {$statusText}.", $booking, [
            'call_status' => $validated['call_status'],
            'call_notes' => $validated['call_notes'] ?? null,
        ]);

        return redirect()->back()->with('success', "Call status updated: {$statusText}");
    }

    /**
     * Accept a pending order after phone discussion with the client.
     * Price can be set now (Estimated or Final) or left for post-completion billing.
     */
    public function accept(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'set_price' => ['nullable', 'boolean'],
            'cost_type' => ['nullable', 'string', 'in:estimated,final'],
            'cost_amount' => ['nullable', 'numeric', 'min:0'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time_slot' => ['nullable', 'string', 'max:100'],
            'assigned_team' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();

        // Update schedule if provided
        $directUpdates = [];
        if (! empty($validated['preferred_date'])) {
            $directUpdates['preferred_date'] = $validated['preferred_date'];
        }
        if (! empty($validated['preferred_time_slot'])) {
            $directUpdates['preferred_time_slot'] = $validated['preferred_time_slot'];
        }
        if (! empty($directUpdates)) {
            $booking->update($directUpdates);
        }

        $pricingMode = $request->input('pricing_mode', 'price'); // 'price' or 'later'
        $isEstimated = (bool) $request->input('is_estimated', false);
        $costAmount = isset($validated['cost_amount']) && $validated['cost_amount'] !== '' ? (float) $validated['cost_amount'] : null;
        $isPriceSet = ($pricingMode === 'price') && $costAmount !== null && $costAmount > 0;
        $costType = $isEstimated ? 'estimated' : 'final';

        $comment = $validated['comment'] ?? null;
        if (! $comment) {
            if ($isPriceSet) {
                $typeLabel = $isEstimated ? 'Agreed Estimated Price' : 'Final Invoiced Price';
                $comment = "Order confirmed with client via phone. {$typeLabel}: ৳ ".number_format($costAmount, 2);
            } else {
                $comment = 'Order confirmed with client via phone. Pricing to be finalized upon job completion / inspection.';
            }
        }

        // Also mark call as connected since phone discussion took place
        $booking->update(['call_status' => 'connected']);

        $payload = [
            'status' => 'accepted',
            'assigned_team' => $validated['assigned_team'] ?? $booking->assigned_team,
            'comment' => $comment,
            'admin_notes' => "Order accepted by {$admin->name}. Pricing: ".($isPriceSet ? "৳ {$costAmount} ({$costType})" : 'Billed upon completion').'.',
        ];

        if ($isPriceSet) {
            if ($costType === 'final') {
                $payload['final_cost'] = $costAmount;
            } else {
                $payload['estimated_cost'] = $costAmount;
            }
        }

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.accept', "Accepted Work Order #{$booking->booking_number} after phone discussion with {$booking->customer_name}.", $booking, [
            'booking_number' => $booking->booking_number,
            'is_price_set' => $isPriceSet,
            'cost_type' => $costType,
            'cost_amount' => $costAmount,
            'assigned_team' => $payload['assigned_team'],
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} has been ACCEPTED successfully.");
    }

    /**
     * Mark a work order as completed with admin remarks/feedback for the client.
     */
    public function complete(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'completion_feedback' => ['required', 'string', 'max:2000'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $admin = Auth::guard('admin')->user();

        $booking->completion_feedback = $validated['completion_feedback'];
        if (! empty($validated['final_cost'])) {
            $booking->final_cost = (float) $validated['final_cost'];
        }
        $booking->save();

        $payload = [
            'status' => 'completed',
            'comment' => 'Work completed: '.$validated['completion_feedback'],
            'admin_notes' => 'Completed by '.$admin->name.'. Feedback for client: '.$validated['completion_feedback'],
        ];
        if (! empty($validated['final_cost'])) {
            $payload['final_cost'] = (float) $validated['final_cost'];
        }

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.complete', "Marked Work Order #{$booking->booking_number} as COMPLETED with feedback for client.", $booking, [
            'booking_number' => $booking->booking_number,
            'feedback' => $validated['completion_feedback'],
            'final_cost' => $booking->final_cost,
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} marked as COMPLETED. Client can now submit feedback & rating.");
    }

    /**
     * Cancel an order with required cancellation note.
     */
    public function cancel(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();

        $payload = [
            'status' => 'cancelled',
            'comment' => 'Order cancelled: '.$validated['cancellation_reason'],
            'admin_notes' => 'Cancelled by '.$admin->name.': '.$validated['cancellation_reason'],
        ];

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.cancel', "Cancelled Work Order #{$booking->booking_number}. Reason: {$validated['cancellation_reason']}", $booking, [
            'booking_number' => $booking->booking_number,
            'reason' => $validated['cancellation_reason'],
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} has been CANCELLED.");
    }

    /**
     * Update and modify comprehensive booking parameters (schedule, team, billing, notes, status).
     */
    public function update(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending_review,accepted,assigned,in_progress,completed,cancelled'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
            'assigned_team' => ['nullable', 'string', 'max:255'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time_slot' => ['nullable', 'string', 'max:50'],
            'street_address' => ['nullable', 'string', 'max:500'],
            'problem_description' => ['nullable', 'string'],
            'admin_notes' => ['nullable', 'string'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();

        // Update direct booking properties if provided
        $directUpdates = [];
        if (isset($validated['preferred_date'])) {
            $directUpdates['preferred_date'] = $validated['preferred_date'];
        }
        if (isset($validated['preferred_time_slot'])) {
            $directUpdates['preferred_time_slot'] = $validated['preferred_time_slot'];
        }
        if (isset($validated['street_address'])) {
            $directUpdates['street_address'] = $validated['street_address'];
        }
        if (isset($validated['problem_description'])) {
            $directUpdates['problem_description'] = $validated['problem_description'];
        }
        if (! empty($directUpdates)) {
            $booking->update($directUpdates);
        }

        $payload = [
            'status' => $validated['status'],
            'assigned_team' => $validated['assigned_team'] ?? $booking->assigned_team,
            'admin_notes' => $validated['admin_notes'] ?? $booking->admin_notes,
            'comment' => $validated['comment'] ?? 'Work Order details and status updated by operations lead.',
        ];

        if (array_key_exists('estimated_cost', $validated)) {
            $payload['estimated_cost'] = $validated['estimated_cost'];
        }
        if (array_key_exists('final_cost', $validated)) {
            $payload['final_cost'] = $validated['final_cost'];
        }

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.modify', "Modified details & status of Work Order #{$booking->booking_number}.", $booking, [
            'booking_number' => $booking->booking_number,
            'status' => $booking->status,
            'estimated_cost' => $booking->estimated_cost,
            'final_cost' => $booking->final_cost,
            'assigned_team' => $booking->assigned_team,
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} updated successfully.");
    }

    /**
     * Update booking status and notify customer via UpdateBookingStatusAction (legacy compatibility).
     */
    public function updateStatus(Booking $booking, UpdateBookingStatusRequest $request, UpdateBookingStatusAction $action): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $action->execute($booking, $request->validated(), $admin);

        ActivityLogService::logBooking('booking.status_update', "Updated Work Order #{$booking->booking_number} status to '".ucfirst(str_replace('_', ' ', $request->validated('status')))."'.", $booking, [
            'booking_number' => $booking->booking_number,
            'status' => $booking->status,
            'assigned_team' => $booking->assigned_team,
            'final_cost' => $booking->final_cost,
        ]);

        return redirect()->back()->with('success', "Booking #{$booking->booking_number} updated to ".ucfirst(str_replace('_', ' ', $request->validated('status'))));
    }
}
