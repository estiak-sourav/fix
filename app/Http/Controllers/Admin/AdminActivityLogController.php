<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminActivityLogController extends Controller
{
    /**
     * Display a paginated listing of administrative activity logs.
     */
    public function index(Request $request): View
    {
        $query = AdminActivityLog::with('admin')->latest();

        // Filter by category
        if ($category = $request->query('category')) {
            $query->category($category);
        }

        // Filter by administrative actor
        if ($adminId = $request->query('admin_id')) {
            $query->byAdmin((int) $adminId);
        }

        // Filter by date timeframe
        if ($timeframe = $request->query('timeframe')) {
            match ($timeframe) {
                'today' => $query->whereDate('created_at', Carbon::today()),
                'yesterday' => $query->whereDate('created_at', Carbon::yesterday()),
                '7days' => $query->where('created_at', '>=', Carbon::now()->subDays(7)),
                '30days' => $query->where('created_at', '>=', Carbon::now()->subDays(30)),
                default => null,
            };
        }

        // Search term
        if ($search = $request->query('search')) {
            $query->search($search);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total_activities' => AdminActivityLog::count(),
            'today_activities' => AdminActivityLog::whereDate('created_at', Carbon::today())->count(),
            'staff_actions' => AdminActivityLog::where('category', 'staff')->count(),
            'operational_actions' => AdminActivityLog::whereIn('category', ['bookings', 'tickets'])->count(),
        ];

        $adminUsers = Admin::select('id', 'name', 'email', 'avatar')->orderBy('name')->get();

        return view('admin.activity-logs.index', compact('logs', 'stats', 'adminUsers'));
    }
}
