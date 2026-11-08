<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerCrew;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerCrewController extends Controller
{
    /**
     * Display internal technicians / field crew members (Vendor only).
     */
    public function index(): View|RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        $crews = $partner->crews()->latest()->get();

        return view('partner.crews.index', compact('partner', 'crews'));
    }

    /**
     * Add a new technician/crew member to the vendor's internal roster.
     */
    public function store(Request $request): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'trade_skill' => ['nullable', 'string', 'max:150'],
        ]);

        $partner->crews()->create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'trade_skill' => $validated['trade_skill'] ?: null,
            'is_active' => true,
        ]);

        return back()->with('success', "Technician '{$validated['name']}' added to your team roster!");
    }

    /**
     * Update an internal crew member's details.
     */
    public function update(Request $request, PartnerCrew $crew): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $crew->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'trade_skill' => ['nullable', 'string', 'max:150'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $crew->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'trade_skill' => $validated['trade_skill'] ?: null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Crew member '{$crew->name}' updated successfully.");
    }

    /**
     * Remove technician from roster.
     */
    public function destroy(PartnerCrew $crew): RedirectResponse
    {
        $partner = Auth::guard('partner')->user();

        if ((int) $crew->partner_id !== (int) $partner->id) {
            abort(403);
        }

        $name = $crew->name;
        $crew->delete();

        return back()->with('success', "Technician '{$name}' removed from your team.");
    }
}
