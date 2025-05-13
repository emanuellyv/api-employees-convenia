<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        $employees = Employee::orderBy('name', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => $employees
        ], 200);
    }

    public function store(EmployeeRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'city' => $request->city,
                'state' => $request->state,
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'employee' => $employee,
                'message' => 'Colaborador cadastrado com sucesso.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar o colaborador.'
            ], 400);
        }
    }

    public function show(Employee $employee): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $employee
        ], 200);
    }

    public function update(EmployeeRequest $request, Employee $employee): JsonResponse
    {
        DB::beginTransaction();

        try {
            $employee->update([
                'name' => $request->name,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'city' => $request->city,
                'state' => $request->state,
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'employee' => $employee,
                'message' => 'Colaborador editado com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao editar o colaborador.'
            ], 400);
        }
    }

    public function destroy(Employee $employee): JsonResponse
    {
        DB::beginTransaction();

        try {
            $employee->delete();
            DB::commit();

            return response()->json([
                'status' => true,
                'employee' => $employee,
                'message' => 'Colaborador excluído com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao excluir o colaborador.'
            ], 400);
        }
    }
}
