<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Realiza o login de um gestor, retornando o token JWT de autenticação
     *
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('manager')->attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'message' => 'Credenciais inválidas',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Realiza o logout do gestor autenticado, invalidando o token JWT
     *
     */
    public function logout(): JsonResponse
    {
        auth('manager')->logout();

        return response()->json([
            'status'  => true,
            'message' => 'Logout realizado com sucesso',
        ]);
    }

    /**
     * Gera um novo token JWT para o gestor autenticado
     *
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }

    /**
     * Retorna um JSON com o token JWT, tipo do token, tempo de expiração e dados do gestor autenticado.
     *
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('manager')->factory()->getTTL() * 60,
            'manager'      => auth('manager')->user(),
        ]);
    }
}
