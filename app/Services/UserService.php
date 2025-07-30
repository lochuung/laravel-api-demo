<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

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


    /**
     * @throws AuthorizationException
     */
    function getAllUsers(array $filters = []): UserCollection
    {
        // TODO: Implement getAllUsers() method.
        if (Gate::denies('viewAny', User::class)) {
            throw new AuthorizationException('You do not have permission to view users.');
        }
        $perPage = $filters['per_page'] ?? 10;
        $users = $this->userRepository->searchAndFilter($filters, $perPage);

        return new UserCollection($users);
    }

    /**
     * @throws AuthorizationException
     */
    public function getFilterOptions(): array
    {
        if (Gate::denies('viewAny', User::class)) {
            throw new AuthorizationException('You do not have permission to view user filter options.');
        }
        return $this->userRepository->getFilterOptions();
    }

    /**
     * @throws AuthorizationException|BadRequestException
     */
    function getUserById(int $id): UserResource
    {
        // TODO: Implement getUserById() method.
        $user = $this->userRepository->find($id);
        if (Gate::denies('view', $user)) {
            throw new AuthorizationException('You do not have permission to view this user.');
        }
        if (!$user) {
            throw new BadRequestException("User not found", 404);
        }

        return new UserResource($user);
    }

    /**
     * @throws Exception
     */
    function getUserWithOrdersById(int $id): UserResource
    {
        // TODO: Implement getUserById() method.

        $user = $this->userRepository->findByIdWithOrders($id);
        if (Gate::denies('view', $user)) {
            throw new AuthorizationException('You do not have permission to view this user.');
        }
        if (!$user) {
            throw new BadRequestException("User not found", 404);
        }
        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     * @throws Exception
     */
    public function createUser(array $data): UserResource
    {
        // TODO: Implement createUser() method.
        if (Gate::denies('create', User::class)) {
            throw new AuthorizationException('You do not have permission to create a user.');
        }

        $user = $this->userRepository->create($data);
        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     * @throws BadRequestException
     */
    public function updateUser(int $id, array $data): UserResource
    {
        // TODO: Implement updateUser() method.
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new BadRequestException("User not found", 404);
        }
        if (Gate::denies('update', $user)) {
            throw new AuthorizationException('You do not have permission to update this user.');
        }

        $user = $this->userRepository->update($id, $data);

        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     * @throws Exception
     */
    public function deleteById(int $id): void
    {
        // TODO: Implement deleteById() method.
        $user = $this->userRepository->find($id);
        if (!Gate::authorize('delete', $user)) {
            throw new AuthorizationException("You do not have permission to delete this user", 403);
        }

        if (!$user) {
            throw new BadRequestException("User not found", 404);
        }
        $this->userRepository->delete($id);
    }
}
