<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
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
            ->with(['service', 'amcPlan', 'assignedCrew'])
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

        $stats = [
            'total' => Booking::where('partner_id', $partner->id)->count(),
            'active' => Booking::where('partner_id', $partner->id)->whereIn('partner_job_status', ['assigned', 'accepted', 'on_the_way', 'in_progress'])->count(),
            'completed' => Booking::where('partner_id', $partner->id)->where(function ($q) {
                $q->where('partner_job_status', 'completed')->orWhere('status', 'completed');
            })->count(),
        ];

        return view('partner.jobs.index', compact('partner', 'jobs', 'status', 'stats'));
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

        $booking->load(['service.category', 'amcPlan', 'assignedCrew', 'statusLogs']);

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

        $isPriceSetLater = ($booking->final_cost === null || $booking->final_cost <= 0) && ($booking->estimated_cost === null || $booking->estimated_cost <= 0);
        $isEstimated = ($booking->estimated_cost > 0 && ($booking->final_cost === null || $booking->final_cost <= 0));

        $priceNote = '';

        if ($isPriceSetLater) {
            $validated = $request->validate([
                'agreed_price' => ['required', 'numeric', 'min:1'],
            ], [
                'agreed_price.required' => 'You must set the service price before accepting this job.',
                'agreed_price.min' => 'Service price must be at least ৳ 1.',
            ]);

            $finalPrice = (float) $validated['agreed_price'];
            $booking->final_cost = $finalPrice;
            $booking->estimated_cost = null;
            $priceNote = ' Agreed Price: ৳ '.number_format($finalPrice, 2).'.';
        } elseif ($isEstimated) {
            $validated = $request->validate([
                'agreed_price' => ['required', 'numeric', 'min:1'],
            ], [
                'agreed_price.required' => 'Please confirm or set the final service price before accepting.',
                'agreed_price.min' => 'Service price must be at least ৳ 1.',
            ]);

            $finalPrice = (float) $validated['agreed_price'];
            $booking->final_cost = $finalPrice;
            $booking->estimated_cost = null; // Converted from estimated to final price!
            $priceNote = ' Final Price Confirmed: ৳ '.number_format($finalPrice, 2).' (converted from estimate).';
        } elseif ($request->filled('agreed_price')) {
            $booking->final_cost = (float) $request->input('agreed_price');
            $priceNote = ' Agreed Price: ৳ '.number_format($booking->final_cost, 2).'.';
        } elseif ($booking->final_cost > 0) {
            $priceNote = ' Agreed Price: ৳ '.number_format($booking->final_cost, 2).'.';
        }

        $oldStatus = $booking->status;
        $booking->partner_job_status = 'accepted';
        $booking->status = 'assigned';
        $booking->save();

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => $oldStatus,
            'to_status' => 'assigned',
            'comment' => "Partner '{$partner->company_name}' accepted the job assignment.{$priceNote}",
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
            $comment = "Technician/Crew from '{$partner->company_name}' is currently on the way to the service address.";

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

            // Mandatory price check if price was set to "later"
            if ($isInitialPrice) {
                if (! $request->filled('agreed_price') || (float) $request->input('agreed_price') <= 0) {
                    return back()->withErrors([
                        'agreed_price' => 'Admin selected "Set price later". You must set and agree on the service price with the customer before starting work.',
                    ]);
                }
            }

            // Handle price set or updated
            if ($request->filled('agreed_price') && (float) $request->input('agreed_price') > 0) {
                $newPrice = (float) $request->input('agreed_price');
                $oldPrice = (float) ($booking->final_cost ?? $booking->estimated_cost ?? 0);

                if ($isInitialPrice || abs($oldPrice - $newPrice) > 0.01) {
                    $updateData['estimated_cost'] = $newPrice;

                    $priceTitle = $isInitialPrice
                        ? "Price Set by Crew for Job #{$booking->booking_number}"
                        : "Price Updated by Crew for Job #{$booking->booking_number}";

                    $priceMsg = $isInitialPrice
                        ? "Partner '{$partner->company_name}' agreed on and set service price to ৳".number_format($newPrice, 2).' before starting work.'
                        : "Partner '{$partner->company_name}' updated service price from ৳".number_format($oldPrice, 2).' to ৳'.number_format($newPrice, 2).($request->filled('price_notes') ? ' • Reason: '.$request->input('price_notes') : '');

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
                            'New Price' => '৳ '.number_format($newPrice, 2),
                            'Previous Price' => $oldPrice > 0 ? '৳ '.number_format($oldPrice, 2) : 'Not Set',
                        ]
                    );

                    BookingStatusLog::create([
                        'booking_id' => $booking->id,
                        'from_status' => $booking->status,
                        'to_status' => $booking->status,
                        'comment' => $priceMsg,
                        'is_customer_visible' => true,
                    ]);
                }
            }

            $updateData['status'] = 'in_progress';
            $comment = "Service work started on site by '{$partner->company_name}'.";

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
            $comment = "Service work successfully completed by '{$partner->company_name}'.";

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
                'title' => "Job #{$booking->booking_number} Completed",
                'message' => "Partner '{$partner->company_name}' completed service on site.",
                'booking_id' => $booking->id,
                'action_url' => route('admin.bookings.show', $booking),
                'is_read' => false,
            ]);

            // Dispatch operational alert to admin team
            NotificationDispatcher::notifyAdminSection(
                'orders',
                "Job #{$booking->booking_number} Completed by Partner",
                "Partner '{$partner->company_name}' has completed on-site service for Job #{$booking->booking_number}.",
                route('admin.bookings.show', $booking),
                [
                    'Order' => "#{$booking->booking_number}",
                    'Partner' => $partner->company_name,
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
            'comment' => $priceMsg,
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
}
