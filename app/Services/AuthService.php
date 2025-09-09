<?php

namespace App\Services;

use App\Repositories\AuthRepositoryInterface;
use App\Http\Resources\UserResource;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {
    }      

    public function register(array $data)
    {
        $user = $this->authRepository->register($data);

        return $user;
    }

    public function login(array $data)
    {
        $user = $this->authRepository->login($data);

        return $user;
    }

    public function logout()
    {
        $this->authRepository->logout();
    }

    public function userInfo()
    {
        $userInfo = $this->authRepository->userInfo();

        return $userInfo;
    }
}