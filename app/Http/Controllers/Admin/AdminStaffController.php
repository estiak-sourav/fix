<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\ActivityLogService;
use App\Services\AdminPermissionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminStaffController extends Controller
{
    /**
     * Display directory of admin staff users and permissions.
     */
    public function index(Request $request): View
    {
        $query = Admin::query()->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        if ($request->has('status') && $request->query('status') !== '') {
            $query->where('is_active', $request->boolean('status'));
        }

        $staffMembers = $query->paginate(15)->withQueryString();

        $stats = [
            'total_staff' => Admin::count(),
            'active_staff' => Admin::where('is_active', true)->count(),
            'super_admins' => Admin::where('role', 'super_admin')->count(),
            'pending_password_change' => Admin::where('must_change_password', true)->count(),
        ];

        return view('admin.staff.index', compact('staffMembers', 'stats'));
    }

    /**
     * Show form to create a new administrator with role permissions.
     */
    public function create(): View
    {
        $modules = AdminPermissionService::allModules();

        return view('admin.staff.create', compact('modules'));
    }

    /**
     * Store new admin staff with default credentials & permission matrix.
     */
    public function store(Request $request): RedirectResponse
    {
        $allKeys = AdminPermissionService::allPermissionKeys();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'string', 'in:super_admin,admin,operations_manager'],
            'password' => ['required', 'string', 'min:8'],
            'must_change_password' => ['nullable', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allKeys)],
        ]);

        $admin = new Admin;
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->role = $validated['role'];
        $admin->password = Hash::make($validated['password']);
        $admin->is_active = true;
        // Default to true if checked, or if not provided default true for safety
        $admin->must_change_password = $request->boolean('must_change_password', true);

        // If super_admin, permissions aren't strictly required, but we can store them or null
        $admin->permissions = $validated['role'] === 'super_admin' ? $allKeys : ($validated['permissions'] ?? []);
        $admin->save();

        ActivityLogService::logStaff('staff.create', "Created admin staff account '{$admin->name}' ({$admin->email}) with role '{$admin->role}'.", $admin, [
            'role' => $admin->role,
            'must_change_password' => $admin->must_change_password,
            'permissions_count' => count($admin->permissions ?? []),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "Admin user '{$admin->name}' successfully created with assigned module permissions.");
    }

    /**
     * Show form to edit existing admin user profile & permissions.
     */
    public function edit(Admin $staff): View
    {
        $modules = AdminPermissionService::allModules();
        $staffPermissions = $staff->permissions ?? [];

        return view('admin.staff.edit', compact('staff', 'modules', 'staffPermissions'));
    }

    /**
     * Update existing admin staff details and permissions.
     */
    public function update(Request $request, Admin $staff): RedirectResponse
    {
        $allKeys = AdminPermissionService::allPermissionKeys();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($staff->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'string', 'in:super_admin,admin,operations_manager'],
            'password' => ['nullable', 'string', 'min:8'],
            'must_change_password' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allKeys)],
        ]);

        $currentUser = Auth::guard('admin')->user();

        // Safety: Do not allow current user to demote self from super_admin if they are the only super admin
        if ($staff->id === $currentUser?->id && $staff->role === 'super_admin' && $validated['role'] !== 'super_admin') {
            $otherSuperAdmins = Admin::where('role', 'super_admin')->where('id', '!=', $staff->id)->count();
            if ($otherSuperAdmins === 0) {
                return redirect()->back()->with('error', 'You cannot remove your own Super Admin role as you are the only Super Administrator.');
            }
        }

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->phone = $validated['phone'] ?? null;
        $staff->role = $validated['role'];
        $staff->is_active = $request->boolean('is_active', true);
        $staff->must_change_password = $request->boolean('must_change_password');

        if (! empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }

        $staff->permissions = $validated['role'] === 'super_admin' ? $allKeys : ($validated['permissions'] ?? []);
        $staff->save();

        ActivityLogService::logStaff('staff.update', "Updated admin staff profile & permissions for '{$staff->name}'.", $staff, [
            'role' => $staff->role,
            'is_active' => $staff->is_active,
            'permissions_count' => count($staff->permissions ?? []),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', "Admin user '{$staff->name}' updated successfully.");
    }

    /**
     * Toggle active status of admin account.
     */
    public function toggleStatus(Admin $staff): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();

        if ($staff->id === $currentUser?->id) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($staff->isSuperAdmin() && $staff->is_active) {
            $activeSuperAdmins = Admin::where('role', 'super_admin')->where('is_active', true)->count();
            if ($activeSuperAdmins <= 1) {
                return redirect()->back()->with('error', 'Cannot deactivate the only active Super Administrator.');
            }
        }

        $staff->is_active = ! $staff->is_active;
        $staff->save();

        $state = $staff->is_active ? 'Activated' : 'Deactivated';

        ActivityLogService::logStaff('staff.status_toggle', "Toggled active status for staff '{$staff->name}' to '{$state}'.", $staff, [
            'is_active' => $staff->is_active,
        ]);

        return redirect()->back()->with('success', "Staff account '{$staff->name}' is now {$state}.");
    }

    /**
     * Quick reset staff password with default password and enforce first login change.
     */
    public function resetPassword(Request $request, Admin $staff): RedirectResponse
    {
        $defaultPassword = 'password123';

        $staff->password = Hash::make($defaultPassword);
        $staff->must_change_password = true;
        $staff->save();

        ActivityLogService::logStaff('staff.password_reset', "Reset password for staff '{$staff->name}' to default and enforced first-login change.", $staff);

        return redirect()->back()->with('success', "Password for '{$staff->name}' reset to '{$defaultPassword}'. The user will be required to change it on their next login.");
    }

    /**
     * Remove an admin staff account.
     */
    public function destroy(Admin $staff): RedirectResponse
    {
        $currentUser = Auth::guard('admin')->user();

        if ($staff->id === $currentUser?->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($staff->isSuperAdmin()) {
            $superAdminsCount = Admin::where('role', 'super_admin')->count();
            if ($superAdminsCount <= 1) {
                return redirect()->back()->with('error', 'Cannot delete the only Super Administrator in the system.');
            }
        }

        $name = $staff->name;
        $email = $staff->email;
        $staff->delete();

        ActivityLogService::logStaff('staff.delete', "Deleted staff member account '{$name}' ({$email}).", null, [
            'deleted_staff_name' => $name,
            'deleted_staff_email' => $email,
        ]);

        return redirect()->route('admin.staff.index')->with('success', "Staff account '{$name}' has been deleted.");
    }
}
