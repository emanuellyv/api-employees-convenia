<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Support\Facades\Route;

//Route::get('/employees', [EmployeeController::class, 'index']);
//Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
//Route::post('/employees', [EmployeeController::class, 'store']);
//Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
//Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

Route::middleware('auth:manager')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
});

// Public routes
Route::post('/manager/login', [AuthController::class, 'login']);

// Protected routes -> auth:manager
Route::middleware('auth:manager')->group(function () {
    Route::apiResource('managers', ManagerController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});
