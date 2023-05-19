<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        return response()->json(UserResource::collection($users));
    }

    public function show(User $user)
    {
        return response()->json(new UserResource($user));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        return response()->json(new UserResource($user), 201);
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        return response()->json(new UserResource($user));
    }

    public function destroy(User $user)
    {
        if ($user->eventsCount > 0) {
            $user->events()->detach();
        }

        $user->delete();

        return response()->json(null, 204);
    }

    public function addToEvent(Request $request)
    {
        $inputs = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $user = User::find($inputs['user_id']);
        $event = Event::find($inputs['event_id']);


        if ($user->events()->where('event_id', $event->id)->exists()) {
            return response()->json([
                'message' => 'User already added to event',
            ], 400);
        }

        if ($event->is_finished) {
            return response()->json([
                'message' => 'Cannot add user to finished event',
            ], 400);
        }

        $user->events()->attach($event);

        return response()->json([], 204);
    }

    public function removeFromEvent(User $user, Event $event)
    {
        $user->events()->detach($event);

        return response()->json([], 204);
    }
}
