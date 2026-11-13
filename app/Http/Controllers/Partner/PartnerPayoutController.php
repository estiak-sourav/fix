<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\PartnerNotification;
use App\Models\PartnerPaymentMethod;
use App\Models\PartnerPayout;
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
        $payouts = $partner->payouts()->with('paymentMethod')->paginate(10, ['*'], 'payouts_page');

        $totalEarned = $partner->total_earned;
        $totalPaid = $partner->total_paid;
        $pendingDue = $partner->payable_balance;

        $pendingPayoutRequests = $partner->payouts()->where('status', 'pending')->get();
        $pendingRequestedAmount = (float) $pendingPayoutRequests->sum('amount');

        return view('partner.payouts.index', compact(
            'partner',
            'completedJobs',
            'paymentMethods',
            'payouts',
            'totalEarned',
            'totalPaid',
            'pendingDue',
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
     * Store new receiving payment method for partner.
     */
    public function storePaymentMethod(Request $request): RedirectResponse
    {
        /** @var Partner $partner */
        $partner = Auth::guard('partner')->user();

        $validated = $request->validate([
            'method_type' => ['required', 'string', 'in:bkash,nagad,rocket,bank'],
            'account_number' => ['required', 'string', 'max:60'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'number_type' => ['required_if:method_type,bkash,nagad,rocket', 'nullable', 'string', 'in:personal,agent,merchant'],
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
            'number_type' => $validated['number_type'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'routing_number' => $validated['routing_number'] ?? null,
            'is_default' => $isDefault,
        ]);

        return redirect()->route('partner.payouts.index')
            ->with('success', 'New payment method added successfully!');
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

        return redirect()->route('partner.payouts.index')
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

        return redirect()->route('partner.payouts.index')
            ->with('success', 'Default payout method updated.');
    }
}
