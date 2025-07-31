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
            throw new AuthorizationException(__('exception.unauthorized'));
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
        Gate::authorize('viewAny', User::class);
        return $this->userRepository->getFilterOptions();
    }

    /**
     * @throws AuthorizationException|BadRequestException
     */
    function getUserById(int $id): UserResource
    {
        // TODO: Implement getUserById() method.
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new BadRequestException(__('exception.not_found', ['name' => "user"]), 404);
        }
        Gate::authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * @throws Exception
     */
    function getUserWithOrdersById(int $id): UserResource
    {
        // TODO: Implement getUserById() method.

        $user = $this->userRepository->findByIdWithOrders($id);
        Gate::authorize('view', $user);
        if (!$user) {
            throw new BadRequestException(__('exception.not_found', ['name' => "user"]), 404);
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
        Gate::authorize('create', User::class);

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
            throw new BadRequestException(__('exception.not_found', ['name' => "user"]), 404);
        }
        Gate::authorize('update', $user);

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
        Gate::authorize('delete', $user);

        if (!$user) {
            throw new BadRequestException(__('exception.not_found', ['name' => "user"]), 404);
        }
        $this->userRepository->delete($id);
    }
}
