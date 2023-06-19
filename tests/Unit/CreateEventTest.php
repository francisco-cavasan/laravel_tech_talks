<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Services\Event\CreateEventService;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    public function test_creating_a_new_event(): void
    {
        $startsAt = fake()->dateTimeBetween('-2 week', '-1 week');
        $endsAt = fake()->dateTimeBetween($startsAt, '-1 week');

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

        // assert that event is finished
        $this->assertTrue($event->is_finished);

        $this->assertInstanceOf(Event::class, $event);
    }
}
