<?php

namespace App\Services\Contracts;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;

interface AuthServiceInterface
{
    public function register(array $data): UserResource;

    public function login(array $credentials): AuthResource;

    public function logout(): void;
}
