<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HistoryStatusController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(
            cache('history.status', [])
        );
    }
}
