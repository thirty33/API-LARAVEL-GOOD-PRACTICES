<?php

namespace App\Contracts\API\Auth;

use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function login(array $credentials): JsonResponse;
    public function register(array $data): JsonResponse;
    public function logout(): JsonResponse;
}
