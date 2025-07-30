<?php

namespace App\Services\Contracts;

use App\Http\Resources\Dashboard\DashboardResource;

interface DashboardServiceInterface
{
    public function getDashboardData(): DashboardResource;
}
