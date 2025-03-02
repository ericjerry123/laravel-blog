<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    /**
     * 註冊新用戶
     *
     * @param array $data 用戶註冊資料
     * @return \App\Models\User
     */
    public function register(array $data);

    /**
     * 用戶登入
     *
     * @param array $data 登入資料
     * @return bool
     */
    public function login(array $data): bool;

    /**
     * 用戶登出
     *
     * @return void
     */
    public function logout(): void;
} 