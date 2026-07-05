<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Dashboard\DashboardService;

class DashboardController
{
    public function __invoke(
        DashboardService $dashboard
    ): JsonResponse {

        return response()->json(
            $dashboard->data()
        );

    }
}
