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

    public function logout(): JsonResponse
    {
        auth('manager')->logout();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('manager')->refresh());
    }

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
