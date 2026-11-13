<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerNotification;
use App\Models\PartnerPayout;
use App\Services\ActivityLogService;
use App\Services\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminPartnerController extends Controller
{
    /**
     * Display listing of enlisted field crews & partner vendors.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = Partner::with('user')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('trade_domain', 'like', "%{$search}%");
            });
        }

        if ($request->query('filter') === 'payable' || $status === 'payable') {
            $payablePartnerIds = Partner::all()->filter(fn ($p) => $p->payable_balance > 0)->pluck('id');
            $query->whereIn('id', $payablePartnerIds);
        }

        $partners = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Partner::count(),
            'pending' => Partner::where('status', 'pending')->count(),
            'approved' => Partner::where('status', 'approved')->count(),
            'rejected' => Partner::where('status', 'rejected')->count(),
            'payable' => Partner::all()->filter(fn ($p) => $p->payable_balance > 0)->count(),
        ];

        return view('admin.partners.index', compact('partners', 'status', 'search', 'stats'));
    }

    /**
     * Show form to enlist a new partner or field crew directly from admin.
     */
    public function create(): View
    {
        return view('admin.partners.create');
    }

    /**
     * Store a newly created partner / field crew in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'partner_type' => ['required', 'string', 'in:individual,company'],
            'trade_domain' => ['required'],
            'trade_domain.*' => ['string'],
            'technicians_count' => ['required', 'string', 'max:50'],
            'coverage_areas' => ['required'],
            'coverage_areas.*' => ['string'],
            'other_coverage_areas' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:approved,pending,rejected,suspended'],
            'payout_terms' => ['required', 'string', 'in:weekly,bi_weekly,monthly,per_job'],
            'earning_model' => ['nullable', 'string', 'in:commission,hourly,daily,monthly,per_job'],
            'payout_rate' => ['nullable', 'numeric', 'min:0'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_method' => ['nullable', 'string', 'in:bank,bkash,nagad,rocket'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'mfs_number' => ['nullable', 'string', 'max:30'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'trade_domain.required' => 'Please select at least one primary technical domain.',
            'coverage_areas.required' => 'Please select at least one operating coverage area.',
        ]);

        $tradeDomain = is_array($request->input('trade_domain'))
            ? implode(', ', array_filter($request->input('trade_domain')))
            : (string) $request->input('trade_domain');

        if (is_array($request->input('coverage_areas'))) {
            $selectedAreas = array_filter($request->input('coverage_areas'));
            if ($request->filled('other_coverage_areas')) {
                $selectedAreas[] = trim((string) $request->input('other_coverage_areas'));
            }
            $coverageAreas = implode(', ', $selectedAreas);
        } else {
            $coverageAreas = (string) $request->input('coverage_areas');
            if ($request->filled('other_coverage_areas')) {
                $coverageAreas .= ', '.trim((string) $request->input('other_coverage_areas'));
            }
        }

        $admin = Auth::guard('admin')->user();
        $isApproved = $request->input('status') === 'approved';

        $rawPassword = $request->input('password');
        $hashedPassword = ! empty($rawPassword)
            ? Hash::make($rawPassword)
            : ($isApproved ? Hash::make('123456') : null);

        $partner = Partner::create([
            'company_name' => (string) $request->input('company_name'),
            'contact_person' => (string) $request->input('contact_person'),
            'phone' => (string) $request->input('phone'),
            'email' => $request->input('email') ?: null,
            'password' => $hashedPassword,
            'partner_type' => (string) $request->input('partner_type'),
            'trade_domain' => $tradeDomain,
            'technicians_count' => (string) $request->input('technicians_count'),
            'coverage_areas' => $coverageAreas,
            'status' => (string) $request->input('status'),
            'payout_terms' => (string) $request->input('payout_terms'),
            'earning_model' => (string) ($request->input('earning_model') ?: 'commission'),
            'payout_rate' => $request->filled('payout_rate') ? (float) $request->input('payout_rate') : ($request->filled('commission_rate') ? (float) $request->input('commission_rate') : 15.00),
            'commission_rate' => $request->filled('commission_rate') ? $request->input('commission_rate') : ($request->filled('payout_rate') && $request->input('earning_model') === 'commission' ? $request->input('payout_rate') : 15.00),
            'payment_method' => $request->input('payment_method') ?: 'bank',
            'bank_name' => $request->input('bank_name') ?: null,
            'account_holder' => $request->input('account_holder') ?: null,
            'account_number' => $request->input('account_number') ?: null,
            'branch_name' => $request->input('branch_name') ?: null,
            'mfs_number' => $request->input('mfs_number') ?: null,
            'admin_notes' => $request->input('admin_notes') ?: null,
            'approved_at' => $isApproved ? now() : null,
            'approved_by_admin_id' => $isApproved ? $admin?->id : null,
        ]);

        ActivityLogService::logPartner('partner.create', "Enlisted new partner/field crew '{$partner->company_name}' ({$partner->contact_person}).", $partner);

        return redirect()->route('admin.partners.show', $partner)
            ->with('success', "Partner / Field Crew '{$partner->company_name}' has been created successfully!");
    }

    /**
     * Display individual partner review and payment configuration.
     */
    public function show(Partner $partner): View
    {
        $partner->load([
            'bookings' => function ($q) {
                $q->with(['service', 'amcPlan', 'assignedCrew'])->latest();
            },
            'crews' => function ($q) {
                $q->withCount('bookings')->latest();
            },
            'paymentMethods',
            'payouts' => function ($q) {
                $q->with(['paymentMethod', 'processedByAdmin'])->latest();
            },
        ]);

        $partner->ensureDefaultPaymentMethod();

        $completedBookings = $partner->bookings->where('status', 'completed');

        $totalEarned = $partner->total_earned;
        $totalPaid = $partner->total_paid;
        $pendingDue = $partner->payable_balance;
        $payoutHistory = $partner->payouts;
        $pendingPayoutRequests = $partner->payouts->where('status', 'pending');
        $paymentMethods = $partner->paymentMethods;

        $unsettledCount = $completedBookings->where('partner_payout_status', '!=', 'paid')->count();
        $activeJobsCount = $partner->bookings->whereIn('status', ['pending_review', 'accepted', 'assigned', 'on_the_way', 'in_progress'])->count();
        $totalJobsCount = $partner->bookings->count();
        $completedJobsCount = $completedBookings->count();

        $avgRating = $partner->bookings->whereNotNull('review_rating')->avg('review_rating');
        $reviewsCount = $partner->bookings->whereNotNull('review_rating')->count();

        $crewsCount = $partner->crews->count();
        $activeCrewsCount = $partner->crews->where('is_active', true)->count();

        return view('admin.partners.show', compact(
            'partner',
            'totalEarned',
            'totalPaid',
            'pendingDue',
            'unsettledCount',
            'activeJobsCount',
            'totalJobsCount',
            'completedJobsCount',
            'avgRating',
            'reviewsCount',
            'crewsCount',
            'activeCrewsCount',
            'payoutHistory',
            'pendingPayoutRequests',
            'paymentMethods'
        ));
    }

    /**
     * Show form to edit existing partner or field crew.
     */
    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update existing partner or field crew.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'partner_type' => ['required', 'string', 'in:individual,company'],
            'trade_domain' => ['required'],
            'trade_domain.*' => ['string'],
            'technicians_count' => ['required', 'string', 'max:50'],
            'coverage_areas' => ['required'],
            'coverage_areas.*' => ['string'],
            'other_coverage_areas' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:approved,pending,rejected,suspended'],
            'payout_terms' => ['required', 'string', 'in:weekly,bi_weekly,monthly,per_job'],
            'earning_model' => ['nullable', 'string', 'in:commission,hourly,daily,monthly,per_job'],
            'payout_rate' => ['nullable', 'numeric', 'min:0'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_method' => ['nullable', 'string', 'in:bank,bkash,nagad,rocket'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'mfs_number' => ['nullable', 'string', 'max:30'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'trade_domain.required' => 'Please select at least one primary technical domain.',
            'coverage_areas.required' => 'Please select at least one operating coverage area.',
        ]);

        $tradeDomain = is_array($request->input('trade_domain'))
            ? implode(', ', array_filter($request->input('trade_domain')))
            : (string) $request->input('trade_domain');

        if (is_array($request->input('coverage_areas'))) {
            $selectedAreas = array_filter($request->input('coverage_areas'));
            if ($request->filled('other_coverage_areas')) {
                $selectedAreas[] = trim((string) $request->input('other_coverage_areas'));
            }
            $coverageAreas = implode(', ', $selectedAreas);
        } else {
            $coverageAreas = (string) $request->input('coverage_areas');
            if ($request->filled('other_coverage_areas')) {
                $coverageAreas .= ', '.trim((string) $request->input('other_coverage_areas'));
            }
        }

        $admin = Auth::guard('admin')->user();
        $wasApproved = $partner->status === 'approved';
        $isApproved = $request->input('status') === 'approved';

        $updateData = [
            'company_name' => (string) $request->input('company_name'),
            'contact_person' => (string) $request->input('contact_person'),
            'phone' => (string) $request->input('phone'),
            'email' => $request->input('email') ?: null,
            'partner_type' => (string) $request->input('partner_type'),
            'trade_domain' => $tradeDomain,
            'technicians_count' => (string) $request->input('technicians_count'),
            'coverage_areas' => $coverageAreas,
            'status' => (string) $request->input('status'),
            'payout_terms' => (string) $request->input('payout_terms'),
            'earning_model' => (string) ($request->input('earning_model') ?: ($partner->earning_model ?? 'commission')),
            'payout_rate' => $request->filled('payout_rate') ? (float) $request->input('payout_rate') : ($request->filled('commission_rate') && $request->input('earning_model') === 'commission' ? (float) $request->input('commission_rate') : $partner->payout_rate),
            'commission_rate' => $request->filled('commission_rate') ? $request->input('commission_rate') : ($request->filled('payout_rate') && $request->input('earning_model') === 'commission' ? $request->input('payout_rate') : $partner->commission_rate),
            'payment_method' => $request->input('payment_method') ?: $partner->payment_method,
            'bank_name' => $request->input('bank_name') ?: null,
            'account_holder' => $request->input('account_holder') ?: null,
            'account_number' => $request->input('account_number') ?: null,
            'branch_name' => $request->input('branch_name') ?: null,
            'mfs_number' => $request->input('mfs_number') ?: null,
            'admin_notes' => $request->input('admin_notes') ?: null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }

        if ($isApproved && ! $wasApproved) {
            $updateData['approved_at'] = now();
            $updateData['approved_by_admin_id'] = $admin?->id;
        }

        $partner->update($updateData);

        ActivityLogService::logPartner('partner.update', "Updated partner/field crew '{$partner->company_name}' details.", $partner);

        return redirect()->route('admin.partners.show', $partner)
            ->with('success', "Partner / Field Crew '{$partner->company_name}' updated successfully!");
    }

    /**
     * Approve partner application with administrative payment and contracting setup.
     */
    public function approve(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'partner_type' => ['required', 'string', 'in:individual,company'],
            'payment_method' => ['required', 'string', 'in:bank,bkash,nagad,rocket'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'mfs_number' => ['nullable', 'string', 'max:30'],
            'earning_model' => ['nullable', 'string', 'in:commission,hourly,daily,monthly,per_job'],
            'payout_rate' => ['nullable', 'numeric', 'min:0'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payout_terms' => ['required', 'string', 'in:weekly,bi_weekly,monthly,per_job'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $admin = Auth::guard('admin')->user();

        $updateData = [
            'status' => 'approved',
            'partner_type' => $validated['partner_type'],
            'payment_method' => $validated['payment_method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'account_holder' => $validated['account_holder'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'mfs_number' => $validated['mfs_number'] ?? null,
            'earning_model' => $validated['earning_model'] ?? ($partner->earning_model ?? 'commission'),
            'payout_rate' => array_key_exists('payout_rate', $validated) && $validated['payout_rate'] !== null ? (float) $validated['payout_rate'] : ($validated['commission_rate'] ?? $partner->payout_rate ?? 15.00),
            'commission_rate' => $validated['commission_rate'] ?? ($validated['payout_rate'] ?? 15.00),
            'payout_terms' => $validated['payout_terms'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_at' => now(),
            'approved_by_admin_id' => $admin?->id,
        ];

        // Ensure partner has a password for login
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        } elseif (empty($partner->password)) {
            $updateData['password'] = Hash::make('123456');
        }

        $partner->update($updateData);

        ActivityLogService::logPartner('partner.approve', "Approved partner/crew '{$partner->company_name}' ({$partner->contact_person}). Added to dispatch network.", $partner);

        // Notify admins subscribed to partner section
        NotificationDispatcher::notifyAdminSection(
            'partners',
            "Partner '{$partner->company_name}' Approved",
            "Partner '{$partner->company_name}' ({$partner->contact_person}, Phone: {$partner->phone}) has been approved and added to active field crew dispatch.",
            route('admin.partners.show', $partner),
            [
                'Partner' => $partner->company_name,
                'Classification' => $partner->isVendor() ? 'Vendor Agency' : 'Individual Solo Crew',
                'Phone' => $partner->phone,
                'Approved By' => $admin?->name ?? 'Admin',
            ]
        );

        // Notify Partner in-app
        NotificationDispatcher::notifyPartnerAccountApproved($partner);

        return redirect()->route('admin.partners.show', $partner)
            ->with('success', "Partner '{$partner->company_name}' has been approved and can now log in at /partner/login using phone number '{$partner->phone}'!");
    }

    /**
     * Update partner details or status (e.g. reject, suspend).
     */
    public function updateStatus(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected,suspended'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $partner->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $partner->admin_notes,
        ]);

        ActivityLogService::logPartner('partner.status', "Changed status of '{$partner->company_name}' to {$validated['status']}.", $partner);

        return back()->with('success', 'Partner status updated to '.ucfirst($validated['status']));
    }

    /**
     * Remove partner from system.
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        $name = $partner->company_name;
        $partner->delete();

        ActivityLogService::logPartner('partner.delete', "Deleted partner record for '{$name}'.");

        return redirect()->route('admin.partners.index')->with('success', "Partner '{$name}' deleted.");
    }

    /**
     * Disburse custom payout amount from partner's unpaid balance.
     */
    public function disbursePayout(Request $request, Partner $partner): RedirectResponse
    {
        $balance = $partner->payable_balance;

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.$balance],
            'partner_payment_method_id' => ['nullable', 'exists:partner_payment_methods,id'],
            'custom_payment_method' => ['nullable', 'string', 'max:150'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'amount.max' => 'Disbursement amount cannot exceed the crew\'s current unpaid balance of ৳'.number_format($balance, 2),
            'amount.min' => 'Disbursement amount must be at least ৳ 1.00',
        ]);

        $amount = (float) $validated['amount'];
        $method = ! empty($validated['partner_payment_method_id'])
            ? $partner->paymentMethods()->find($validated['partner_payment_method_id'])
            : null;

        $methodDetails = $method
            ? $method->formatted_method
            : ($validated['custom_payment_method'] ?? 'Direct Cash / Bank Settlement');

        $payout = PartnerPayout::create([
            'payout_number' => PartnerPayout::generatePayoutNumber(),
            'partner_id' => $partner->id,
            'partner_payment_method_id' => $method?->id,
            'amount' => $amount,
            'status' => 'paid',
            'payment_method_details' => $methodDetails,
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'requested_at' => now(),
            'paid_at' => now(),
            'processed_by_admin_id' => Auth::guard('admin')->id(),
        ]);

        // If the crew has an open pending payout request, settle it if covered
        $pendingPayout = $partner->payouts()->where('status', 'pending')->first();
        if ($pendingPayout && (float) $pendingPayout->amount <= $amount) {
            $pendingPayout->update([
                'status' => 'paid',
                'paid_at' => now(),
                'processed_by_admin_id' => Auth::guard('admin')->id(),
                'transaction_reference' => $validated['transaction_reference'] ?? $pendingPayout->transaction_reference,
                'admin_notes' => 'Settled via lump-sum balance disbursement payout #'.$payout->payout_number,
            ]);
        }

        // Notify Partner in-app
        PartnerNotification::create([
            'partner_id' => $partner->id,
            'type' => 'payout_disbursed',
            'title' => 'Payment Disbursed: ৳'.number_format($amount, 2),
            'message' => 'Admin has disbursed ৳'.number_format($amount, 2)." to your balance via {$methodDetails}."
                .(! empty($payout->transaction_reference) ? " (TrxID: {$payout->transaction_reference})" : '')
                .' Remaining unpaid balance: ৳'.number_format($partner->fresh()->payable_balance, 2).'.',
            'action_url' => route('partner.payouts.index'),
            'is_read' => false,
        ]);

        // Dispatch alert to admin payments section
        NotificationDispatcher::notifyAdminSection(
            'payments',
            "Payout Disbursed to {$partner->company_name}",
            'Disbursed ৳'.number_format($amount, 2)." to {$partner->company_name}. Remaining balance: ৳".number_format($partner->fresh()->payable_balance, 2).'.',
            route('admin.partners.show', $partner).'#tab-payouts'
        );

        ActivityLogService::logPartner('partner.payout', 'Disbursed ৳'.number_format($amount, 2)." to {$partner->company_name} (Payout #{$payout->payout_number}).", $partner);

        return redirect()->route('admin.partners.show', $partner)
            ->with('success', 'Disbursed ৳'.number_format($amount, 2)." to {$partner->company_name}. Remaining unpaid balance is ৳".number_format($partner->fresh()->payable_balance, 2).'.');
    }

    /**
     * Approve and mark a partner withdrawal request as paid.
     */
    public function approvePayoutRequest(Request $request, PartnerPayout $payout): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_reference' => ['nullable', 'string', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $partner = $payout->partner;

        $payout->update([
            'status' => 'paid',
            'paid_at' => now(),
            'processed_by_admin_id' => Auth::guard('admin')->id(),
            'transaction_reference' => $validated['transaction_reference'] ?? $payout->transaction_reference,
            'admin_notes' => $validated['admin_notes'] ?? $payout->admin_notes,
        ]);

        $remainingBalance = $partner->fresh()->payable_balance;

        PartnerNotification::create([
            'partner_id' => $partner->id,
            'type' => 'payout_approved',
            'title' => 'Payout Approved: ৳'.number_format((float) $payout->amount, 2),
            'message' => "Your payout request #{$payout->payout_number} for ৳".number_format((float) $payout->amount, 2).' has been approved and paid.'
                .(! empty($payout->transaction_reference) ? " (Trx: {$payout->transaction_reference})" : '')
                .' Remaining unpaid balance: ৳'.number_format($remainingBalance, 2).'.',
            'action_url' => route('partner.payouts.index'),
            'is_read' => false,
        ]);

        ActivityLogService::logPartner('partner.payout_approved', "Approved withdrawal request #{$payout->payout_number} for ৳".number_format((float) $payout->amount, 2)." to {$partner->company_name}.", $partner);

        return back()->with('success', "Payout request #{$payout->payout_number} for ৳".number_format((float) $payout->amount, 2).' approved and disbursed.');
    }

    /**
     * Reject a partner withdrawal request.
     */
    public function rejectPayoutRequest(Request $request, PartnerPayout $payout): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $partner = $payout->partner;

        $payout->update([
            'status' => 'rejected',
            'admin_notes' => $validated['rejection_reason'],
            'processed_by_admin_id' => Auth::guard('admin')->id(),
        ]);

        PartnerNotification::create([
            'partner_id' => $partner->id,
            'type' => 'payout_rejected',
            'title' => 'Payout Request Rejected',
            'message' => "Your payout request #{$payout->payout_number} for ৳".number_format((float) $payout->amount, 2).' was rejected by admin. Reason: '.$validated['rejection_reason'],
            'action_url' => route('partner.payouts.index'),
            'is_read' => false,
        ]);

        ActivityLogService::logPartner('partner.payout_rejected', "Rejected payout request #{$payout->payout_number} of {$partner->company_name}. Reason: {$validated['rejection_reason']}", $partner);

        return back()->with('info', "Payout request #{$payout->payout_number} has been rejected.");
    }
}
