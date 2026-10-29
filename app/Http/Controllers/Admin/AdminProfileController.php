<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    /**
     * Show administrator self-profile management screen.
     */
    public function edit(): View
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update administrator profile credentials, display picture (DP) and security password.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'string', 'current_password:admin', 'required_with:password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', 'different:current_password', 'required_with:current_password'],
            'password_confirmation' => ['nullable', 'string', 'required_with:password'],
        ], [
            'current_password.required_with' => 'Please enter your current password to set a new password.',
            'current_password.current_password' => 'The current password you entered is incorrect.',
            'password.required_with' => 'Please enter a new password if you provided your current password.',
            'password.min' => 'The new password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.different' => 'The new password must be different from your current password.',
            'password_confirmation.required_with' => 'Please confirm your new password.',
        ]);

        // Handle Avatar (DP) upload
        if ($request->hasFile('avatar')) {
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $admin->avatar = $request->file('avatar')->store('avatars/admins', 'public');
        } elseif ($request->boolean('remove_avatar')) {
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $admin->avatar = null;
        }

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;

        // Handle optional password update
        if (! empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        ActivityLogService::logAuth('profile.update', "Staff member '{$admin->name}' updated their profile information and display picture.", $admin);

        return redirect()->route('admin.profile')
            ->with('success', 'Your administrator profile and display picture have been updated successfully.');
    }
}
