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
    public function getRecentUsers(int $limit = 5): Collection
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

    /**
     * Update email verification status
     */
    public function updateEmailVerificationStatus(User $user, bool $verified): User
    {
        $user->update([
            'email_verified' => $verified,
            'email_verified_at' => $verified ? now() : null,
            'email_verification_token' => null,
            'email_verification_token_expires_at' => null,
        ]);

        return $user->fresh();
    }

    /**
     * Generate email verification token
     */
    public function generateEmailVerificationToken(User $user): User
    {
        $token = \Str::random(64);

        $user->update([
            'email_verification_token' => $token,
            'email_verification_token_expires_at' => now()->addHours(24),
        ]);

        return $user->fresh();
    }

    public function getLatestUsers(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        // TODO: Implement getLatestUsers() method.
        return $this->newQuery()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
