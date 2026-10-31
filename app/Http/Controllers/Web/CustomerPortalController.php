<?php

namespace App\Http\Controllers\Web;

use App\Actions\User\UpdateUserProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UpdateAvatarRequest;
use App\Http\Requests\Web\UpdateProfileRequest;
use App\Models\Booking;
use App\Models\Division;
use App\Services\ImageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
     * Submit client feedback rating and comment for completed booking.
     */
    public function submitReview(Request $request, string $bookingNumber): RedirectResponse
    {
        $validated = $request->validate([
            'review_rating' => ['required', 'integer', 'between:1,5'],
            'review_comment' => ['required', 'string', 'max:1000'],
        ]);

        $user = Auth::guard('web')->user();
        $booking = Booking::where('booking_number', $bookingNumber)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        $booking->update([
            'review_rating' => (int) $validated['review_rating'],
            'review_comment' => $validated['review_comment'],
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you! Your feedback and rating have been submitted successfully.');
    }

    /**
     * Render customer profile view.
     */
    public function profile(): View
    {
        $user = Auth::guard('web')->user();

        $divisionsData = Cache::rememberForever('bd_locations_hierarchy_v2', function () {
            $divisions = Division::with(['districts.thanas'])->orderBy('name')->get();

            if ($divisions->isEmpty()) {
                return [];
            }

            return $divisions->map(function ($div) {
                return [
                    'name' => (string) $div->name,
                    'districts' => $div->districts->sortBy('name')->map(function ($dist) {
                        return [
                            'name' => (string) $dist->name,
                            'thanas' => $dist->thanas->sortBy('name')->pluck('name')->values()->all(),
                        ];
                    })->values()->all(),
                ];
            })->values()->all();
        });

        if (! is_array($divisionsData)) {
            $divisionsData = [];
        }

        return view('web.pages.customer.profile', compact('user', 'divisionsData'));
    }

    /**
     * Upload/replace customer avatar only — center cropped into square.
     */
    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        $file = $request->file('avatar');

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = ImageService::cropSquareAndStore($file);
        $user->save();

        return redirect()->route('customer.profile')
            ->with('success', 'Profile picture updated successfully.');
    }

    /**
     * Remove customer avatar.
     */
    public function removeAvatar(): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        return redirect()->route('customer.profile')
            ->with('success', 'Profile picture removed successfully.');
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
