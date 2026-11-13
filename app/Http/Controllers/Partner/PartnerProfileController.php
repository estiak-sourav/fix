<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerProfileController extends Controller
{
    /**
     * Show profile & notification preferences view.
     */
    public function index(): View
    {
        $partner = Auth::guard('partner')->user();
        $activeCategories = ServiceCategory::where('is_active', true)->orderBy('order')->get();
        $activeDivisions = Division::whereHas('districts', function ($q) {
            $q->where('is_active', true);
        })->with(['districts' => function ($q) {
            $q->where('is_active', true)->with(['thanas' => function ($t) {
                $t->where('is_active', true)->orderBy('name');
            }])->orderBy('name');
        }])->orderBy('name')->get();

        $activeDistricts = District::where('is_active', true)
            ->with(['division', 'thanas' => function ($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('partner.profile', compact('partner', 'activeCategories', 'activeDivisions', 'activeDistricts'));
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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'remove_avatar' => ['nullable', 'boolean'],
            'trade_domain' => ['nullable'],
            'trade_domain.*' => ['string', 'max:150'],
            'coverage_areas' => ['nullable'],
            'coverage_areas.*' => ['string', 'max:150'],
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

        $tradeDomains = null;
        if ($request->has('trade_domain_submitted') || $request->has('trade_domain')) {
            $tradeDomains = is_array($request->input('trade_domain'))
                ? implode(', ', array_filter($request->input('trade_domain')))
                : (string) $request->input('trade_domain');
        }

        $coverageAreas = null;
        if ($request->has('coverage_areas_submitted') || $request->has('coverage_areas')) {
            if (is_array($request->input('coverage_areas'))) {
                $selectedAreas = array_values(array_unique(array_filter($request->input('coverage_areas'))));
                $coverageAreas = implode(', ', $selectedAreas);
            } else {
                $coverageAreas = (string) ($request->input('coverage_areas') ?? '');
            }
        }

        $updateData = [
            'contact_person' => $validated['contact_person'],
            'email' => $request->filled('email') ? (string) $request->input('email') : null,
            'payment_method' => $request->input('payment_method') ?: $partner->payment_method,
            'bank_name' => $request->input('bank_name') ?: null,
            'account_holder' => $request->input('account_holder') ?: null,
            'account_number' => $request->input('account_number') ?: null,
            'branch_name' => $request->input('branch_name') ?: null,
            'mfs_number' => $request->input('mfs_number') ?: null,
            'notification_preferences' => [
                'job_assigned' => $request->boolean('notify_job_assigned', true),
                'job_updated' => $request->boolean('notify_job_updated', true),
                'payout_sent' => $request->boolean('notify_payout_sent', true),
                'job_cancelled' => $request->boolean('notify_job_cancelled', true),
            ],
        ];

        if ($tradeDomains !== null) {
            $updateData['trade_domain'] = $tradeDomains;
        }

        if ($coverageAreas !== null) {
            $updateData['coverage_areas'] = $coverageAreas;
        }

        // Handle avatar upload or removal
        if ($request->hasFile('avatar')) {
            if ($partner->avatar && ! str_starts_with($partner->avatar, 'http')) {
                Storage::disk('public')->delete($partner->avatar);
            }
            $path = $request->file('avatar')->store('avatars/partners', 'public');
            $updateData['avatar'] = $path;
        } elseif ($request->boolean('remove_avatar')) {
            if ($partner->avatar && ! str_starts_with($partner->avatar, 'http')) {
                Storage::disk('public')->delete($partner->avatar);
            }
            $updateData['avatar'] = null;
        }

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
