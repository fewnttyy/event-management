<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * @unauthenticated
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());
            return new MessageResource('User registered successfully', $user);

        } catch (\Exception  $e) {
            return new MessageResource('User registration failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @unauthenticated
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request->validated());
            return new MessageResource('User login successfully', $user);

        } catch (\Exception $e) {
            return new MessageResource('User login failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        try {
            $logout = $this->authService->logout();
            return new MessageResource('Logout successfully', null);

        } catch (\Exception $e) {
            return new MessageResource('Logout failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function userInfo()
    {
        try {
            $userInfo = $this->authService->userInfo();
            return new UserResource($userInfo);
            
        } catch (\Exception $e) {
            return new MessageResource('failed to retrieve user info', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
