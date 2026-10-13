<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateUserProfileAction
{
    /**
     * Update customer profile attributes.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}
