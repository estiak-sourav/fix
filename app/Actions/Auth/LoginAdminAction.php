<?php

namespace App\Actions\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginAdminAction
{
    /**
     * Authenticate an admin user on the admin guard.
     *
     * @param  array{email: string, password: string, remember?: bool}  $credentials
     *
     * @throws ValidationException
     */
    public function execute(array $credentials, Request $request): Admin
    {
        $remember = (bool) ($credentials['remember'] ?? false);

        $attempt = Auth::guard('admin')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember);

        if (! $attempt) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $request->session()->regenerate();

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        return $admin;
    }
}
