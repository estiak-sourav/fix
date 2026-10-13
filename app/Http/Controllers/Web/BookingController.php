<?php

namespace App\Http\Controllers\Web;

use App\Actions\Booking\CreateBookingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreBookingRequest;
use App\Models\AmcPlan;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Show the booking request form.
     */
    public function create(Request $request): View
    {
        $services = Service::where('is_active', true)->orderBy('order')->get();
        $amcPlans = AmcPlan::where('is_active', true)->orderBy('order')->get();

        $selectedService = $request->query('service');
        $selectedPlan = $request->query('plan');

        return view('web.pages.booking', compact('services', 'amcPlans', 'selectedService', 'selectedPlan'));
    }

    /**
     * Store new booking request using action pattern.
     */
    public function store(StoreBookingRequest $request, CreateBookingAction $action): RedirectResponse|JsonResponse
    {
        // Enforce customer login requirement
        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'requires_auth' => true,
                    'message' => 'You must sign in with your email OTP before submitting a service request.',
                ], 401);
            }

            return redirect()->route('booking.create')
                ->withInput()
                ->with('error', 'You must sign in with your email OTP before submitting a service request.')
                ->with('trigger_auth_modal', true);
        }

        $booking = $action->execute(
            $request->validated(),
            $request->user('web'),
            $request->file('problem_photo')
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'booking_number' => $booking->booking_number,
                'message' => "Your booking request (#{$booking->booking_number}) has been placed successfully!",
                'redirect_url' => route('customer.bookings.show', $booking->booking_number),
            ]);
        }

        return redirect()->route('customer.bookings')
            ->with('success', "Your booking request (#{$booking->booking_number}) has been placed successfully! Our operations team has been alerted.");
    }
}
