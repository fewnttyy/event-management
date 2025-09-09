<?php

namespace App\Repositories;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function register(array $data);

    public function login(array $data);

    public function logout();

    public function userInfo();
}
