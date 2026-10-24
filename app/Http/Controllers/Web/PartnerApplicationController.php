<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PartnerApplicationController extends Controller
{
    /**
     * Store new partner enlistment application submitted from web.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'trade_domain' => ['required', 'string', 'max:150'],
            'technicians_count' => ['required', 'string', 'max:50'],
            'coverage_areas' => ['required', 'string', 'max:1000'],
        ]);

        $partner = Partner::create([
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'trade_domain' => $validated['trade_domain'],
            'technicians_count' => $validated['technicians_count'],
            'coverage_areas' => $validated['coverage_areas'],
            'status' => 'pending',
            'partner_type' => 'company',
        ]);

        return back()->with('success', 'Application received successfully! Our Partner Operations Lead will review your trade profile and contact you within 24 hours for verification.');
    }
}
