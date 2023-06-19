<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\EventSearchRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\Event\CreateEventService;
use Illuminate\Support\Facades\DB;
use Throwable;

class EventsController extends Controller
{
    private CreateEventService $createEventService;

    public function __construct(CreateEventService $createEventService)
    {
        $this->createEventService = $createEventService;
    }

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

        try {
            $event = $this->createEventService->handle($validated);
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Cannot create user',
            ], 400);
        }

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
        if ($event->users_count > 0) {
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

        if ($validated['starts_at'] && $validated['ends_at']) {
            $events->where('starts_at', '>=', $validated['starts_at'])
                ->where('ends_at', '<=', $validated['ends_at']);
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
