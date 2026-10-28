<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\OutreachTask;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOutreachTaskController extends Controller
{
    /**
     * Display a paginated listing of all outreach tasks with filters and search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $priority = $request->query('priority', 'all');
        $search = trim($request->query('search', ''));

        $query = OutreachTask::with(['booking.service', 'user', 'createdByAdmin', 'completedByAdmin'])
            ->latest('id');

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        }

        if ($priority === 'urgent_high') {
            $query->whereIn('priority', ['urgent', 'high']);
        } elseif (in_array($priority, ['low', 'normal', 'high', 'urgent'])) {
            $query->where('priority', $priority);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($bQ) use ($search) {
                        $bQ->where('booking_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($uQ) use ($search) {
                        $uQ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $tasks = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => OutreachTask::count(),
            'pending' => OutreachTask::where('status', 'pending')->count(),
            'completed' => OutreachTask::where('status', 'completed')->count(),
            'urgent_high' => OutreachTask::where('status', 'pending')->whereIn('priority', ['urgent', 'high'])->count(),
        ];

        $recentBookings = Booking::with('service')->latest()->take(60)->get();
        $bookingOptionsData = $recentBookings->map(function ($b) {
            return [
                'id' => $b->id,
                'number' => $b->booking_number,
                'name' => $b->customer_name,
                'phone' => $b->customer_phone,
                'service' => $b->service?->title ?? ucfirst(str_replace('_', ' ', $b->service_type)),
                'status' => ucfirst(str_replace('_', ' ', $b->status)),
                'cost' => '৳ '.number_format($b->final_cost ?: ($b->estimated_cost ?: 0)),
                'area' => $b->area ?? 'Dhaka',
            ];
        });

        $recentUsers = User::latest()->take(60)->get();
        $userOptionsData = $recentUsers->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'phone' => $u->phone,
                'email' => $u->email,
                'address' => $u->default_address ?? ($u->area ? "{$u->area}, {$u->district}" : ''),
            ];
        });

        return view('admin.outreach_tasks.index', compact(
            'tasks',
            'stats',
            'status',
            'priority',
            'search',
            'bookingOptionsData',
            'userOptionsData'
        ));
    }

    /**
     * Store a manually created outreach task.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'task_type' => ['nullable', 'string', 'max:50'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'priority' => ['nullable', 'string', 'in:low,normal,medium,high,urgent'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $rawPriority = $validated['priority'] ?? 'normal';
        $priority = $rawPriority === 'medium' ? 'normal' : $rawPriority;
        $taskType = $validated['task_type'] ?? 'general';

        $admin = Auth::guard('admin')->user();
        $booking = null;
        $user = null;
        $customerName = $validated['customer_name'] ?? null;
        $customerPhone = $validated['customer_phone'] ?? null;
        $userId = $validated['user_id'] ?? null;
        $bookingId = $validated['booking_id'] ?? null;

        if (! empty($bookingId)) {
            $booking = Booking::find($bookingId);
            if ($booking) {
                $customerName = $customerName ?: $booking->customer_name;
                $customerPhone = $customerPhone ?: $booking->customer_phone;
                if (! $userId && $booking->user_id) {
                    $userId = $booking->user_id;
                }
            }
        } elseif (! empty($userId)) {
            $user = User::find($userId);
            if ($user) {
                $customerName = $customerName ?: $user->name;
                $customerPhone = $customerPhone ?: $user->phone;
            }
        }

        $task = OutreachTask::create([
            'booking_id' => $bookingId,
            'user_id' => $userId,
            'admin_id' => $admin?->id,
            'created_by_admin_id' => $admin?->id,
            'title' => $validated['title'],
            'task_type' => $taskType,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'priority' => $priority,
            'status' => 'pending',
            'due_at' => $validated['due_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_automated' => false,
        ]);

        if ($booking) {
            ActivityLogService::logBooking('booking.task_created', "Manual outreach task created: '{$task->title}'", $booking, [
                'task_id' => $task->id,
                'task_type' => $task->task_type,
            ]);
        }

        return redirect()->back()->with('success', "Task created successfully: '{$task->title}'");
    }

    /**
     * Update an outreach task.
     */
    public function update(OutreachTask $task, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'task_type' => ['nullable', 'string', 'max:50'],
            'priority' => ['nullable', 'string', 'in:low,normal,medium,high,urgent'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'string', 'in:pending,completed'],
        ]);

        $rawPriority = $validated['priority'] ?? $task->priority;
        $priority = $rawPriority === 'medium' ? 'normal' : $rawPriority;
        $admin = Auth::guard('admin')->user();

        $updateData = [
            'title' => $validated['title'],
            'priority' => $priority,
            'due_at' => $validated['due_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        if (array_key_exists('task_type', $validated) && $validated['task_type']) {
            $updateData['task_type'] = $validated['task_type'];
        }

        if (array_key_exists('booking_id', $validated)) {
            $updateData['booking_id'] = $validated['booking_id'] ?: null;
            if ($updateData['booking_id']) {
                $booking = Booking::find($updateData['booking_id']);
                if ($booking) {
                    $updateData['customer_name'] = $booking->customer_name;
                    $updateData['customer_phone'] = $booking->customer_phone;
                    if (! empty($booking->user_id)) {
                        $updateData['user_id'] = $booking->user_id;
                    }
                }
            }
        }

        if (array_key_exists('user_id', $validated)) {
            $updateData['user_id'] = $validated['user_id'] ?: null;
            if ($updateData['user_id'] && empty($updateData['booking_id'])) {
                $user = User::find($updateData['user_id']);
                if ($user) {
                    $updateData['customer_name'] = $user->name;
                    $updateData['customer_phone'] = $user->phone;
                }
            }
        }

        if (isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
            if ($validated['status'] === 'completed' && $task->status !== 'completed') {
                $updateData['completed_at'] = now();
                $updateData['completed_by_admin_id'] = $admin?->id;
            } elseif ($validated['status'] === 'pending' && $task->status === 'completed') {
                $updateData['completed_at'] = null;
                $updateData['completed_by_admin_id'] = null;
            }
        }

        $task->update($updateData);

        return redirect()->back()->with('success', "Task updated: '{$task->title}'");
    }

    /**
     * Mark an outreach task as completed.
     */
    public function complete(OutreachTask $task): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $task->markAsCompleted($admin);

        if ($task->booking) {
            ActivityLogService::logBooking('booking.task_completed', "Outreach task marked as completed: '{$task->title}'", $task->booking, [
                'task_id' => $task->id,
            ]);
        }

        return redirect()->back()->with('success', "Task marked as completed: '{$task->title}'");
    }

    /**
     * Delete an outreach task.
     */
    public function destroy(OutreachTask $task): RedirectResponse
    {
        $title = $task->title;
        $task->delete();

        return redirect()->back()->with('success', "Task removed: '{$title}'");
    }
}
