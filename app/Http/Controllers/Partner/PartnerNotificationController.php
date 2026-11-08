<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerNotificationController extends Controller
{
    /**
     * Display partner in-app notification center.
     */
    public function index(Request $request): View
    {
        $partner = Auth::guard('partner')->user();

        $filter = $request->query('filter', 'all');

        $query = PartnerNotification::where('partner_id', $partner->id)->latest();

        if ($filter === 'unread') {
            $query->unread();
        }

        $notifications = $query->paginate(20)->withQueryString();

        $unreadCount = PartnerNotification::where('partner_id', $partner->id)->unread()->count();
        $totalCount = PartnerNotification::where('partner_id', $partner->id)->count();

        return view('partner.notifications.index', compact('partner', 'notifications', 'unreadCount', 'totalCount', 'filter'));
    }

    /**
     * Mark a single notification as read and optionally navigate to action URL.
     */
    public function markAsRead(Request $request, PartnerNotification $notification): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ($notification->partner_id !== $partner->id) {
            abort(403);
        }

        $notification->markAsRead();

        if ($request->filled('redirect') && $notification->action_url) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications for partner as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        PartnerNotification::where('partner_id', $partner->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Lightweight API endpoint for badge counter polling.
     */
    public function unreadCount(): JsonResponse
    {
        $partner = Auth::guard('partner')->user();
        $count = $partner ? $partner->unreadNotificationsCount() : 0;

        return response()->json(['unread_count' => $count]);
    }
}

