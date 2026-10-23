<?php

namespace App\Http\Controllers\Web;

use App\Actions\User\UpdateUserProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UpdateProfileRequest;
use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CustomerPortalController extends Controller
{
    /**
     * Display customer's service bookings with live status tracking.
     */
    public function myBookings(): View
    {
        $user = Auth::guard('web')->user();
        $bookings = Booking::where('user_id', $user->id)
            ->with(['service', 'amcPlan', 'statusLogs' => fn ($q) => $q->where('is_customer_visible', true)->latest()])
            ->latest()
            ->paginate(10);

        return view('web.pages.customer.my-bookings', compact('bookings'));
    }

    /**
     * Show detailed timeline for a single booking.
     */
    public function showBooking(string $bookingNumber): View
    {
        $user = Auth::guard('web')->user();
        $booking = Booking::where('booking_number', $bookingNumber)
            ->where('user_id', $user->id)
            ->with(['service', 'amcPlan', 'statusLogs' => fn ($q) => $q->where('is_customer_visible', true)->latest()])
            ->firstOrFail();

        return view('web.pages.customer.booking-details', compact('booking'));
    }

    /**
     * Render customer profile view.
     */
    public function profile(): View
    {
        $user = Auth::guard('web')->user();

        return view('web.pages.customer.profile', compact('user'));
    }

    /**
     * Update customer profile information using Action pattern.
     */
    public function updateProfile(UpdateProfileRequest $request, UpdateUserProfileAction $action): RedirectResponse
    {
        $action->execute(Auth::guard('web')->user(), $request->validated());

        return redirect()->route('customer.profile')
            ->with('success', 'Your profile details have been updated successfully.');
    }
}
