<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AmcShift;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\PartnerNotification;
use App\Services\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerJobController extends Controller
{
    /**
     * Display listing of jobs assigned to this partner.
     */
    public function index(Request $request): View
    {
        $partner = Auth::guard('partner')->user();
        $status = $request->query('status', 'all');

        $query = Booking::where('partner_id', $partner->id)
            ->with(['service', 'amcPlan', 'assignedCrew', 'amcShifts'])
            ->latest('partner_assigned_at')
            ->latest();

        if ($status === 'active') {
            $query->whereIn('partner_job_status', ['assigned', 'accepted', 'on_the_way', 'in_progress']);
        } elseif ($status === 'completed') {
            $query->where(function ($q) {
                $q->where('partner_job_status', 'completed')
                    ->orWhere('status', 'completed');
            });
        } elseif ($status === 'declined') {
            $query->where('partner_job_status', 'declined');
        } elseif ($status !== 'all') {
            $query->where('partner_job_status', $status);
        }

        $jobs = $query->paginate(12)->withQueryString();

        // Completed AMC shifts directly for this partner
        $completedShiftsQuery = AmcShift::where(function ($q) use ($partner) {
            $q->where('partner_id', $partner->id)
                ->orWhere(function ($q2) use ($partner) {
                    $q2->whereNull('partner_id')
                        ->whereHas('booking', fn ($b) => $b->where('partner_id', $partner->id));
                });
        })->where('status', 'completed');

        $completedShiftsCount = $completedShiftsQuery->count();
        $completedBookingsCount = Booking::where('partner_id', $partner->id)
            ->where(function ($q) {
                $q->where('partner_job_status', 'completed')->orWhere('status', 'completed');
            })->count();

        $stats = [
            'total' => Booking::where('partner_id', $partner->id)->count() + $completedShiftsCount,
            'active' => Booking::where('partner_id', $partner->id)->whereIn('partner_job_status', ['assigned', 'accepted', 'on_the_way', 'in_progress'])->count(),
            'completed' => $completedBookingsCount + $completedShiftsCount,
        ];

        // Fetch completed AMC shifts for display when viewing 'completed' or 'all'
        $completedAmcShifts = collect();
        if ($status === 'completed' || $status === 'all') {
            $completedAmcShifts = $completedShiftsQuery
                ->with(['booking.user', 'booking.amcPlan', 'assignedCrew'])
                ->latest('completed_at')
                ->latest('updated_at')
                ->get();
        }

        return view('partner.jobs.index', compact('partner', 'jobs', 'status', 'stats', 'completedAmcShifts'));
    }

    /**
     * Display details of a specific assigned job.
     */
    public function show(Booking $booking): View|RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            $wasNotified = PartnerNotification::where('partner_id', $partner->id)
                ->where('booking_id', $booking->id)
                ->exists();

            if ($wasNotified) {
                return redirect()->route('partner.jobs.index')->with('info', "Work Order #{$booking->booking_number} is no longer assigned to your account or has been cancelled.");
            }

            abort(403, 'Unauthorized access to this booking assignment.');
        }

        $booking->load(['service.category', 'amcPlan', 'assignedCrew', 'statusLogs', 'amcShifts.partner', 'amcNotes']);

        $internalCrews = $partner->isVendor()
            ? $partner->crews()->where('is_active', true)->get()
            : collect();

        $isPriceSetLater = (($booking->estimated_cost === null || $booking->estimated_cost <= 0) && ($booking->final_cost === null || $booking->final_cost <= 0));

        return view('partner.jobs.show', compact('partner', 'booking', 'internalCrews', 'isPriceSetLater'));
    }

    /**
     * Accept newly assigned job.
     */
    public function accept(Request $request, Booking $booking): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $priceNote = '';

        if ($request->filled('agreed_price') && (float) $request->input('agreed_price') > 0) {
            $booking->final_cost = (float) $request->input('agreed_price');
            $booking->estimated_cost = null;
            $priceNote = ' Agreed Price: ৳ '.number_format($booking->final_cost, 2).'.';
        } elseif ($booking->final_cost > 0) {
            $priceNote = ' Agreed Price: ৳ '.number_format($booking->final_cost, 2).'.';
        } elseif ($booking->estimated_cost > 0) {
            $priceNote = ' Estimated Price: ৳ '.number_format($booking->estimated_cost, 2).'.';
        } else {
            $priceNote = ' Price to be agreed and set before work starts on site.';
        }

        $oldStatus = $booking->status;
        $booking->partner_job_status = 'accepted';
        $booking->status = 'assigned';
        $booking->save();

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $oldStatus,
            'to_status' => 'assigned',
            'comment' => "Work order confirmed and scheduled for service.{$priceNote}",
            'is_customer_visible' => true,
        ]);

        AdminNotification::create([
            'type' => 'order_status',
            'title' => "Crew Accepted Job #{$booking->booking_number}",
            'message' => "Partner '{$partner->company_name}' has accepted assignment for Job #{$booking->booking_number}.{$priceNote}",
            'booking_id' => $booking->id,
            'action_url' => route('admin.bookings.show', $booking),
            'is_read' => false,
        ]);

        NotificationDispatcher::notifyAdminSection(
            'orders',
            "Crew Accepted Job #{$booking->booking_number}",
            "Partner '{$partner->company_name}' has accepted assignment for Job #{$booking->booking_number}.{$priceNote}",
            route('admin.bookings.show', $booking),
            [
                'Order' => "#{$booking->booking_number}",
                'Partner' => $partner->company_name,
                'Status' => 'Accepted by Crew',
                'Confirmed Price' => $booking->final_cost > 0 ? '৳ '.number_format($booking->final_cost, 2) : 'Not Set',
            ]
        );

        return back()->with('success', "Job #{$booking->booking_number} accepted successfully!".($booking->final_cost > 0 ? ' (Final Price: ৳ '.number_format($booking->final_cost, 2).')' : ''));
    }

    /**
     * Decline job assignment with reason.
     */
    public function decline(Request $request, Booking $booking): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $reason = $request->input('decline_reason', 'Partner currently unable to service this job');

        $booking->update([
            'partner_job_status' => 'declined',
            'partner_notes' => 'Declined: '.$reason,
            'status' => 'accepted',
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $booking->status,
            'to_status' => 'accepted',
            'comment' => "Partner '{$partner->company_name}' declined assignment: {$reason}. Awaiting admin reassignment.",
            'is_customer_visible' => false,
        ]);

        AdminNotification::create([
            'type' => 'emergency_alert',
            'title' => "Crew Declined Job #{$booking->booking_number}",
            'message' => "Partner '{$partner->company_name}' declined assignment: {$reason}. Immediate reassignment required.",
            'booking_id' => $booking->id,
            'action_url' => route('admin.bookings.show', $booking),
            'is_read' => false,
            'sound_played' => false,
        ]);

        NotificationDispatcher::notifyAdminSection(
            'orders',
            "Partner Declined Job #{$booking->booking_number}",
            "Partner '{$partner->company_name}' declined assignment for Job #{$booking->booking_number}. Reason: {$reason}. Please reassign.",
            route('admin.bookings.show', $booking),
            [
                'Order' => "#{$booking->booking_number}",
                'Partner' => $partner->company_name,
                'Reason' => $reason,
            ]
        );

        return redirect()->route('partner.jobs.index')
            ->with('info', "Job #{$booking->booking_number} declined. Fix dispatch team will be notified.");
    }

    /**
     * Transition job execution status: on_the_way -> in_progress -> completed.
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $request->validate([
            'partner_job_status' => ['required', 'string', 'in:on_the_way,in_progress,completed'],
            'agreed_price' => ['nullable', 'numeric', 'min:0'],
            'price_notes' => ['nullable', 'string', 'max:500'],
            'partner_notes' => ['nullable', 'string', 'max:1000'],
            'partner_worked_hours' => ['nullable', 'numeric', 'min:0.25', 'max:100'],
            'completion_photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'payment_collected_by' => ['nullable', 'required_if:partner_job_status,completed', 'string', 'in:crew,admin'],
            'payment_method' => ['nullable', 'required_if:partner_job_status,completed', 'string', 'in:cash,offline,bkash,nagad,rocket,bank,other'],
            'collected_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_reference' => ['nullable', 'string', 'max:120'],
        ]);

        $newJobStatus = $request->input('partner_job_status');
        $oldJobStatus = $booking->partner_job_status;

        $updateData = [
            'partner_job_status' => $newJobStatus,
        ];

        if ($request->filled('partner_notes')) {
            $updateData['partner_notes'] = $request->input('partner_notes');
        }

        if ($newJobStatus === 'on_the_way') {
            $updateData['status'] = 'on_the_way';
            $comment = 'Technician team is currently on the way to the service address.';

            AdminNotification::create([
                'type' => 'order_status',
                'title' => "Crew Started Journey for Job #{$booking->booking_number}",
                'message' => "Partner '{$partner->company_name}' has started journey and is on the way to customer address.",
                'booking_id' => $booking->id,
                'action_url' => route('admin.bookings.show', $booking),
                'is_read' => false,
            ]);

            NotificationDispatcher::notifyAdminSection(
                'orders',
                "Crew Started Journey for Job #{$booking->booking_number}",
                "Partner '{$partner->company_name}' has started journey and is on the way to customer address for Job #{$booking->booking_number}.",
                route('admin.bookings.show', $booking),
                [
                    'Order' => "#{$booking->booking_number}",
                    'Partner' => $partner->company_name,
                    'Status' => 'En Route / On The Way',
                ]
            );
        } elseif ($newJobStatus === 'in_progress') {
            $isInitialPrice = (($booking->estimated_cost === null || $booking->estimated_cost <= 0) && ($booking->final_cost === null || $booking->final_cost <= 0));
            $isEstimated = ($booking->estimated_cost > 0 && ($booking->final_cost === null || $booking->final_cost <= 0));

            // Mandatory price check if price was set to "later"
            if ($isInitialPrice) {
                if (! $request->filled('agreed_price') || (float) $request->input('agreed_price') <= 0) {
                    return back()
                        ->withInput()
                        ->with('error', 'Price Required: Admin selected "Set price later". You must set and agree on the service price with the customer before starting work.')
                        ->withErrors([
                            'agreed_price' => 'Admin selected "Set price later". You must set and agree on the service price with the customer before starting work.',
                        ]);
                }
            }

            // Handle price set or updated
            if ($request->filled('agreed_price') && (float) $request->input('agreed_price') > 0) {
                $newPrice = (float) $request->input('agreed_price');
                $oldPrice = (float) ($booking->final_cost ?? $booking->estimated_cost ?? 0);

                if ($isInitialPrice || $isEstimated || abs($oldPrice - $newPrice) > 0.01) {
                    $updateData['final_cost'] = $newPrice;
                    $updateData['estimated_cost'] = null;

                    $priceTitle = $isInitialPrice
                        ? "Price Set by Crew for Job #{$booking->booking_number}"
                        : ($isEstimated ? "Final Price Confirmed by Crew for Job #{$booking->booking_number}" : "Price Updated by Crew for Job #{$booking->booking_number}");

                    $priceMsg = $isInitialPrice
                        ? "Partner '{$partner->company_name}' agreed on and set final service price to ৳".number_format($newPrice, 2).' before starting work.'
                        : ($isEstimated ? "Partner '{$partner->company_name}' confirmed final service price as ৳".number_format($newPrice, 2).' (converted from estimate).' : "Partner '{$partner->company_name}' updated service price from ৳".number_format($oldPrice, 2).' to ৳'.number_format($newPrice, 2).($request->filled('price_notes') ? ' • Reason: '.$request->input('price_notes') : ''));

                    AdminNotification::create([
                        'type' => 'price_updated',
                        'title' => $priceTitle,
                        'message' => $priceMsg,
                        'booking_id' => $booking->id,
                        'action_url' => route('admin.bookings.show', $booking),
                        'is_read' => false,
                    ]);

                    NotificationDispatcher::notifyAdminSection(
                        'orders',
                        $priceTitle,
                        $priceMsg,
                        route('admin.bookings.show', $booking),
                        [
                            'Order' => "#{$booking->booking_number}",
                            'Partner' => $partner->company_name,
                            'Confirmed Final Price' => '৳ '.number_format($newPrice, 2),
                            'Previous Price' => $oldPrice > 0 ? '৳ '.number_format($oldPrice, 2) : 'Not Set',
                        ]
                    );

                    BookingStatusLog::create([
                        'booking_id' => $booking->id,
                        'from_status' => $booking->status,
                        'to_status' => $booking->status,
                        'comment' => 'Final service price confirmed: ৳'.number_format($newPrice, 2).'.',
                        'is_customer_visible' => true,
                    ]);
                }
            } elseif ($isEstimated) {
                $updateData['final_cost'] = $booking->estimated_cost;
                $updateData['estimated_cost'] = null;
            }

            $updateData['status'] = 'in_progress';
            $comment = 'Service work started on site.';

            AdminNotification::create([
                'type' => 'order_status',
                'title' => "Work Started on Job #{$booking->booking_number}",
                'message' => "Partner '{$partner->company_name}' has started work on site.",
                'booking_id' => $booking->id,
                'action_url' => route('admin.bookings.show', $booking),
                'is_read' => false,
            ]);

            NotificationDispatcher::notifyAdminSection(
                'orders',
                "Work Started on Job #{$booking->booking_number}",
                "Partner '{$partner->company_name}' has started work on site for Job #{$booking->booking_number}.",
                route('admin.bookings.show', $booking),
                [
                    'Order' => "#{$booking->booking_number}",
                    'Partner' => $partner->company_name,
                    'Status' => 'Working / In Progress',
                ]
            );
        } elseif ($newJobStatus === 'completed') {
            $updateData['status'] = 'completed';
            $updateData['completed_at'] = now();

            $collectedBy = $request->input('payment_collected_by', 'crew');
            $payMethod = $request->input('payment_method', 'cash');
            $finalAmount = (float) ($booking->final_cost ?? $booking->estimated_cost ?? 0);
            $collectedAmount = $request->filled('collected_amount') ? (float) $request->input('collected_amount') : $finalAmount;

            $updateData['payment_status'] = 'paid';
            $updateData['payment_collected_by'] = $collectedBy;
            $updateData['payment_method'] = $payMethod;
            $updateData['collected_amount'] = $collectedAmount;
            $updateData['payment_reference'] = $request->input('payment_reference');
            $updateData['payment_collected_at'] = now();

            $collectorLabel = $collectedBy === 'crew' ? 'Crew Collected on Site' : 'Client Paid to Admin/Office';
            $methodLabel = strtoupper($payMethod);
            $comment = 'Service work completed on site. Payment: ৳'.number_format($collectedAmount, 2)." ({$collectorLabel} via {$methodLabel}).";

            if ($request->filled('partner_worked_hours')) {
                $updateData['partner_worked_hours'] = (float) $request->input('partner_worked_hours');
            }

            // Process uploaded completion photos
            if ($request->hasFile('completion_photos')) {
                $photoPaths = $booking->completion_photos ?? [];
                foreach ($request->file('completion_photos') as $photo) {
                    $path = $photo->store('completion_photos', 'public');
                    $photoPaths[] = $path;
                }
                $updateData['completion_photos'] = $photoPaths;
            }

            // Create admin in-app notification
            AdminNotification::create([
                'type' => 'order_status',
                'title' => "Job #{$booking->booking_number} Completed ({$collectorLabel})",
                'message' => "Partner '{$partner->company_name}' completed service on site. Payment ৳".number_format($collectedAmount, 2)." marked as {$collectorLabel} via {$methodLabel}.".($request->filled('payment_reference') ? " (Ref: {$request->input('payment_reference')})" : ''),
                'booking_id' => $booking->id,
                'action_url' => route('admin.bookings.show', $booking),
                'is_read' => false,
            ]);

            // Dispatch operational alert to admin team
            NotificationDispatcher::notifyAdminSection(
                'orders',
                "Job #{$booking->booking_number} Completed ({$collectorLabel})",
                "Partner '{$partner->company_name}' has completed on-site service for Job #{$booking->booking_number}. Payment: ৳".number_format($collectedAmount, 2)." ({$collectorLabel} via {$methodLabel}).",
                route('admin.bookings.show', $booking),
                [
                    'Order' => "#{$booking->booking_number}",
                    'Partner' => $partner->company_name,
                    'Payment Status' => '৳ '.number_format($collectedAmount, 2)." ({$collectorLabel})",
                    'Completed At' => now()->format('M d, Y h:i A'),
                ]
            );
        }

        $booking->update($updateData);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $booking->status,
            'to_status' => $updateData['status'] ?? $booking->status,
            'comment' => $comment ?? "Partner status updated to {$newJobStatus}",
            'is_customer_visible' => true,
        ]);

        return back()->with('success', "Work status updated to '".ucwords(str_replace('_', ' ', $newJobStatus))."'!");
    }

    /**
     * Crew reviews and updates agreed price.
     */
    public function updatePrice(Request $request, Booking $booking): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $validated = $request->validate([
            'agreed_price' => ['required', 'numeric', 'min:1'],
            'price_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $newPrice = (float) $validated['agreed_price'];
        $oldPrice = (float) ($booking->final_cost ?? $booking->estimated_cost ?? 0);

        $booking->update([
            'estimated_cost' => $newPrice,
        ]);

        $priceMsg = "Partner '{$partner->company_name}' updated service price to ৳".number_format($newPrice, 2).($oldPrice > 0 ? ' (Previous: ৳'.number_format($oldPrice, 2).')' : '').($validated['price_notes'] ? ' • Reason: '.$validated['price_notes'] : '');

        AdminNotification::create([
            'type' => 'price_updated',
            'title' => "Price Revised by Crew for Job #{$booking->booking_number}",
            'message' => $priceMsg,
            'booking_id' => $booking->id,
            'action_url' => route('admin.bookings.show', $booking),
            'is_read' => false,
        ]);

        NotificationDispatcher::notifyAdminSection(
            'orders',
            "Price Revised for Job #{$booking->booking_number}",
            $priceMsg,
            route('admin.bookings.show', $booking),
            [
                'Order' => "#{$booking->booking_number}",
                'Partner' => $partner->company_name,
                'New Price' => '৳ '.number_format($newPrice, 2),
                'Previous Price' => $oldPrice > 0 ? '৳ '.number_format($oldPrice, 2) : 'Not Set',
            ]
        );

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $booking->status,
            'to_status' => $booking->status,
            'comment' => 'Service price updated to ৳'.number_format($newPrice, 2).'.',
            'is_customer_visible' => true,
        ]);

        return back()->with('success', 'Service price updated to ৳'.number_format($newPrice, 2).' successfully.');
    }

    /**
     * Assign internal technician / crew member (Vendor internal management).
     */
    public function assignInternalCrew(Request $request, Booking $booking): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $validated = $request->validate([
            'assigned_partner_crew_id' => ['nullable', 'exists:partner_crews,id'],
        ]);

        $crewId = $validated['assigned_partner_crew_id'] ?: null;
        if ($crewId) {
            // Verify crew belongs to this partner
            $crewExists = $partner->crews()->where('id', $crewId)->exists();
            if (! $crewExists) {
                return back()->withErrors(['assigned_partner_crew_id' => 'Invalid crew member selected.']);
            }
        }

        $booking->update([
            'assigned_partner_crew_id' => $crewId,
        ]);

        return back()->with('success', 'Internal field crew assigned to this job.');
    }

    /**
     * Mark an individual AMC routine shift as completed by crew.
     */
    public function completeAmcShift(Request $request, Booking $booking, AmcShift $shift): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $shift->partner_id !== (int) $partner->id && (int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        if ((int) $shift->booking_id !== (int) $booking->id) {
            abort(404);
        }

        $validated = $request->validate([
            'completion_notes' => ['nullable', 'string', 'max:1500'],
            'payment_collected' => ['nullable', 'in:yes,no'],
            'collected_amount' => ['nullable', 'required_if:payment_collected,yes', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'required_if:payment_collected,yes', 'string', 'max:100'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $paymentCollected = ($request->input('payment_collected') === 'yes');
        $collectedAmount = $paymentCollected ? (float) $request->input('collected_amount', 0) : 0.0;
        $paymentMethod = $paymentCollected ? $request->input('payment_method', 'Cash') : null;
        $paymentReference = $paymentCollected ? $request->input('payment_reference') : null;

        $payout = $shift->payout_amount ?: ($booking->payout_per_shift ?: $partner->calculateShiftEarning($shift));

        // If payment was collected directly on site from client, payout is immediately payable to crew
        $payoutStatus = ($paymentCollected || $booking->amc_billing_type === 'per_shift' || $booking->payment_status === 'paid')
            ? 'payable'
            : 'pending_client_payment';

        $shift->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $validated['completion_notes'] ?? 'Shift completed as per AMC routine schedule.',
            'crew_notes' => $validated['completion_notes'] ?? null,
            'payout_amount' => $payout,
            'payout_status' => $payoutStatus,
            'payment_collected_by' => $paymentCollected ? 'crew' : null,
            'collected_amount' => $collectedAmount,
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
        ]);

        $paymentLogInfo = $paymentCollected
            ? ' Client payment collected on site: ৳ '.number_format($collectedAmount, 2)." via {$paymentMethod}".($paymentReference ? " (Ref: {$paymentReference})" : '').'.'
            : ' No on-site payment collected (Office/Monthly invoicing).';

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $booking->status,
            'to_status' => $booking->status,
            'comment' => "Partner '{$partner->company_name}' completed AMC Shift #{$shift->shift_number} ({$shift->scheduled_date->format('M d, Y')}).{$paymentLogInfo} Crew shift payout: ৳ ".number_format($payout, 2)." ({$payoutStatus}).".(! empty($validated['completion_notes']) ? " Remarks: {$validated['completion_notes']}" : ''),
            'is_customer_visible' => true,
        ]);

        $notifMsg = "Partner '{$partner->company_name}' completed Shift #{$shift->shift_number} for AMC #{$booking->booking_number}."
            .($paymentCollected ? ' Collected ৳ '.number_format($collectedAmount, 2)." via {$paymentMethod} on site." : ' No payment collected on site.');

        AdminNotification::create([
            'type' => 'order_status',
            'title' => "AMC Shift #{$shift->shift_number} Completed: #{$booking->booking_number}",
            'message' => $notifMsg,
            'booking_id' => $booking->id,
            'action_url' => route('admin.bookings.show', $booking).'#tab-routine-shifts',
            'is_read' => false,
            'sound_played' => false,
        ]);

        return back()->with('success', "AMC Shift #{$shift->shift_number} marked as COMPLETED successfully.".($paymentCollected ? ' Recorded ৳ '.number_format($collectedAmount, 2)." ({$paymentMethod}) collected on site." : ''));
    }

    /**
     * Cancel / flag an individual AMC shift with reason by crew so admin can reassign.
     */
    public function cancelAmcShift(Request $request, Booking $booking, AmcShift $shift): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $shift->partner_id !== (int) $partner->id && (int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        if ((int) $shift->booking_id !== (int) $booking->id) {
            abort(404);
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $shift->update([
            'status' => 'reassignment_needed',
            'cancelled_by' => 'partner',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $booking->status,
            'to_status' => $booking->status,
            'comment' => "Partner '{$partner->company_name}' declined/cancelled AMC Shift #{$shift->shift_number}: {$validated['cancellation_reason']}. Immediate reassignment needed.",
            'is_customer_visible' => true,
        ]);

        AdminNotification::create([
            'type' => 'emergency_alert',
            'title' => "⚠️ AMC Shift #{$shift->shift_number} Cancelled by Crew",
            'message' => "Partner '{$partner->company_name}' cancelled Shift #{$shift->shift_number} for AMC #{$booking->booking_number}. Reason: {$validated['cancellation_reason']}. Please reassign a new crew.",
            'booking_id' => $booking->id,
            'action_url' => route('admin.bookings.show', $booking).'#tab-routine-shifts',
            'is_read' => false,
            'sound_played' => false,
        ]);

        return back()->with('success', "Shift #{$shift->shift_number} has been flagged. Admin notified for crew reassignment.");
    }

    /**
     * Cancel / relinquish the entire AMC assignment by crew so admin can reassign to another crew.
     */
    public function cancelAmcDealAssignment(Request $request, Booking $booking): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $booking->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $validated = $request->validate([
            'crew_cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $reason = $validated['crew_cancellation_reason'];

        $booking->update([
            'crew_cancellation_reason' => $reason,
            'crew_cancellation_requested_at' => now(),
            'partner_job_status' => 'declined',
        ]);

        // Mark remaining scheduled shifts for reassignment
        $booking->amcShifts()
            ->where('status', 'scheduled')
            ->update([
                'status' => 'reassignment_needed',
                'admin_notes' => "Previous crew ({$partner->company_name}) relinquished assignment: {$reason}",
            ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $booking->status,
            'to_status' => $booking->status,
            'comment' => "Partner '{$partner->company_name}' relinquished AMC Contract assignment: {$reason}. Deal awaiting admin reassignment.",
            'is_customer_visible' => false,
        ]);

        AdminNotification::create([
            'type' => 'emergency_alert',
            'title' => "Crew Relinquished AMC Contract #{$booking->booking_number}",
            'message' => "Partner '{$partner->company_name}' cancelled assignment for AMC #{$booking->booking_number}. Reason: {$reason}. Immediate reassignment required.",
            'booking_id' => $booking->id,
            'action_url' => route('admin.bookings.show', $booking),
            'is_read' => false,
            'sound_played' => false,
        ]);

        return redirect()->route('partner.jobs.index')->with('success', 'You have relinquished assignment for this AMC Contract. FIX Operations has been alerted.');
    }
}
