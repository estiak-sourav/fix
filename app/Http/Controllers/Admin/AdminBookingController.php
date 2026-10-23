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
     * List all facility service bookings with filters.
     */
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'service', 'amcPlan', 'statusLogs'])->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
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
     * Update booking status and notify customer via UpdateBookingStatusAction.
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
