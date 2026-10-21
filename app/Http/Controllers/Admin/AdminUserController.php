<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display client users list with filters and control actions.
     */
    public function index(Request $request): View
    {
        $query = User::withCount('bookings')->latest();

        // Filter by role if specified, default to showing customers/clients first
        if ($role = $request->query('role')) {
            $query->where('role', $role);
        } else {
            // Default filter to customers or allow seeing all
            if ($request->query('show_all') !== '1') {
                $query->where('role', 'customer');
            }
        }

        // Filter by status (active / inactive)
        if ($request->has('status') && $request->query('status') !== '') {
            $query->where('is_active', $request->boolean('status'));
        }

        // Filter by property type
        if ($propertyType = $request->query('property_type')) {
            $query->where('property_type', $propertyType);
        }

        // Search by name, email, phone, company, or area
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total_clients' => User::where('role', 'customer')->count(),
            'active_clients' => User::where('role', 'customer')->where('is_active', true)->count(),
            'deactivated_clients' => User::where('role', 'customer')->where('is_active', false)->count(),
            'corporate_clients' => User::where('role', 'customer')->whereNotNull('company_name')->where('company_name', '!=', '')->count(),
            'total_bookings' => Booking::whereNotNull('user_id')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display detailed profile & booking history of a client.
     */
    public function show(User $user): View
    {
        $user->loadCount('bookings');
        $bookings = $user->bookings()->with(['service', 'amcPlan'])->paginate(10);

        return view('admin.users.show', compact('user', 'bookings'));
    }

    /**
     * Toggle active/deactivated status of client user.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $statusLabel = $user->is_active ? 'Activated' : 'Deactivated';

        return redirect()->back()->with('success', "Client account '{$user->name}' is now {$statusLabel}.");
    }

    /**
     * Update client user profile details from admin console.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'property_type' => ['nullable', 'string', 'max:50'],
            'default_address' => ['nullable', 'string', 'max:500'],
            'area' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->back()->with('success', "Client profile for '{$user->name}' updated successfully.");
    }
}
