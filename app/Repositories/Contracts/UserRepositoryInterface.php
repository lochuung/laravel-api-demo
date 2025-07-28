<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function createWithHashedPassword(array $data): User;

    public function findByEmailVerificationToken(string $token): ?User;

    public function getActiveUsers(): \Illuminate\Database\Eloquent\Collection;

    public function searchByName(string $name): \Illuminate\Database\Eloquent\Collection;

    public function updateEmailVerificationStatus(User $user, bool $verified): User;

    public function generateEmailVerificationToken(User $user): User;

    public function emailExists(string $email): bool;
}
