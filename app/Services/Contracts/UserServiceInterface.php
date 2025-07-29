<?php

namespace App\Services\Contracts;

use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;

interface UserServiceInterface
{
    function getAllUsers(array $filters = []): UserCollection;

    function getUserById(int $id): UserResource;

    public function getFilterOptions(): array;
}
