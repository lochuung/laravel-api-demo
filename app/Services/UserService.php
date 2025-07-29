<?php

namespace App\Services;

use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    function getAllUsers(array $filters = []): UserCollection
    {
        // TODO: Implement getAllUsers() method.
        $perPage = $filters['per_page'] ?? 10;
        $users = $this->userRepository->searchAndFilter($filters, $perPage);

        return new UserCollection($users);
    }

    public function getFilterOptions(): array
    {
        return $this->userRepository->getFilterOptions();
    }

    /**
     * @throws \Exception
     */
    function getUserById(int $id): UserResource
    {
        // TODO: Implement getUserById() method.
        $user = $this->userRepository->findByIdWithOrders($id);
        if (!$user) {
            throw new \Exception("User not found", 404);
        }
        return new UserResource($user);
    }
}
