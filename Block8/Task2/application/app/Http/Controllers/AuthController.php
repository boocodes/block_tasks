<?php

namespace Final2\App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Final2\App\Http\Requests\LoginRequest;
use Final2\App\Http\Requests\RegistrationRequest;
use Final2\App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(RegistrationRequest $request): JsonResponse
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
    public function loginRequest(LoginRequest $request)
    {
        $data = $request->validated();
        $user = new User()->query()
            ->where('email', $data['email'])
            ->first();
        if(!$user || !Hash::check($data['password'], $user->password))
            {
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
    public function me(Request $request): JsonResponse
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
