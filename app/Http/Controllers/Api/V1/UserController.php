<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserIndexRequest;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\Users\UserCollection;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends BaseController
{
    private UserServiceInterface $userService;

    /**
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(UserIndexRequest $request): JsonResponse
    {
        return response()->json(
            $this->userService
                ->getAllUsers($request->validated())
        );
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(UserRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->userService->createUser($request->validated())
        );
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->userService->getUserById($id)
        );
    }

    public function showWithOrders(int $id): JsonResponse
    {
        $user = $this->userService->getUserWithOrdersById($id);
        return $this->apiSuccessSingleResponse($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());
        return $this->apiSuccessSingleResponse($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->userService->deleteById($id);
        return $this->apiSuccessSingleResponse();
    }
}
