<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Support\Facades\Route;

// Rota pública
Route::post('/manager/login', [AuthController::class, 'login']);

// Rotas protegidas com acesso autenticado do gestor
Route::middleware('auth:manager')->group(function () {
    // Gestor (Manager)
    Route::apiResource('managers', ManagerController::class);

    // Autenticação (Auth)
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Colaboradores (Employees)
    Route::apiResource('employees', EmployeeController::class);
    Route::post('/employees/import', [EmployeeController::class, 'import']);
});
