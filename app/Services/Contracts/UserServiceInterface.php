<?php

namespace App\Services\Contracts;

use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use phpseclib3\File\ASN1\Maps\Pentanomial;

interface UserServiceInterface
{
    function getAllUsers(array $filters = []): UserCollection;

    function getUserById(int $id): UserResource;

    public function getFilterOptions(): array;

    public function deleteById(int $id): void;

    public function createUser(array $data): UserResource;

    public function updateUser(int $id, array $data): UserResource;

}
