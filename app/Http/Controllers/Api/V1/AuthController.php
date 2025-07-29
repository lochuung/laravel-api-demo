<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendVerificationEmailRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Resources\Auth\UserResource;
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

    function register(RegisterRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse($this->authService->register($request->validated()));
    }

    function login(LoginRequest $request): JsonResponse
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

    function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->verifyEmail($request->validated())
        );
    }

    function resendVerificationEmail(ResendVerificationEmailRequest $request): JsonResponse
    {
        $this->authService->resendVerificationEmail($request->validated());

        return $this->apiSuccessResponse(
            message: 'Verification email sent successfully.'
        );
    }

    function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->validated());

        return $this->apiSuccessResponse(
            message: 'Password reset email sent successfully.'
        );
    }

    function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->resetPassword($request->validated())
        );
    }
}
