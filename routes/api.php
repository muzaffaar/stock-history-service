<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HistoryStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/history/status', HistoryStatusController::class);

Route::get('/', DashboardController::class);
