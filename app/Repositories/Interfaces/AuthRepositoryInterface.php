<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * 註冊新用戶
     *
     * @param array $data 用戶註冊資料
     * @return User
     */
    public function register(array $data): User;

    /**
     * 用戶登入
     *
     * @param array $data 登入資料
     * @return bool
     */
    public function login(array $data): bool;
} 