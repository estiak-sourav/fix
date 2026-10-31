<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Booking\UpdateBookingStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\AmcInvoice;
use App\Models\AmcPlan;
use App\Models\Booking;
use App\Models\OutreachTask;
use App\Models\Partner;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
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
        $query = Booking::with(['user', 'service', 'amcPlan', 'statusLogs', 'amcInvoices'])->latest();

        // 1. Order Type / Service vs AMC filter
        $serviceType = $request->query('service_type', $request->query('type'));
        if ($serviceType === 'service' || $serviceType === 'regular') {
            $query->where(function ($q) {
                $q->where('service_type', '!=', 'amc_contract')
                    ->orWhereNull('service_type');
            })->whereNull('amc_plan_id');
        } elseif ($serviceType === 'amc' || $serviceType === 'amc_contract') {
            $query->where(function ($q) {
                $q->where('service_type', 'amc_contract')
                    ->orWhereNotNull('amc_plan_id');
            });
        }

        // 2. AMC Contract Status filter (Running / Active vs Expired)
        $amcStatus = $request->query('amc_status');
        if ($amcStatus === 'running' || $amcStatus === 'active') {
            $query->where(function ($q) {
                $q->where('service_type', 'amc_contract')
                    ->orWhereNotNull('amc_plan_id');
            })->where('contract_status', 'active')
                ->where(function ($q) {
                    $q->whereNull('contract_end_date')
                        ->orWhereDate('contract_end_date', '>=', today());
                });
        } elseif ($amcStatus === 'expired') {
            $query->where(function ($q) {
                $q->where('service_type', 'amc_contract')
                    ->orWhereNotNull('amc_plan_id');
            })->whereDate('contract_end_date', '<', today());
        }

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

        $typeCounts = [
            'all' => Booking::count(),
            'regular' => Booking::where(function ($q) {
                $q->where('service_type', '!=', 'amc_contract')
                    ->orWhereNull('service_type');
            })->whereNull('amc_plan_id')->count(),
            'amc' => Booking::where(function ($q) {
                $q->where('service_type', 'amc_contract')
                    ->orWhereNotNull('amc_plan_id');
            })->count(),
            'amc_running' => Booking::where(function ($q) {
                $q->where('service_type', 'amc_contract')
                    ->orWhereNotNull('amc_plan_id');
            })->where('contract_status', 'active')
                ->where(function ($q) {
                    $q->whereNull('contract_end_date')
                        ->orWhereDate('contract_end_date', '>=', today());
                })->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'availableDivisions', 'availableDistricts', 'availableAreas', 'statusCounts', 'typeCounts'));
    }

    /**
     * View detailed single booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'service', 'amcPlan', 'statusLogs.changedByUser', 'statusLogs.changedByAdmin', 'amcInvoices.collectedByAdmin']);

        $fieldCrews = User::whereIn('role', ['technician', 'vendor', 'partner'])
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'role', 'company_name']);

        $enlistedPartners = Partner::where('status', 'approved')->orderBy('company_name')->get();

        $crewOptions = collect();
        foreach ($enlistedPartners as $p) {
            $crewOptions->push((object) [
                'name' => "{$p->company_name} — {$p->contact_person}",
                'phone' => $p->phone,
                'role' => "Enlisted Partner • {$p->trade_domain}",
                'company_name' => $p->company_name,
            ]);
        }
        foreach ($fieldCrews as $u) {
            $crewOptions->push((object) [
                'name' => $u->name,
                'phone' => $u->phone,
                'role' => ucfirst($u->role),
                'company_name' => $u->company_name,
            ]);
        }

        $amcPlans = AmcPlan::where('is_active', true)->orderBy('order')->get();

        return view('admin.bookings.show', [
            'booking' => $booking,
            'fieldCrews' => $crewOptions,
            'amcPlans' => $amcPlans,
        ]);
    }

    /**
     * Log phone contact status (e.g. unreachable, call back later).
     */
    public function logCallStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'call_status' => ['required', 'string', 'in:unreachable,call_back_later,connected'],
            'unreachable_reason' => ['nullable', 'string', 'max:255'],
            'callback_time' => ['nullable', 'string', 'max:255'],
            'call_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();
        $notesCombined = $validated['call_notes'] ?? '';
        if ($validated['call_status'] === 'unreachable' && ! empty($validated['unreachable_reason'])) {
            $notesCombined = 'Reason: '.$validated['unreachable_reason'].($notesCombined ? ' • Note: '.$notesCombined : '');
        } elseif ($validated['call_status'] === 'call_back_later' && ! empty($validated['callback_time'])) {
            $notesCombined = 'Follow-up: '.$validated['callback_time'].($notesCombined ? ' • Note: '.$notesCombined : '');
        }

        $booking->update([
            'call_status' => $validated['call_status'],
            'call_notes' => $notesCombined ?: null,
        ]);

        $statusText = match ($validated['call_status']) {
            'unreachable' => 'Client Unreachable / No Answer over phone',
            'call_back_later' => 'Client requested Callback Later / Follow-up required',
            'connected' => 'Phone discussion connected with client',
            default => 'Phone call status updated'
        };
        $logComment = $statusText.($notesCombined ? ' ('.$notesCombined.')' : '');

        $booking->statusLogs()->create([
            'from_status' => $booking->status,
            'to_status' => $booking->status,
            'comment' => $logComment,
            'is_customer_visible' => false,
            'admin_id' => $admin?->id,
        ]);

        // Automatically create or resolve OutreachTask based on call outcome
        if ($validated['call_status'] === 'unreachable') {
            $reasonText = ! empty($validated['unreachable_reason']) ? $validated['unreachable_reason'] : 'Client unreachable / no answer';
            OutreachTask::create([
                'booking_id' => $booking->id,
                'admin_id' => $admin?->id,
                'created_by_admin_id' => $admin?->id,
                'title' => "Retry Call: {$booking->customer_name}",
                'task_type' => 'call_unreachable',
                'customer_name' => $booking->customer_name,
                'customer_phone' => $booking->customer_phone,
                'priority' => $booking->urgency === 'emergency_1h' ? 'urgent' : 'high',
                'status' => 'pending',
                'due_at' => now()->addMinutes(30),
                'notes' => $notesCombined ?: $reasonText,
                'is_automated' => true,
            ]);
        } elseif ($validated['call_status'] === 'call_back_later') {
            $callbackText = ! empty($validated['callback_time']) ? $validated['callback_time'] : 'Call back requested';
            $dueAt = now()->addHours(2);
            $lower = strtolower($callbackText);
            if (str_contains($lower, '1 hour')) {
                $dueAt = now()->addHour();
            } elseif (str_contains($lower, 'afternoon')) {
                $dueAt = now()->hour < 14 ? now()->setHour(14)->setMinute(0) : now()->addHours(2);
            } elseif (str_contains($lower, 'evening')) {
                $dueAt = now()->hour < 18 ? now()->setHour(18)->setMinute(0) : now()->addHours(2);
            } elseif (str_contains($lower, 'tomorrow')) {
                $dueAt = now()->addDay()->setHour(10)->setMinute(0);
            }

            OutreachTask::create([
                'booking_id' => $booking->id,
                'admin_id' => $admin?->id,
                'created_by_admin_id' => $admin?->id,
                'title' => "Follow-up Call: {$booking->customer_name}",
                'task_type' => 'call_back_later',
                'customer_name' => $booking->customer_name,
                'customer_phone' => $booking->customer_phone,
                'priority' => $booking->urgency === 'emergency_1h' ? 'urgent' : 'high',
                'status' => 'pending',
                'due_at' => $dueAt,
                'notes' => $notesCombined ?: $callbackText,
                'is_automated' => true,
            ]);
        } elseif ($validated['call_status'] === 'connected') {
            $booking->outreachTasks()->where('status', 'pending')->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by_admin_id' => $admin?->id,
            ]);
        }

        ActivityLogService::logBooking('booking.call_status', "Phone call log for #{$booking->booking_number}: {$statusText}.", $booking, [
            'call_status' => $validated['call_status'],
            'call_notes' => $validated['call_notes'] ?? null,
        ]);

        return redirect()->back()->with('success', "Call status updated: {$statusText} (Outreach Task updated)");
    }

    /**
     * Accept a pending order after phone discussion with the client.
     * Price can be set now (Estimated or Final) or left for post-completion billing.
     */
    public function accept(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'set_price' => ['nullable', 'boolean'],
            'cost_type' => ['nullable', 'string', 'in:estimated,final'],
            'cost_amount' => ['nullable', 'numeric', 'min:0'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time_slot' => ['nullable', 'string', 'max:100'],
            'assigned_team' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();

        // Update schedule if provided
        $directUpdates = [];
        if (! empty($validated['preferred_date'])) {
            $directUpdates['preferred_date'] = $validated['preferred_date'];
        }
        if (! empty($validated['preferred_time_slot'])) {
            $directUpdates['preferred_time_slot'] = $validated['preferred_time_slot'];
        }
        if (! empty($directUpdates)) {
            $booking->update($directUpdates);
        }

        $pricingMode = $request->input('pricing_mode', 'price'); // 'price' or 'later'
        if ($pricingMode === 'price') {
            $request->validate([
                'cost_amount' => ['required', 'numeric', 'min:0.01'],
            ], [
                'cost_amount.required' => 'Service price amount is required when Price agreement is selected.',
            ]);
        }

        $isEstimated = (bool) $request->input('is_estimated', false);
        $costAmount = isset($validated['cost_amount']) && $validated['cost_amount'] !== '' ? (float) $validated['cost_amount'] : null;
        $isPriceSet = ($pricingMode === 'price') && $costAmount !== null && $costAmount > 0;
        $costType = $isEstimated ? 'estimated' : 'final';

        $comment = $validated['comment'] ?? null;
        if (! $comment) {
            if ($isPriceSet) {
                $typeLabel = $isEstimated ? 'Agreed Estimated Price' : 'Final Invoiced Price';
                $comment = "Order confirmed with client via phone. {$typeLabel}: ৳ ".number_format($costAmount, 2);
            } else {
                $comment = 'Order confirmed with client via phone. Pricing to be finalized upon job completion / inspection.';
            }
        }

        // Also mark call as connected since phone discussion took place
        $booking->update(['call_status' => 'connected']);

        // Auto-complete any pending outreach tasks for this booking
        $booking->outreachTasks()->where('status', 'pending')->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by_admin_id' => $admin?->id,
        ]);

        $assignedTeam = ! empty($validated['assigned_team']) ? trim($validated['assigned_team']) : $booking->assigned_team;
        $status = ! empty($assignedTeam) ? 'in_progress' : 'accepted';

        $payload = [
            'status' => $status,
            'assigned_team' => $assignedTeam,
            'comment' => $comment,
            'admin_notes' => "Order accepted by {$admin->name}. Pricing: ".($isPriceSet ? "৳ {$costAmount} ({$costType})" : 'Billed upon completion').'.',
        ];

        if ($isPriceSet) {
            if ($costType === 'final') {
                $payload['final_cost'] = $costAmount;
            } else {
                $payload['estimated_cost'] = $costAmount;
            }
        }

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.accept', "Accepted Work Order #{$booking->booking_number} after phone discussion with {$booking->customer_name}.", $booking, [
            'booking_number' => $booking->booking_number,
            'is_price_set' => $isPriceSet,
            'cost_type' => $costType,
            'cost_amount' => $costAmount,
            'assigned_team' => $payload['assigned_team'],
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} has been ACCEPTED successfully.");
    }

    /**
     * Mark a work order as completed with admin remarks/feedback for the client.
     */
    public function complete(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        if (empty($booking->assigned_team)) {
            return redirect()->back()->with('error', 'Cannot complete work order: A field crew or technician team must be assigned before marking as completed.');
        }

        $validated = $request->validate([
            'completion_feedback' => ['nullable', 'string', 'max:2000'],
            'completion_remarks' => ['nullable', 'string', 'max:2000'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $feedback = $validated['completion_remarks'] ?? ($validated['completion_feedback'] ?? 'Work completed successfully.');

        $admin = Auth::guard('admin')->user();

        $booking->completion_feedback = $feedback;
        if (! empty($validated['final_cost'])) {
            $booking->final_cost = (float) $validated['final_cost'];
        }
        $booking->save();

        $payload = [
            'status' => 'completed',
            'comment' => 'Work completed: '.$feedback,
            'admin_notes' => 'Completed by '.$admin->name.'. Feedback for client: '.$feedback,
        ];
        if (! empty($validated['final_cost'])) {
            $payload['final_cost'] = (float) $validated['final_cost'];
        }

        $action->execute($booking, $payload, $admin);

        ActivityLogService::logBooking('booking.complete', "Marked Work Order #{$booking->booking_number} as COMPLETED with feedback for client.", $booking, [
            'booking_number' => $booking->booking_number,
            'feedback' => $validated['completion_feedback'],
            'final_cost' => $booking->final_cost,
        ]);

        return redirect()->back()->with('success', "Work Order #{$booking->booking_number} marked as COMPLETED. Client can now submit feedback & rating.");
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

        $newStatus = $validated['status'];
        $assignedTeam = isset($validated['assigned_team']) ? trim($validated['assigned_team']) : $booking->assigned_team;

        // Auto change status to in_progress if crew is assigned and order was pending, accepted or assigned
        if (! empty($assignedTeam) && in_array($newStatus, ['pending_review', 'accepted', 'assigned'])) {
            $newStatus = 'in_progress';
        }

        $payload = [
            'status' => $newStatus,
            'assigned_team' => $assignedTeam,
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
     * Activate AMC Annual Contract with client agreement details and document copy.
     */
    public function activateAmcContract(Request $request, Booking $booking, UpdateBookingStatusAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'amc_plan_id' => ['required', 'exists:amc_plans,id'],
            'contract_start_date' => ['required', 'date'],
            'contract_end_date' => ['required', 'date', 'after_or_equal:contract_start_date'],
            'billing_cycle' => ['nullable', 'in:daily,weekly,half_monthly,monthly,quarterly,yearly'],
            'recurring_amount' => ['nullable', 'numeric', 'min:0'],
            'billing_day' => ['nullable', 'integer', 'between:1,31'],
            'first_bill_due_date' => ['nullable', 'date'],
            'contract_cost' => ['nullable', 'numeric', 'min:0'],
            'amc_package_notes' => ['nullable', 'string', 'max:2000'],
            'agreement_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,pdf', 'max:10240'],
        ]);

        $admin = Auth::guard('admin')->user();
        $billingCycle = $validated['billing_cycle'] ?? $booking->billing_cycle ?? 'monthly';
        $recurringAmount = isset($validated['recurring_amount']) && $validated['recurring_amount'] !== ''
            ? (float) $validated['recurring_amount']
            : (isset($validated['contract_cost']) ? (float) $validated['contract_cost'] : 0);
        $billingDay = ! empty($validated['billing_day']) ? (int) $validated['billing_day'] : 1;
        $firstDue = ! empty($validated['first_bill_due_date']) ? $validated['first_bill_due_date'] : $validated['contract_start_date'];

        $updates = [
            'amc_plan_id' => $validated['amc_plan_id'],
            'service_type' => 'amc_contract',
            'contract_start_date' => $validated['contract_start_date'],
            'contract_end_date' => $validated['contract_end_date'],
            'contract_status' => 'active',
            'billing_cycle' => $billingCycle,
            'recurring_amount' => $recurringAmount,
            'billing_day' => $billingDay,
            'next_billing_date' => $firstDue,
            'amc_package_notes' => $validated['amc_package_notes'] ?? null,
        ];

        if (isset($validated['contract_cost']) && $validated['contract_cost'] !== '') {
            $updates['final_cost'] = (float) $validated['contract_cost'];
        } elseif ($recurringAmount > 0) {
            $updates['final_cost'] = $recurringAmount;
        }

        if ($request->hasFile('agreement_file')) {
            $path = $request->file('agreement_file')->store('agreements', 'public');
            $updates['agreement_document_path'] = $path;
        }

        $booking->update($updates);

        // Generate initial recurring invoice if none exist
        if ($booking->amcInvoices()->count() === 0 && $recurringAmount > 0) {
            $periodStart = Carbon::parse($validated['contract_start_date']);
            $periodEnd = match ($billingCycle) {
                'daily' => $periodStart->copy()->addDay(),
                'weekly' => $periodStart->copy()->addWeek(),
                'half_monthly' => $periodStart->copy()->addDays(15),
                'quarterly' => $periodStart->copy()->addMonths(3),
                'yearly' => $periodStart->copy()->addYear(),
                default => $periodStart->copy()->addMonth(),
            };

            $booking->amcInvoices()->create([
                'invoice_number' => 'AMC-'.$booking->booking_number.'-01',
                'billing_cycle_label' => ucfirst($billingCycle).' Recurring Cycle #1',
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'due_date' => $firstDue,
                'amount' => $recurringAmount,
                'status' => 'unpaid',
                'notes' => 'Initial billing cycle invoice scheduled upon contract activation.',
            ]);
        }

        $plan = AmcPlan::find($validated['amc_plan_id']);
        $planName = $plan ? $plan->name : 'AMC Plan';

        // Update status to accepted or in_progress
        $newStatus = in_array($booking->status, ['assigned', 'in_progress', 'completed']) ? $booking->status : 'accepted';
        if ($booking->status === 'pending_review' || $booking->status === 'accepted') {
            $action->execute($booking, [
                'status' => $newStatus,
                'comment' => "Annual Maintenance Contract ({$planName}) finalized and activated after site inspection. Agreement document securely filed.",
                'admin_notes' => "AMC Contract activated by {$admin->name}.",
            ], $admin);
        }

        ActivityLogService::logBooking('amc.contract.activated', "Activated AMC Contract ({$planName}) for Work Order #{$booking->booking_number}.", $booking, [
            'booking_number' => $booking->booking_number,
            'amc_plan' => $planName,
            'start_date' => $validated['contract_start_date'],
            'end_date' => $validated['contract_end_date'],
            'billing_cycle' => $billingCycle,
            'recurring_amount' => $recurringAmount,
            'has_agreement_file' => ! empty($updates['agreement_document_path']),
        ]);

        return redirect()->back()->with('success', "AMC Contract ({$planName}) has been successfully ACTIVATED for Work Order #{$booking->booking_number}.");
    }

    /**
     * Record payment and collect an AMC recurring bill invoice.
     */
    public function collectAmcBill(Request $request, Booking $booking, AmcInvoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();
        $paidDate = ! empty($validated['paid_at']) ? Carbon::parse($validated['paid_at']) : now();

        $invoice->update([
            'status' => 'paid',
            'paid_at' => $paidDate,
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'collected_by_admin_id' => $admin->id,
            'notes' => $validated['notes'] ?? $invoice->notes,
        ]);

        // Calculate and advance next billing date
        $nextDate = match ($booking->billing_cycle) {
            'daily' => ($invoice->period_end ?: now())->copy()->addDay(),
            'weekly' => ($invoice->period_end ?: now())->copy()->addWeek(),
            'half_monthly' => ($invoice->period_end ?: now())->copy()->addDays(15),
            'quarterly' => ($invoice->period_end ?: now())->copy()->addMonths(3),
            'yearly' => ($invoice->period_end ?: now())->copy()->addYear(),
            default => ($invoice->period_end ?: now())->copy()->addMonth(),
        };

        if (! $booking->contract_end_date || $nextDate->lte($booking->contract_end_date)) {
            $booking->update(['next_billing_date' => $nextDate]);
        }

        ActivityLogService::logBooking('amc.bill.collected', "Collected AMC recurring bill #{$invoice->invoice_number} (৳ ".number_format($invoice->amount, 2).") via {$validated['payment_method']}.", $booking, [
            'booking_number' => $booking->booking_number,
            'invoice_number' => $invoice->invoice_number,
            'amount' => $invoice->amount,
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['payment_reference'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Payment of ৳ '.number_format($invoice->amount, 2)." successfully collected for Invoice #{$invoice->invoice_number}.");
    }

    /**
     * Generate next recurring cycle bill for an active AMC contract.
     */
    public function generateNextAmcBill(Request $request, Booking $booking): RedirectResponse
    {
        if (! $booking->is_amc || $booking->contract_status !== 'active') {
            return redirect()->back()->with('error', 'Recurring bills can only be generated for active AMC contracts.');
        }

        $latestInvoice = $booking->amcInvoices()->latest('period_end')->first();
        $cycleCount = $booking->amcInvoices()->count() + 1;

        $periodStart = $latestInvoice && $latestInvoice->period_end
            ? Carbon::parse($latestInvoice->period_end)
            : ($booking->contract_start_date ?: today());

        $periodEnd = match ($booking->billing_cycle) {
            'daily' => $periodStart->copy()->addDay(),
            'weekly' => $periodStart->copy()->addWeek(),
            'half_monthly' => $periodStart->copy()->addDays(15),
            'quarterly' => $periodStart->copy()->addMonths(3),
            'yearly' => $periodStart->copy()->addYear(),
            default => $periodStart->copy()->addMonth(),
        };

        $dueDate = $booking->next_billing_date ?: $periodStart;
        $amount = (float) ($booking->recurring_amount ?: $booking->final_cost ?: 0);
        $padded = str_pad((string) $cycleCount, 2, '0', STR_PAD_LEFT);

        $invoice = $booking->amcInvoices()->create([
            'invoice_number' => "AMC-{$booking->booking_number}-{$padded}",
            'billing_cycle_label' => ucfirst($booking->billing_cycle ?: 'monthly')." Recurring Cycle #{$cycleCount}",
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'due_date' => $dueDate,
            'amount' => $amount,
            'status' => 'unpaid',
            'notes' => 'Recurring cycle invoice generated by '.Auth::guard('admin')->user()->name.'.',
        ]);

        ActivityLogService::logBooking('amc.bill.generated', "Generated next AMC recurring invoice #{$invoice->invoice_number} (৳ ".number_format($amount, 2).').', $booking);

        return redirect()->back()->with('success', "Next cycle invoice #{$invoice->invoice_number} has been generated successfully.");
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
