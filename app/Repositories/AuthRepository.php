<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        if(User::where('email', $data['email'])->exists()) {
            throw new \Exception('Email already exists');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return [
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if($user && Hash::check($data['password'], $user['password'])) {

            $token = $user->createToken('Personal Access Token')->accessToken;

            return [
                'user' => new UserResource($user),
                'token' => $token,
            ];
        } else {
            throw new \Exception('Invalid email or password');
        }
    }

    public function logout()
    {
        $user = auth()->user();

        if(!$user) {
            throw new \Exception('User not logged in');
        }

        $accessToken = $user->token();
        
        if($accessToken) {
            $accessToken->revoke();
        }

        // $user->token()->delete(); //delete token login aja
        // $user->tokens()->delete(); //delete token register sama login
        // $user->token()->revoke(); //revoke token login

        return true;
    }

    public function userInfo()
    {
        $user = auth()->user();

        if(!$user) {
            throw new \Exception('User not logged in');
        }

        return $user;
    }
}