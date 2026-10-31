<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $partners = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Partner::count(),
            'pending' => Partner::where('status', 'pending')->count(),
            'approved' => Partner::where('status', 'approved')->count(),
            'rejected' => Partner::where('status', 'rejected')->count(),
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
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_method' => ['nullable', 'string', 'in:bank,bkash,nagad,rocket'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'mfs_number' => ['nullable', 'string', 'max:30'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
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

        $partner = Partner::create([
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
            'commission_rate' => $request->filled('commission_rate') ? $request->input('commission_rate') : 15.00,
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
        return view('admin.partners.show', compact('partner'));
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
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_method' => ['nullable', 'string', 'in:bank,bkash,nagad,rocket'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'mfs_number' => ['nullable', 'string', 'max:30'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
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
            'commission_rate' => $request->filled('commission_rate') ? $request->input('commission_rate') : $partner->commission_rate,
            'payment_method' => $request->input('payment_method') ?: $partner->payment_method,
            'bank_name' => $request->input('bank_name') ?: null,
            'account_holder' => $request->input('account_holder') ?: null,
            'account_number' => $request->input('account_number') ?: null,
            'branch_name' => $request->input('branch_name') ?: null,
            'mfs_number' => $request->input('mfs_number') ?: null,
            'admin_notes' => $request->input('admin_notes') ?: null,
        ];

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
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payout_terms' => ['required', 'string', 'in:weekly,bi_weekly,monthly,per_job'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();

        $partner->update([
            'status' => 'approved',
            'partner_type' => $validated['partner_type'],
            'payment_method' => $validated['payment_method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'account_holder' => $validated['account_holder'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'mfs_number' => $validated['mfs_number'] ?? null,
            'commission_rate' => $validated['commission_rate'] ?? 15.00,
            'payout_terms' => $validated['payout_terms'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_at' => now(),
            'approved_by_admin_id' => $admin?->id,
        ]);

        ActivityLogService::logPartner('partner.approve', "Approved partner/crew '{$partner->company_name}' ({$partner->contact_person}). Added to dispatch network.", $partner);

        return redirect()->route('admin.partners.show', $partner)
            ->with('success', "Partner '{$partner->company_name}' has been approved and added to active field crew dispatch!");
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
}
