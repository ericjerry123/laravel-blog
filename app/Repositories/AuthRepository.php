<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function login(array $data): bool
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $remember = $data['remember'] ?? false;

        return Auth::attempt($credentials, $remember);
    }
}
