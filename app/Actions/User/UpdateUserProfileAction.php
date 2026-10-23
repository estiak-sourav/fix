<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfileAction
{
    /**
     * Update customer profile attributes including DP/avatar upload.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data): User
    {
        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $data['avatar']->store('avatars/users', 'public');
        } elseif (! empty($data['remove_avatar'])) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        } else {
            unset($data['avatar']);
        }

        // Handle optional password update
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['remove_avatar'], $data['password_confirmation']);

        $user->update($data);

        return $user;
    }
}
