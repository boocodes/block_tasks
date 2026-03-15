<?php

namespace Task2\App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Task2\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Task2\App\Models\User;
use Task2\App\Http\Controllers\Task\BaseController;
use Task2\App\Http\Requests\User\RegistrationRequest;
use Task2\App\Http\Requests\User\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = new User()->query()
            ->create($data);
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function login(LoginRequest $request): JsonResponse
    {

        $data = $request->validated();
        $user = new User()->query()
            ->where('email', $data['email'])
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'error' => 'Wrong email or password',
            ], 422);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }
    public function getMe(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out',
        ], 200);
    }
}
