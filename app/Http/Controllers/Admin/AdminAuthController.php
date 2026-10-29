<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Auth\LoginAdminAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Admin;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * Show admin login screen.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            /** @var Admin $admin */
            $admin = Auth::guard('admin')->user();
            if ($admin->must_change_password) {
                return redirect()->route('admin.password.change');
            }

            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Authenticate admin credentials via LoginAdminAction.
     */
    public function login(AdminLoginRequest $request, LoginAdminAction $action): RedirectResponse
    {
        $action->execute($request->validated(), $request);

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        ActivityLogService::logAuth('auth.login', "Staff member '{$admin->name}' ({$admin->email}) logged into Command Center.", $admin);

        if ($admin && $admin->must_change_password) {
            return redirect()->route('admin.password.change');
        }

        $intended = $request->session()->get('url.intended');
        if ($intended && (str_contains($intended, '/admin') || str_contains($intended, 'admin.'))) {
            return redirect()->intended(route('admin.dashboard'));
        }

        $request->session()->forget('url.intended');

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show mandatory password change form.
     */
    public function showChangePasswordForm(): View|RedirectResponse
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.change-password');
    }

    /**
     * Update mandatory first-time password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $isFirstLogin = (bool) $admin->must_change_password;

        $request->validate([
            'current_password' => ['required', 'string', 'current_password:admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'password_confirmation' => ['required', 'string'],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'current_password.current_password' => 'The current password you entered is incorrect.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'The new password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.different' => 'The new password must be different from your current password.',
            'password_confirmation.required' => 'Please confirm your new password.',
        ]);

        $admin->password = Hash::make($request->password);
        $admin->must_change_password = false;
        $admin->save();

        ActivityLogService::logAuth('auth.password_update', "Staff member '{$admin->name}' updated their password.", $admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Your password has been updated successfully.');
    }

    /**
     * Admin logout on isolated admin guard.
     */
    public function logout(Request $request): RedirectResponse
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            ActivityLogService::logAuth('auth.logout', "Staff member '{$admin->name}' logged out.", $admin);
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out from Admin Panel.');
    }
}
