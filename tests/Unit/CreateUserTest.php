<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\User\CreateUserService;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function test_creating_a_new_user(): void
    {
        $data = [
            'email' => fake()->email(),
            'password' => fake()->password(),
            'name' => fake()->name(),
        ];

        $user = app(CreateUserService::class)->handle($data);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);

        $this->assertInstanceOf(User::class, $user);
    }
}
