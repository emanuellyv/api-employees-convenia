<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use Illuminate\Support\Facades\Route;

// GET - http://127.0.0.1:8000/api/employees
Route::get('/employees', [EmployeeController::class, 'index']);
// GET - http://127.0.0.1:8000/api/employees/{id}
Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
// POST - http://127.0.0.1:8000/api/employees/
Route::post('/employees', [EmployeeController::class, 'store']);
// PUT - http://127.0.0.1:8000/api/employee/{id}
Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
// DELETE - http://127.0.0.1:8000/api/employees/{id}
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

// GET - http://127.0.0.1:8000/api/managers
Route::get('/managers', [ManagerController::class, 'index']);
// GET - http://127.0.0.1:8000/api/managers/{id}
Route::get('/managers/{manager}', [ManagerController::class, 'show']);
// POST - http://127.0.0.1:8000/api/managers/
Route::post('/managers', [ManagerController::class, 'store']);
// PUT - http://127.0.0.1:8000/api/managers/{id}
Route::put('/managers/{manager}', [ManagerController::class, 'update']);
// DELETE - http://127.0.0.1:8000/api/managers/{id}
Route::delete('/managers/{manager}', [ManagerController::class, 'destroy']);
