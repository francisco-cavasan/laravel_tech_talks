<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\Services\Event\CreateEventService;
use App\Services\User\AttachUserToEventService;
use App\Services\User\CreateUserService;
use App\Services\User\DetachUserFromEventService;
use Tests\TestCase;

class DetachUserFromEventTest extends TestCase
{
    public function test_attaching_an_user_to_an_event(): void
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

        $startsAt = fake()->dateTimeBetween('+1 day', '+2 days');
        $endsAt = fake()->dateTimeBetween($startsAt, '+3 days');

        $data = [
            'name' => fake()->name(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'type' => 'public',
        ];

        $event = app(CreateEventService::class)->handle($data);

        $this->assertDatabaseHas('events', [
            'name' => $data['name'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'type' => $data['type'],
        ]);

        $this->assertInstanceOf(Event::class, $event);

        $data = [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ];

        app(AttachUserToEventService::class)->handle($data);

        $this->assertDatabaseHas('users_events', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        app(DetachUserFromEventService::class)->handle($user, $event);

        $this->assertDatabaseMissing('users_events', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }
}
