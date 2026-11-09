<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLocationController extends Controller
{
    /**
     * Display service coverage districts and thanas manager.
     */
    public function index(Request $request): View
    {
        $selectedDivisionId = $request->input('division_id');
        $search = trim((string) $request->input('q'));

        $divisions = Division::withCount([
            'districts',
            'districts as active_districts_count' => function ($q) {
                $q->where('is_active', true);
            },
        ])->get();

        $districtsQuery = District::with(['division', 'thanas' => function ($q) {
            $q->orderBy('name');
        }])->withCount('thanas');

        if ($selectedDivisionId) {
            $districtsQuery->where('division_id', $selectedDivisionId);
        }

        if ($search !== '') {
            $districtsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('bn_name', 'like', "%{$search}%")
                    ->orWhereHas('thanas', function ($t) use ($search) {
                        $t->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $districts = $districtsQuery->orderBy('name')->get();

        $activeDistrictsCount = District::where('is_active', true)->count();
        $totalDistrictsCount = District::count();
        $totalThanasCount = Thana::count();

        return view('admin.locations.index', compact(
            'divisions',
            'districts',
            'selectedDivisionId',
            'search',
            'activeDistrictsCount',
            'totalDistrictsCount',
            'totalThanasCount'
        ));
    }

    /**
     * Toggle serviceable active status for a district.
     */
    public function toggleDistrict(District $district, Request $request): RedirectResponse|JsonResponse
    {
        $district->update(['is_active' => ! $district->is_active]);

        $statusStr = $district->is_active ? 'activated for service' : 'deactivated';
        ActivityLogService::log(
            'locations',
            'district.toggle',
            "District '{$district->name}' was {$statusStr}.",
            $district
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $district->is_active,
                'message' => "District '{$district->name}' is now {$statusStr}.",
            ]);
        }

        return redirect()->back()->with('success', "District '{$district->name}' is now {$statusStr}.");
    }

    /**
     * Store new Thana / Area under a District.
     */
    public function storeThana(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name' => ['required', 'string', 'max:80'],
            'bn_name' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $district = District::findOrFail($validated['district_id']);

        $thana = Thana::create([
            'district_id' => $district->id,
            'name' => trim($validated['name']),
            'bn_name' => $validated['bn_name'] ? trim($validated['bn_name']) : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLogService::log(
            'locations',
            'thana.create',
            "Added new Thana '{$thana->name}' under District '{$district->name}'.",
            $thana
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'thana' => $thana,
                'message' => "Thana '{$thana->name}' added successfully.",
            ]);
        }

        return redirect()->back()->with('success', "Thana '{$thana->name}' added successfully under {$district->name}.");
    }

    /**
     * Update an existing Thana / Area name and active state.
     */
    public function updateThana(Request $request, Thana $thana): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'bn_name' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $thana->update([
            'name' => trim($validated['name']),
            'bn_name' => $validated['bn_name'] ? trim($validated['bn_name']) : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLogService::log(
            'locations',
            'thana.update',
            "Updated Thana '{$thana->name}'.",
            $thana
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'thana' => $thana,
                'message' => "Thana '{$thana->name}' updated successfully.",
            ]);
        }

        return redirect()->back()->with('success', "Thana '{$thana->name}' updated successfully.");
    }

    /**
     * Toggle Thana active status.
     */
    public function toggleThana(Thana $thana, Request $request): RedirectResponse|JsonResponse
    {
        $thana->update(['is_active' => ! $thana->is_active]);

        $statusStr = $thana->is_active ? 'activated' : 'deactivated';
        ActivityLogService::log(
            'locations',
            'thana.toggle',
            "Thana '{$thana->name}' was {$statusStr}.",
            $thana
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $thana->is_active,
                'message' => "Thana '{$thana->name}' {$statusStr}.",
            ]);
        }

        return redirect()->back()->with('success', "Thana '{$thana->name}' {$statusStr}.");
    }

    /**
     * Delete a Thana.
     */
    public function destroyThana(Thana $thana, Request $request): RedirectResponse|JsonResponse
    {
        $name = $thana->name;
        $districtName = $thana->district?->name ?? 'District';
        $thana->delete();

        ActivityLogService::log(
            'locations',
            'thana.delete',
            "Deleted Thana '{$name}' from {$districtName}."
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Thana '{$name}' deleted successfully.",
            ]);
        }

        return redirect()->back()->with('success', "Thana '{$name}' deleted successfully.");
    }
}
