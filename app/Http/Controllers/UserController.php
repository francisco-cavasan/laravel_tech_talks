<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\User;
use App\Services\User\AttachUserToEventService;
use App\Services\User\CreateUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\DetachUserFromEventService;
use App\Services\User\UpdateUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class UsersController extends Controller
{
    private CreateUserService $createUserService;
    private UpdateUserService $updateUserService;
    private DeleteUserService $deleteUserService;
    private AttachUserToEventService $attachUserToEventService;
    private DetachUserFromEventService $detachUserFromEventService;

    public function __construct(
        CreateUserService $createUserService,
        UpdateUserService $updateUserService,
        DeleteUserService $deleteUserService,
        AttachUserToEventService $attachUserToEventService,
        DetachUserFromEventService $detachUserFromEventService
    ) {
        $this->createUserService = $createUserService;
        $this->updateUserService = $updateUserService;
        $this->deleteUserService = $deleteUserService;
        $this->attachUserToEventService = $attachUserToEventService;
        $this->detachUserFromEventService = $detachUserFromEventService;
    }

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

        try {
            DB::beginTransaction();

            $user = $this->createUserService->handle($validated);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Cannot create user',
            ], 400);
        }

        return response()->json(new UserResource($user), 201);
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $user = $this->updateUserService->handle($user, $validated);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Cannot update user',
            ], 400);
        }

        return response()->json(new UserResource($user));
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $user->load('events');

            $this->deleteUserService->handle($user);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Cannot update user',
            ], 400);
        }

        return response()->json([], 200);
    }

    public function addToEvent(Request $request)
    {
        $inputs = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ]);

        try {
            DB::beginTransaction();

            $this->attachUserToEventService->handle($inputs);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Cannot add user to event',
            ], 400);
        }

        return response()->json([], 204);
    }

    public function removeFromEvent(User $user, Event $event)
    {
        try {
            DB::beforeExecuting();

            $this->detachUserFromEventService->handle($user, $event);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Cannot remove user from event',
            ], 400);
        }

        return response()->json([], 204);
    }
}
