<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Partner;
use App\Services\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PartnerApplicationController extends Controller
{
    /**
     * Store new partner enlistment application submitted from web.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'trade_domain' => ['required'],
            'trade_domain.*' => ['string', 'max:150'],
            'technicians_count' => ['required', 'string', 'max:50'],
            'coverage_areas' => ['required'],
            'coverage_areas.*' => ['string', 'max:150'],
            'other_coverage_areas' => ['nullable', 'string', 'max:255'],
        ], [
            'trade_domain.required' => 'Please select at least one primary technical domain.',
            'coverage_areas.required' => 'Please select at least one operating coverage area.',
        ]);

        $tradeDomains = is_array($request->input('trade_domain'))
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

        $partner = Partner::create([
            'company_name' => (string) $request->input('company_name'),
            'contact_person' => (string) $request->input('contact_person'),
            'phone' => (string) $request->input('phone'),
            'email' => $request->input('email') ?: null,
            'trade_domain' => $tradeDomains,
            'technicians_count' => (string) $request->input('technicians_count'),
            'coverage_areas' => $coverageAreas,
            'status' => 'pending',
            'partner_type' => 'company',
        ]);

        AdminNotification::create([
            'type' => 'partner_application',
            'title' => 'New Partner Enlistment',
            'message' => "Contractor '{$partner->company_name}' ({$partner->contact_person}, Phone: {$partner->phone}) applied for enlistment.",
            'action_url' => route('admin.partners.show', $partner),
            'is_read' => false,
            'sound_played' => false,
        ]);

        NotificationDispatcher::notifyAdminSection(
            'partners',
            "New Partner Enlistment: {$partner->company_name}",
            "New contractor enlistment received from {$partner->company_name} ({$partner->contact_person}, Phone: {$partner->phone}). Review and verify application in CMS.",
            route('admin.partners.show', $partner),
            [
                'Company' => $partner->company_name,
                'Contact' => $partner->contact_person,
                'Phone' => $partner->phone,
                'Domain' => $partner->trade_domain,
                'Areas' => $partner->coverage_areas,
            ]
        );

        return back()->with('success', 'Application received successfully! Our Partner Operations Lead will review your trade profile and contact you within 24 hours for verification.');
    }
}
