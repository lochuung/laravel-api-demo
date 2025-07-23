<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    function register(RegisterRequest $request): UserResource
    {
        return $this->authService->register($request->validated());
    }

    function login(LoginRequest $request): AuthResource
    {
        return $this->authService->login($request->validated());
    }

    function logout(): void
    {
        $this->authService->logout();
    }
}
