<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    public function test_deleting_an_user(): void
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

        app(DeleteUserService::class)->handle($user);

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);
    }
}
