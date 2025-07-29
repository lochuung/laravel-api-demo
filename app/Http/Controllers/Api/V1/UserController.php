<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserIndexRequest;
use App\Http\Resources\Users\UserCollection;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     */
    public function index(UserIndexRequest $request): JsonResponse
    {
        return response()->json(
            $this->userService
                ->getAllUsers($request->validated())
        );
    }

    /**
     * Get filter options for users
     */
    public function filterOptions(): JsonResponse
    {
        $options = $this->userService->getFilterOptions();

        return response()->json([
            'data' => $options,
            'message' => 'Filter options retrieved successfully.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
