<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Auth\LoginAdminAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show admin login screen.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
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

        $intended = $request->session()->get('url.intended');
        if ($intended && (str_contains($intended, '/admin') || str_contains($intended, 'admin.'))) {
            return redirect()->intended(route('admin.dashboard'));
        }

        $request->session()->forget('url.intended');

        return redirect()->route('admin.dashboard');
    }

    /**
     * Admin logout on isolated admin guard.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out from Admin Panel.');
    }
}
