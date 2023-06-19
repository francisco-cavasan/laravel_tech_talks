<?php

namespace App\Services\User;

use App\Models\User;

class DeleteUserService
{

    public function handle(User $user)
    {
        if ($user->events_count > 0) {
            $user->events()->detach();
        }

        $user->delete();
    }
}
