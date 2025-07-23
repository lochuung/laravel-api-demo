<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findBy('email', $email);
    }

    public function createWithHashedPassword(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    public function findByEmailVerificationToken(string $token): ?User
    {
        return $this->findBy('email_verification_token', $token);
    }

    public function getActiveUsers(): Collection
    {
        return $this->findWhere(['status' => 'active']);
    }

    public function searchByName(string $name): Collection
    {
        return $this->newQuery()
            ->where('name', 'LIKE', "%{$name}%")
            ->get();
    }

    /**
     * Get users with their posts
     */
    public function getUsersWithPosts(): Collection
    {
        return $this->with(['posts']);
    }

    /**
     * Get users ordered by creation date
     */
    public function getRecentUsers(int $limit = 10): Collection
    {
        return $this->newQuery()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        return $this->exists(['email' => $email]);
    }

}
