<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Booking\UpdateBookingStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\Booking;
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

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Accept a pending order after phone discussion with the client.
     * Price can be set now or left for post-completion billing.
     */
    public function accept(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'pricing_type' => ['required', 'string', 'in:agreed,bill_later'],
            'agreed_price' => ['nullable', 'numeric', 'min:0', 'required_if:pricing_type,agreed'],
            'assigned_team' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();
        $pricingType = $validated['pricing_type'];
        $agreedPrice = isset($validated['agreed_price']) ? (float) $validated['agreed_price'] : null;

        $comment = $validated['comment'] ?? null;
        if (! $comment) {
            if ($pricingType === 'agreed' && $agreedPrice !== null) {
                $comment = 'Order confirmed with client via phone. Agreed pricing: ৳ '.number_format($agreedPrice, 2);
            } else {
                $comment = 'Order confirmed with client via phone. Pricing to be finalized upon job completion / inspection.';
            }
        }

        $payload = [
            'status' => 'accepted',
            'assigned_team' => $validated['assigned_team'] ?? $booking->assigned_team,
            'comment' => $comment,
            'admin_notes' => "Order accepted by {$admin->name}. Pricing mode: {$pricingType}.",
        ];

        if ($pricingType === 'agreed' && $agreedPrice !== null) {
            $payload['estimated_cost'] = $agreedPrice;
        }

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.accept', "Accepted Work Order #{$booking->booking_number} after phone discussion with {$booking->customer_name}.", $booking, [
            'booking_number' => $booking->booking_number,
            'pricing_type' => $pricingType,
            'agreed_price' => $agreedPrice,
            'assigned_team' => $booking->assigned_team,
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} has been ACCEPTED successfully.");
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
