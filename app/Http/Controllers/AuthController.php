<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ErrorsExceptionsTrait;

class AuthController extends Controller
{
    use ErrorsExceptionsTrait;

    protected $authService;

    public function __construct(
        AuthService $authService
    )
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request);
        return response()->json([], 201);
    }

    public function login(LoginRequest $request)
    {
        return response()->json(
            $this->authService->login($request->only(['email', 'password']))
        );
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }

    public function logout()
    {
        $this->authService->logout();
    }

    public function refresh()
    {
        return response()->json($this->authService->refresh());
    }
}
