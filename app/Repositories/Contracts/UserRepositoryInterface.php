<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function createWithHashedPassword(array $data): User;

    public function findByEmailVerificationToken(string $token): ?User;

    public function getActiveUsers(): Collection;

    public function searchByName(string $name): Collection;

    public function updateEmailVerificationStatus(User $user, bool $verified): User;

    public function generateEmailVerificationToken(User $user): User;

    public function emailExists(string $email): bool;

    public function getLatestUsers(int $limit = 5): Collection;

    public function searchAndFilter(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function getFilterOptions(): array;

    public function findByIdWithOrders(int $id): ?User;
}
