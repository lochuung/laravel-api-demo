<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->apiSuccessSingleResponse($this->authService->register($request->validated()));
    }

    function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->login($request->validated())
        );
    }

    function logout(Request $request): void
    {
        $this->authService->logout($request);
    }

    function refresh(RefreshTokenRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->refresh($request->validated())
        );
    }

    function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->apiSuccessSingleResponse(
            new UserResource($user)
        );
    }
}
