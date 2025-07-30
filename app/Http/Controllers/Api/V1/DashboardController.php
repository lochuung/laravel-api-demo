<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Services\Contracts\DashboardServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends BaseController
{
    private DashboardServiceInterface $dashboardService;

    /**
     * @param DashboardServiceInterface $dashboardService
     */
    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }


    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        if (Gate::denies('is-moderator')) {
            throw new AuthorizationException('You do not have permission to access this resource.');
        }
        $data = $this->dashboardService->getDashboardData();
        return $this->apiSuccessSingleResponse($data);
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
