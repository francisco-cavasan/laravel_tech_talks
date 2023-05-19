<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\EventSearchRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::with('users')->get();

        return response()->json(EventResource::collection($events));
    }

    public function show(Event $event)
    {
        return response()->json(new EventResource($event));
    }

    public function store(EventRequest $request)
    {
        $validated = $request->validated();

        $event = Event::create($validated);

        return response()->json(new EventResource($event), 201);
    }

    public function update(EventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $event->update($validated);

        return response()->json(new EventResource($event));
    }

    public function destroy(Event $event)
    {
        if ($event->usersCount > 0) {
            return response()->json([
                'message' => 'Cannot delete event with users',
            ], 400);
        }

        $event->delete();

        return response()->json(null, 204);
    }

    public function search(EventSearchRequest $request)
    {
        $validated = $request->validated();

        $events = Event::with('users')->query();

        if ($validated['start'] && $validated['end']) {
            $events->where('start', '>=', $validated['start'])
                ->where('end', '<=', $validated['end']);
        }

        if ($validated['name']) {
            $events->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if ($validated['user_email']) {
            $events->whereHas('users', function ($query) use ($validated) {
                $query->where('email', $validated['user_email']);
            });
        }

        return response()->json(EventResource::collection($events->get()));
    }
}
