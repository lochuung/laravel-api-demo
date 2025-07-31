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
use App\Http\Resources\Users\UserResource;
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

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->register($request->validated()),
            'auth.registered'
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->login($request->validated()),
            'auth.logged_in'
        );
    }

    public function logout(Request $request): void
    {
        $this->authService->logout($request);
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->refresh($request->validated()),
            'auth.token_refreshed'
        );
    }

    public function getMyProfile(Request $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            new UserResource($request->user()),
            'auth.profile_retrieved'
        );
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->verifyEmail($request->validated()),
            'auth.email_verified'
        );
    }

    public function resendVerificationEmail(ResendVerificationEmailRequest $request): JsonResponse
    {
        $this->authService->resendVerificationEmail($request->validated());

        return $this->apiSuccessResponse([], 'auth.verification_sent');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->validated());

        return $this->apiSuccessResponse([], 'auth.password_forgot');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->authService->resetPassword($request->validated()),
            'auth.password_reset'
        );
    }
}
