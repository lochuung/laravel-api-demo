<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->findBy('email', $email);
        return $user;
    }

    public function createWithHashedPassword(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        /** @var User $user */
        $user = $this->create($data);
        return $user;
    }

    public function findByEmailVerificationToken(string $token): ?User
    {
        /** @var User|null $user */
        $user = $this->findBy('email_verification_token', $token);
        return $user;
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
        $token = Str::random(64);

        $user->update([
            'email_verification_token' => $token,
            'email_verification_token_expires_at' => now()->addHours(24),
        ]);

        return $user->fresh();
    }

    public function getLatestUsers(int $limit = 5): Collection
    {
        // TODO: Implement getLatestUsers() method.
        return $this->newQuery()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function searchAndFilter(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        // TODO: Implement searchAndFilter() method.
        $query = $this->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'LIKE', "%{$filters['search']}%")
                    ->orWhere('email', 'LIKE', "%{$filters['search']}%");
            });
        }

        // Filter by role
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function getFilterOptions(): array
    {
        return [
            'roles' => $this->newQuery()->distinct()->pluck('role')->filter()->values(),
        ];
    }

    public function findByIdWithOrders(int $id): User
    {
        // TODO: Implement findByIdWithOrders() method.
        return $this->newQuery()
            ->withCount('orders as orders_count')
            ->withSum(['orders as total_spent'], 'total_amount')
            ->with('orders')
            ->find($id);
    }
}
