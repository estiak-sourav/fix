<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Booking\UpdateBookingStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Display Admin Command Center dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_review' => Booking::where('status', 'pending_review')->count(),
            'active_in_progress' => Booking::whereIn('status', ['accepted', 'assigned', 'in_progress'])->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $recentBookings = Booking::with(['user', 'service', 'amcPlan'])
            ->latest()
            ->take(8)
            ->get();

        $unreadNotifications = AdminNotification::latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'unreadNotifications'));
    }

    /**
     * Polling endpoint to check for incoming notifications with audible sound trigger.
     */
    public function checkNewAlerts(): JsonResponse
    {
        $unplayed = AdminNotification::where('sound_played', false)
            ->latest()
            ->get();

        $unreadCount = AdminNotification::where('is_read', false)->count();

        if ($unplayed->isNotEmpty()) {
            AdminNotification::whereIn('id', $unplayed->pluck('id'))->update(['sound_played' => true]);
        }

        return response()->json([
            'has_sound' => $unplayed->isNotEmpty(),
            'new_count' => $unplayed->count(),
            'unread_total' => $unreadCount,
            'alerts' => $unplayed->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'type' => $n->type,
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Backwards-compatible delegation to AdminBookingController.
     */
    public function bookings(Request $request): View
    {
        return app(AdminBookingController::class)->index($request);
    }

    public function showBooking(Booking $booking): View
    {
        return app(AdminBookingController::class)->show($booking);
    }

    public function updateBookingStatus(Booking $booking, UpdateBookingStatusRequest $request, UpdateBookingStatusAction $action): RedirectResponse
    {
        return app(AdminBookingController::class)->updateStatus($booking, $request, $action);
    }
}
