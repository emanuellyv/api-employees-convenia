<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\ImportEmployeesRequest;
use App\Jobs\ImportCSVJob;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @method authorize(string $string, string $class)
 */
class EmployeeController extends Controller
{
    /**
     * Lista todos os colaboradores vinculados ao gestor autenticado
     *
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Employee::class);
        $manager = auth('manager')->user();
        $employees = $manager->employees;

        return response()->json([
            'status'  => true,
            'message' => $employees,
        ], 200);
    }

    /**
     * Cadastra um novo colaborador e vincula ao gestor autenticado
     *
     */
    public function store(EmployeeRequest $request): JsonResponse
    {
        $this->authorize('create', Employee::class);

        DB::beginTransaction();

        try {
            $manager = auth('manager')->user();

            $employee = $manager->employees()->create($request->validated());
            DB::commit();

            return response()->json([
                'status'   => true,
                'employee' => $employee,
                'message'  => 'Colaborador cadastrado com sucesso.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Erro ao cadastrar o colaborador.',
            ], 400);
        }
    }

    /**
     * Retorna os dados do colaborador informado na URL
     *
     */
    public function show(Employee $employee): JsonResponse
    {
        $this->authorize('view', $employee);

        return response()->json([
            'status'  => true,
            'message' => $employee,
        ], 200);
    }

    /**
     * Atualiza os dados do colaborador
     *
     */
    public function update(EmployeeRequest $request, Employee $employee): JsonResponse
    {
        $this->authorize('update', $employee);

        DB::beginTransaction();

        try {
            $employee->update($request->validated());
            DB::commit();

            return response()->json([
                'status'   => true,
                'employee' => $employee,
                'message'  => 'Colaborador editado com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Erro ao editar o colaborador.',
            ], 400);
        }
    }

    /**
     * Deleta um colaborador do banco de dados
     *
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->authorize('delete', $employee);

        DB::beginTransaction();

        try {
            $employee->delete();
            DB::commit();

            return response()->json([
                'status'   => true,
                'employee' => $employee,
                'message'  => 'Colaborador excluído com sucesso.',
            ], 200);
        } catch (\Exception) {
            return response()->json([
                'status'  => false,
                'message' => 'Erro ao excluir o colaborador.',
            ], 400);
        }
    }

    /**
     * Importa colaboradores em massa a partir de um arquivo CSV e retorna a quantidade cadastrada
     * O arquivo CSV deve conter os cabeçalhos: name, email, cpf, city, state
     *
     */
    public function import(ImportEmployeesRequest $request): JsonResponse
    {
        $this->authorize('import', Employee::class);

        $fileName = 'import-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $path = $request->file('file')->storeAs('imports', $fileName);

        $manager = Auth::guard('manager')->user();

        ImportCSVJob::dispatch($path, $manager->id, $manager->name, $manager->email);

        return response()->json([
            'status'  => true,
            'message' => 'Importação iniciada com sucesso.',
        ], 201);
    }
}
