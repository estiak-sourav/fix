<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Display the Admin Notifications & Dispatch Alerts page.
     */
    public function index(Request $request): View
    {
        $query = AdminNotification::with('booking')->latest();

        // Filter by notification type
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        // Filter by read status
        if ($request->has('status')) {
            if ($request->query('status') === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->query('status') === 'read') {
                $query->where('is_read', true);
            }
        }

        // Search in title or message
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(20)->withQueryString();

        $stats = [
            'total_alerts' => AdminNotification::count(),
            'unread_alerts' => AdminNotification::where('is_read', false)->count(),
            'emergency_alerts' => AdminNotification::where('type', 'emergency_alert')->count(),
            'booking_alerts' => AdminNotification::where('type', 'new_booking')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Mark single notification as read.
     */
    public function markAsRead(AdminNotification $notification): RedirectResponse
    {
        $notification->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        $count = AdminNotification::where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', "{$count} notifications marked as read.");
    }

    /**
     * Clear / delete all read notifications.
     */
    public function clearRead(): RedirectResponse
    {
        $count = AdminNotification::where('is_read', true)->delete();

        return redirect()->back()->with('success', "{$count} resolved notifications cleared.");
    }

    /**
     * Delete a single notification.
     */
    public function destroy(AdminNotification $notification): RedirectResponse
    {
        $notification->delete();

        return redirect()->back()->with('success', 'Notification removed.');
    }
}
