<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AdminPaymentMethod;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\PartnerNotification;
use App\Models\PartnerPaymentMethod;
use App\Models\PartnerPayout;
use App\Services\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerPayoutController extends Controller
{
    /**
     * Display earnings, commission summary, payment methods, and payout history.
     */
    public function index(): View
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();
        $partner->ensureDefaultPaymentMethod();

        $completedJobs = Booking::where('partner_id', $partner->id)
            ->where(function ($q) {
                $q->where('partner_job_status', 'completed')
                    ->orWhere('status', 'completed');
            })
            ->latest('completed_at')
            ->paginate(10, ['*'], 'jobs_page');

        $paymentMethods = $partner->paymentMethods;
        $adminPaymentMethods = AdminPaymentMethod::active()->get();
        $payouts = $partner->payouts()->with(['paymentMethod', 'adminPaymentMethod'])->latest()->paginate(10, ['*'], 'payouts_page');

        $totalEarned = $partner->total_earned;
        $totalPaid = $partner->total_paid;
        $cashCollectedByCrew = $partner->cash_collected_by_crew;
        $paidByCrew = $partner->paid_by_crew;
        $netBalance = $partner->net_balance;
        $adminOwesCrew = $partner->admin_owes_crew;
        $crewOwesAdmin = $partner->crew_owes_admin;
        $pendingDue = $partner->payable_balance;
        $crewDueAmount = $partner->crew_due_amount;

        $adminPaymentRequests = $partner->payouts()
            ->where('direction', 'partner_to_admin')
            ->where('status', 'requested')
            ->with('adminPaymentMethod')
            ->get();

        $pendingSettlementSubmissions = $partner->payouts()
            ->where('direction', 'partner_to_admin')
            ->where('status', 'pending')
            ->with('adminPaymentMethod')
            ->get();

        $pendingPayoutRequests = $partner->payouts()
            ->where('direction', 'admin_to_partner')
            ->where('status', 'pending')
            ->get();
        $pendingRequestedAmount = (float) $pendingPayoutRequests->sum('amount');

        return view('partner.payouts.index', compact(
            'partner',
            'completedJobs',
            'paymentMethods',
            'adminPaymentMethods',
            'payouts',
            'totalEarned',
            'totalPaid',
            'cashCollectedByCrew',
            'paidByCrew',
            'netBalance',
            'adminOwesCrew',
            'crewOwesAdmin',
            'pendingDue',
            'crewDueAmount',
            'adminPaymentRequests',
            'pendingSettlementSubmissions',
            'pendingPayoutRequests',
            'pendingRequestedAmount'
        ));
    }

    /**
     * Submit a payout withdrawal request to Admin.
     */
    public function requestPayout(Request $request): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();
        $availableBalance = $partner->payable_balance;

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.$availableBalance],
            'partner_payment_method_id' => ['required', 'exists:partner_payment_methods,id'],
            'partner_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'amount.max' => 'Requested amount cannot exceed your current payable balance of ৳'.number_format($availableBalance, 2),
            'amount.min' => 'Requested amount must be at least ৳ 1.00',
            'partner_payment_method_id.required' => 'Please select a receiving payment method.',
        ]);

        $paymentMethod = $partner->paymentMethods()->where('id', $validated['partner_payment_method_id'])->firstOrFail();

        $amount = (float) $validated['amount'];

        $payout = PartnerPayout::create([
            'payout_number' => PartnerPayout::generatePayoutNumber(),
            'partner_id' => $partner->id,
            'partner_payment_method_id' => $paymentMethod->id,
            'amount' => $amount,
            'status' => 'pending',
            'payment_method_details' => $paymentMethod->formatted_method,
            'partner_notes' => $validated['partner_notes'] ?? null,
            'requested_at' => now(),
        ]);

        // Notify Admin CMS
        AdminNotification::create([
            'type' => 'payout_request',
            'title' => 'Payout Request: ৳'.number_format($amount, 2)." from {$partner->company_name}",
            'message' => "Partner '{$partner->company_name}' requested payout of ৳".number_format($amount, 2)." via {$paymentMethod->formatted_method} (Req #{$payout->payout_number}).",
            'action_url' => route('admin.partners.show', $partner).'#tab-payouts',
            'is_read' => false,
        ]);

        // Notify Partner In-App
        PartnerNotification::create([
            'partner_id' => $partner->id,
            'type' => 'payout_requested',
            'title' => 'Payout Request Submitted: ৳'.number_format($amount, 2),
            'message' => "Your payout request #{$payout->payout_number} for ৳".number_format($amount, 2).' has been submitted to FIX operations for review and disbursement.',
            'action_url' => route('partner.payouts.index'),
            'is_read' => false,
        ]);

        return redirect()->route('partner.payouts.index')
            ->with('success', 'Payout request for ৳'.number_format($amount, 2).' submitted successfully! Admin will process your disbursement shortly.');
    }

    /**
     * Submit a dues settlement payment to Admin.
     */
    public function payAdmin(Request $request): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();

        if (! $request->filled('payment_method_type') && $request->filled('admin_payment_method_id')) {
            $matchedMethod = AdminPaymentMethod::find($request->input('admin_payment_method_id'));
            if ($matchedMethod) {
                $request->merge(['payment_method_type' => $matchedMethod->method_type]);
            }
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'admin_payment_method_id' => ['nullable', 'exists:admin_payment_methods,id'],
            'payment_method_type' => ['required', 'string', 'in:cash,offline,bkash,nagad,rocket,bank,other'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
            'partner_reference' => ['nullable', 'string', 'max:100'],
            'partner_notes' => ['nullable', 'string', 'max:500'],
            'proof_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'admin_request_id' => ['nullable', 'exists:partner_payouts,id'],
        ]);

        $amount = (float) $validated['amount'];
        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('settlement_proofs', 'public');
        }

        $adminMethod = ! empty($validated['admin_payment_method_id'])
            ? AdminPaymentMethod::find($validated['admin_payment_method_id'])
            : null;

        $methodDetails = $adminMethod
            ? $adminMethod->formatted_method
            : strtoupper($validated['payment_method_type']).' (Cash / Offline Handover)';

        $payout = PartnerPayout::create([
            'payout_number' => PartnerPayout::generatePayoutNumber('partner_to_admin'),
            'direction' => 'partner_to_admin',
            'initiated_by' => 'partner',
            'partner_id' => $partner->id,
            'admin_payment_method_id' => $adminMethod?->id,
            'payment_method_type' => $validated['payment_method_type'],
            'amount' => $amount,
            'status' => 'pending',
            'payment_method_details' => $methodDetails,
            'transaction_reference' => $validated['transaction_reference'] ?? $validated['partner_reference'] ?? null,
            'partner_notes' => $validated['partner_notes'] ?? null,
            'proof_file' => $proofPath,
            'requested_at' => now(),
        ]);

        // If this was in response to an open admin payment request, update it
        if (! empty($validated['admin_request_id'])) {
            $adminReq = PartnerPayout::where('id', $validated['admin_request_id'])
                ->where('partner_id', $partner->id)
                ->where('status', 'requested')
                ->first();
            if ($adminReq) {
                $adminReq->update([
                    'status' => 'pending',
                    'transaction_reference' => $validated['transaction_reference'] ?? $adminReq->transaction_reference,
                    'partner_notes' => 'Settlement payment submitted via payout #'.$payout->payout_number,
                ]);
            }
        }

        // Notify Admin CMS
        AdminNotification::create([
            'type' => 'partner_settlement_submitted',
            'title' => 'Crew Payment Submitted: ৳'.number_format($amount, 2)." from {$partner->company_name}",
            'message' => "Partner '{$partner->company_name}' submitted dues settlement of ৳".number_format($amount, 2)." via {$methodDetails} (Ref: {$payout->transaction_reference}). Review and confirm to clear dues.",
            'action_url' => route('admin.partners.show', $partner).'#tab-payouts',
            'is_read' => false,
        ]);

        NotificationDispatcher::notifyAdminSection(
            'payments',
            "Crew Payment Submitted from {$partner->company_name}",
            "Partner '{$partner->company_name}' submitted dues settlement of ৳".number_format($amount, 2)." via {$methodDetails}. Please verify and approve.",
            route('admin.partners.show', $partner).'#tab-payouts'
        );

        // Notify Partner In-App
        PartnerNotification::create([
            'partner_id' => $partner->id,
            'type' => 'settlement_submitted',
            'title' => 'Settlement Payment Submitted: ৳'.number_format($amount, 2),
            'message' => 'Your payment of ৳'.number_format($amount, 2)." to FIX has been submitted (Receipt #{$payout->payout_number}). Admin will verify transaction and clear dues.",
            'action_url' => route('partner.payouts.index'),
            'is_read' => false,
        ]);

        return redirect()->route('partner.payouts.index')
            ->with('success', 'Payment of ৳'.number_format($amount, 2).' to FIX submitted successfully! Admin will verify and clear your dues.');
    }

    /**
     * Store new receiving payment method for partner.
     */
    public function storePaymentMethod(Request $request): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();

        $validated = $request->validate([
            'method_type' => ['required', 'string', 'in:bkash,nagad,rocket,upay,bank'],
            'account_number' => ['required', 'string', 'max:60'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'number_type' => ['required_if:method_type,bkash,nagad,rocket,upay', 'nullable', 'string', 'in:personal,agent,merchant'],
            'bank_name' => ['required_if:method_type,bank', 'nullable', 'string', 'max:100'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'routing_number' => ['nullable', 'string', 'max:50'],
            'is_default' => ['nullable', 'boolean'],
        ], [
            'number_type.required_if' => 'Please specify whether this number is Personal, Agent, or Merchant.',
            'bank_name.required_if' => 'Bank name is required for bank transfer method.',
        ]);

        $isDefault = $request->boolean('is_default') || ! $partner->paymentMethods()->exists();

        if ($isDefault) {
            $partner->paymentMethods()->update(['is_default' => false]);
        }

        $partner->paymentMethods()->create([
            'method_type' => $validated['method_type'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'] ?? null,
            'number_type' => $validated['method_type'] !== 'bank' ? ($validated['number_type'] ?? 'personal') : null,
            'bank_name' => $validated['method_type'] === 'bank' ? ($validated['bank_name'] ?? null) : null,
            'branch_name' => $validated['method_type'] === 'bank' ? ($validated['branch_name'] ?? null) : null,
            'routing_number' => $validated['method_type'] === 'bank' ? ($validated['routing_number'] ?? null) : null,
            'is_default' => $isDefault,
        ]);

        return redirect()->back()
            ->with('tab', 'payout')
            ->with('success', 'New payment method added successfully!');
    }

    /**
     * Update an existing payment method.
     */
    public function updatePaymentMethod(Request $request, PartnerPaymentMethod $paymentMethod): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();

        if ((int) $paymentMethod->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $validated = $request->validate([
            'method_type' => ['required', 'string', 'in:bkash,nagad,rocket,upay,bank'],
            'account_number' => ['required', 'string', 'max:60'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'number_type' => ['required_if:method_type,bkash,nagad,rocket,upay', 'nullable', 'string', 'in:personal,agent,merchant'],
            'bank_name' => ['required_if:method_type,bank', 'nullable', 'string', 'max:100'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'routing_number' => ['nullable', 'string', 'max:50'],
            'is_default' => ['nullable', 'boolean'],
        ], [
            'number_type.required_if' => 'Please specify whether this number is Personal, Agent, or Merchant.',
            'bank_name.required_if' => 'Bank name is required for bank transfer method.',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault && ! $paymentMethod->is_default) {
            $partner->paymentMethods()->update(['is_default' => false]);
        }

        $paymentMethod->update([
            'method_type' => $validated['method_type'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'] ?? null,
            'number_type' => $validated['method_type'] !== 'bank' ? ($validated['number_type'] ?? 'personal') : null,
            'bank_name' => $validated['method_type'] === 'bank' ? ($validated['bank_name'] ?? null) : null,
            'branch_name' => $validated['method_type'] === 'bank' ? ($validated['branch_name'] ?? null) : null,
            'routing_number' => $validated['method_type'] === 'bank' ? ($validated['routing_number'] ?? null) : null,
            'is_default' => $isDefault ? true : $paymentMethod->is_default,
        ]);

        return redirect()->back()
            ->with('tab', 'payout')
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Remove a payment method.
     */
    public function destroyPaymentMethod(PartnerPaymentMethod $paymentMethod): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();

        if ((int) $paymentMethod->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $wasDefault = $paymentMethod->is_default;
        $paymentMethod->delete();

        if ($wasDefault) {
            $partner->paymentMethods()->latest()->first()?->update(['is_default' => true]);
        }

        return redirect()->back()
            ->with('tab', 'payout')
            ->with('success', 'Payment method removed.');
    }

    /**
     * Set a payment method as default.
     */
    public function setDefaultPaymentMethod(PartnerPaymentMethod $paymentMethod): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();

        if ((int) $paymentMethod->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $partner->paymentMethods()->update(['is_default' => false]);
        $paymentMethod->update(['is_default' => true]);

        return redirect()->back()
            ->with('tab', 'payout')
            ->with('success', 'Default payout method updated.');
    }
}
