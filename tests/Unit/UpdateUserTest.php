<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\User\CreateUserService;
use App\Services\User\UpdateUserService;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    public function test_updating_an_user(): void
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

        $newData = [
            'email' => fake()->email(),
            'password' => fake()->password(),
            'name' => fake()->name(),
        ];

        app(UpdateUserService::class)->handle($user, $newData);

        $this->assertDatabaseHas('users', [
            'email' => $newData['email'],
            'name' => $newData['name'],
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);
    }
}
