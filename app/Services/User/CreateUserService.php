<?php

namespace App\Services\User;

use App\Models\User;

class CreateUserService
{
    public function handle(array $data)
    {
        $user = User::create($data);

        return $user;
    }
}
