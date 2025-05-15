<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Realiza o login de um gestor, retornando o token JWT de autenticação
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('manager')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciais inválidas',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Realiza o logout do gestor autenticado, invalidando o token JWT
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('manager')->logout();

        return response()->json([
            'status' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    /**
     * Gera um novo token JWT para o gestor autenticado
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }

    /**
     * Retorna um JSON com o token JWT, tipo do token, tempo de expiração e dados do gestor autenticado.
     *
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('manager')->factory()->getTTL() * 60,
            'manager' => auth('manager')->user(),
        ]);
    }
}
