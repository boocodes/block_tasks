<?php

namespace Final6\App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Final6\App\Http\Requests\LoginRequest;
use Final6\App\Http\Requests\RegistrationRequest;
use Final6\App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(RegistrationRequest $request): JsonResponse
    {
        return $this->userService->registration($request);
    }
    public function loginRequest(LoginRequest $request)
    {
        return $this->userService->login($request);
    }
    public function me(Request $request): JsonResponse
    {
        return $this->userService->me($request);
    }
    public function logout(Request $request): JsonResponse
    {
        return $this->userService->logout($request);
    }
}
