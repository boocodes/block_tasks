<?php

namespace Final3\App\Services;

use Final3\App\Http\Requests\LoginRequest;
use Final3\App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use Final3\App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function registration(RegistrationRequest $request)
    {
        $data = $request->validated();
        $user = new User()->query()
            ->create($data);
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = new User()->query()
            ->where('email', $data['email'])
            ->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'error' => 'Wrong email or password'
            ], 422);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out',
        ], 200);
    }
    public function delete(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $user->delete();
        return response()->json([
        ], 204);
    }
}
