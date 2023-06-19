<?php

namespace App\Services\User;

use App\Models\Event;
use App\Models\User;

class DetachUserFromEventService
{
    public function handle(User $user, Event $event): void
    {
        $user->events()->detach($event);
    }
}
