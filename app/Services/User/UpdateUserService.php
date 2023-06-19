<?php

namespace App\Services\User;

use App\Models\User;

class UpdateUserService
{

    public function handle(User $user, array $data)
    {
        $user->update($data);
    }
}
