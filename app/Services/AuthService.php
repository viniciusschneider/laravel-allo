<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Traits\ErrorsExceptionsTrait;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    use ErrorsExceptionsTrait;

    protected $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function register($request)
    {
        $user = $this->userRepository->getUserByEmail($request->email);

        if ($user) {
            $this->badRequestException('Duplicate email', [
                'status' => 'DUPLICATE_EMAIL',
            ]);
        }

        $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function login($credentials)
    {
        if (!$token = JWTAuth::attempt($credentials))
            $this->unauthorizedRequestException();

        return $this->generateToken($token);
    }

    public function me()
    {
        return JWTAuth::user();
    }

    public function logout()
    {
        JWTAuth::invalidate(true);
    }

    public function refresh()
    {
        return $this->generateToken(JWTAuth::refresh());
    }

    private function generateToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }
}