<?php

namespace App\Services\User;

use App\Models\Event;
use App\Models\User;
use Exception;

class AttachUserToEventService
{
    public function handle(array $data): void
    {
        $user = User::find($data['user_id']);
        $event = Event::find($data['event_id']);

        if ($user->events()->where('event_id', $event->id)->exists()) {
            throw new Exception('User already attached to event');
        }

        if ($event->is_finished) {
            throw new Exception('Event is finished');
        }

        $user->events()->attach($event);
    }
}
