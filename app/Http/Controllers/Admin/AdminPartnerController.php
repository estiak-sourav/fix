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

        $query = Partner::query()->latest();

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
     * Display individual partner review and payment configuration.
     */
    public function show(Partner $partner): View
    {
        return view('admin.partners.show', compact('partner'));
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
