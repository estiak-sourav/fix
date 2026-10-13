<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    /**
     * Authenticate and start a web session for the customer.
     */
    public function execute(User $user, Request $request, bool $remember = true): void
    {
        Auth::guard('web')->login($user, $remember);
        $request->session()->regenerate();
    }
}
