<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:manager')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::post('/employees/import', [EmployeeController::class, 'import']);
});

// Public routes
Route::post('/manager/login', [AuthController::class, 'login']);

// Protected routes -> auth:manager
Route::middleware('auth:manager')->group(function () {
    Route::apiResource('managers', ManagerController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});
