<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PartnerProfileController extends Controller
{
    /**
     * Show profile & notification preferences view.
     */
    public function index(): View
    {
        $partner = Auth::guard('partner')->user();

        return view('partner.profile', compact('partner'));
    }

    /**
     * Update partner profile, banking, and email notification preferences.
     */
    public function update(Request $request): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        $validated = $request->validate([
            'contact_person' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'coverage_areas' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', 'string', 'in:bank,bkash,nagad,rocket'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'mfs_number' => ['nullable', 'string', 'max:30'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'notify_job_assigned' => ['nullable', 'boolean'],
            'notify_job_updated' => ['nullable', 'boolean'],
            'notify_payout_sent' => ['nullable', 'boolean'],
            'notify_job_cancelled' => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'] ?: null,
            'coverage_areas' => $validated['coverage_areas'] ?: $partner->coverage_areas,
            'payment_method' => $validated['payment_method'] ?: $partner->payment_method,
            'bank_name' => $validated['bank_name'] ?: null,
            'account_holder' => $validated['account_holder'] ?: null,
            'account_number' => $validated['account_number'] ?: null,
            'branch_name' => $validated['branch_name'] ?: null,
            'mfs_number' => $validated['mfs_number'] ?: null,
            'notification_preferences' => [
                'job_assigned' => $request->boolean('notify_job_assigned', true),
                'job_updated' => $request->boolean('notify_job_updated', true),
                'payout_sent' => $request->boolean('notify_payout_sent', true),
                'job_cancelled' => $request->boolean('notify_job_cancelled', true),
            ],
        ];

        // Handle password change if requested
        if ($request->filled('new_password')) {
            if (! empty($partner->password) && ! Hash::check($request->input('current_password'), $partner->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $partner->update($updateData);

        return back()->with('success', 'Profile and notification preferences updated successfully.');
    }
}
