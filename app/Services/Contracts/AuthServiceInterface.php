<?php

namespace App\Services\Contracts;

use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(array $data): UserResource;

    public function login(array $credentials): AuthResource;

    public function logout(Request $request): void;

    public function refresh(array $data);

    public function verifyEmail(array $data): UserResource;

    public function resendVerificationEmail(array $data): bool;

    public function forgotPassword(array $data): bool;

    public function resetPassword(array $data): UserResource;
}
