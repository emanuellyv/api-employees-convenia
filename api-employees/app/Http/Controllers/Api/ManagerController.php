<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ManagerRequest;
use App\Models\Manager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ManagerController extends Controller
{
    /**
     * Lista todos os gestores cadastrados no sistema.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $managers = Manager::orderBy('name', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => $managers
        ], 200);
    }

    /**
     * Cadastra um novo gestor no sistema
     *
     * @param ManagerRequest $request
     * @return JsonResponse
     */
    public function store(ManagerRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $manager = Manager::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'manager' => $manager,
                'message' => 'Gestor cadastrado com sucesso.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar o gestor.'
            ], 400);
        }
    }

    /**
     * Exibe os dados de um gestor específico
     *
     * @param Manager $manager
     * @return JsonResponse
     */
    public function show(Manager $manager): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $manager
        ], 200);
    }

    /**
     * Atualiza os dados de um gestor
     *
     * @param ManagerRequest $request
     * @param Manager $manager
     * @return JsonResponse
     */
    public function update(ManagerRequest $request, Manager $manager): JsonResponse
    {
        DB::beginTransaction();

        try {
            $manager->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'manager' => $manager,
                'message' => 'Gestor editado com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao editar o gestor.'
            ], 400);
        }
    }

    /**
     * Remove um gestor do sistema
     *
     * @param Manager $manager
     * @return JsonResponse
     */
    public function destroy(Manager $manager): JsonResponse
    {
        DB::beginTransaction();
        try {
            $manager->delete();
            DB::commit();

            return response()->json([
                'status' => true,
                'manager' => $manager,
                'message' => 'Gestor excluído com sucesso.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao excluir o gestor.'
            ], 400);
        }
    }
}
