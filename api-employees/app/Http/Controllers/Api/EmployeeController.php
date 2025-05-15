<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Mail\EmployeeImportSuccess;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{

    /**
     * Lista todos os colaboradores vinculados ao gestor autenticado
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $manager = auth('manager')->user();
        $employees = $manager->employees;

        return response()->json([
            'status' => true,
            'message' => $employees
        ], 200);
    }

    /**
     * Cadastra um novo colaborador e vincula ao gestor autenticado
     *
     * @param EmployeeRequest $request
     * @return JsonResponse
     */
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

    /**
     * Retorna os dados do colaborador informado na URL
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function show(Employee $employee): JsonResponse
    {
        if ($employee->manager_id !== auth('manager')->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado.'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => $employee
        ], 200);
    }

    /**
     * Atualiza os dados do colaborador
     *
     * @param EmployeeRequest $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function update(EmployeeRequest $request, Employee $employee): JsonResponse
    {
        if ($employee->manager_id !== auth('manager')->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado.'
            ], 403);
        }

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

    /**
     * Deleta um colaborador do banco de dados
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function destroy(Employee $employee): JsonResponse
    {
        if ($employee->manager_id !== auth('manager')->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Acesso não autorizado.'
            ], 403);
        }

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

    /**
     * Importa colaboradores em massa a partir de um arquivo CSV e retorna a quantidade cadastrada
     * O arquivo CSV deve conter os cabeçalhos: name, email, cpf, city, state
     *
     * @param Request $request
     * @return JsonResponse
     */
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
                        $cpfAlreadyExist[] = $row[$key];
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

        $managerName = auth('manager')->user();
        $totalEmployeesImported = (int) count($arrayValues);
        $fileName = $request->file('file')->getClientOriginalName();

        Mail::to($managerName->email)->send(new EmployeeImportSuccess(
            $managerName->name,
            $fileName,
            $totalEmployeesImported
        ));

        return response()->json([
            'status' => true,
            'message' => 'Arquivo importado com sucesso.',
            '$totalEmployeesImported' => $totalEmployeesImported
        ], 201);
    }
}
