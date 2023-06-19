<?php

namespace App\Services\Event;

use App\Models\Event;

class CreateEventService
{
    public function handle(array $data)
    {
        $event = Event::create($data);

        return $event;
    }
}
