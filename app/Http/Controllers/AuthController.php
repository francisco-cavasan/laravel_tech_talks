<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = User::where('email', $validated['email'])->first();

        /** @var User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function signUp(SignupRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function currentAuthUser(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'message' => 'User retrieved successfully',
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
        ], 200);
    }
}
