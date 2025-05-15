<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function index(): JsonResponse
    {
        $manager = auth('manager')->user();
        $employees = $manager->employees;

        return response()->json([
            'status' => true,
            'message' => $employees
        ], 200);
    }

    public function store(EmployeeRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $manager = auth('manager')->user();

            $employee = $manager->employees()->create([
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

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ], [
            'file.required' => 'É obrigatório selecionar um arquivo.',
            'file.mimes' => 'O arquivo deve ser um CSV.',
        ]);

        $headers = ['name', 'email', 'cpf', 'city', 'state'];

        $dataFile = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        $headersRow = array_shift($dataFile);

        $cpfAlreadyExist = [];
        $arrayValues = [];

        foreach ($dataFile as $keyData => $row) {
            foreach ($headers as $key => $header) {

                if ($header === 'cpf' ) {
                    if (Employee::where('cpf', $row[$key])->first()) {
                        $cpfAlreadyExist[] .= $row[$key];
                    }
                }

                $arrayValues[$keyData][$header] = $row[$key];
            }
            $arrayValues[$keyData]['manager_id'] = auth('manager')->id();
        }

        if (!empty($cpfAlreadyExist)) {
            return response()->json([
                'status' => false,
                'message' => 'Existem CPFs já cadastrados.',
                'cpf_already_exist' => $cpfAlreadyExist,
            ], 409);
        }

        Employee::insert($arrayValues);

        return response()->json([
            'status' => true,
            'message' => 'Arquivo importado com sucesso.',
            'total_rows' => count($arrayValues)
        ], 201);
    }
}
