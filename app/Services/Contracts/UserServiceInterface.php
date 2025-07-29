<?php

namespace App\Services\Contracts;

use App\Http\Resources\Users\UserCollection;

interface UserServiceInterface
{
    function getAllUsers(array $filters = []): UserCollection;
    
    public function getFilterOptions(): array;
}
