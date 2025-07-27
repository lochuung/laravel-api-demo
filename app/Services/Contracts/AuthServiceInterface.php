<?php

namespace App\Services\Contracts;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(array $data): UserResource;

    public function login(array $credentials): AuthResource;

    public function logout(Request $request): void;

    public function refresh(array $data);
}
