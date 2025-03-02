<?php

namespace App\Services;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    /**
     * @var AuthRepositoryInterface
     */
    private $authRepository;

    /**
     * @var DebugService
     */
    private $debugService;

    /**
     * AuthService 構造函數
     *
     * @param AuthRepositoryInterface $authRepository
     * @param DebugService $debugService
     */
    public function __construct(AuthRepositoryInterface $authRepository, DebugService $debugService)
    {
        $this->authRepository = $authRepository;
        $this->debugService = $debugService;
    }

    /**
     * {@inheritdoc}
     */
    public function register(array $data)
    {
        try {
            $user = $this->authRepository->register($data);

            $this->debugService->log('用戶註冊成功', ['email' => $data['email']]);

            return $user;
        } catch (\Exception $e) {
            $this->debugService->logError($e, '用戶註冊失敗', [
                'email' => $data['email']
            ]);
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function login(array $data): bool
    {
        try {
            $result = $this->authRepository->login($data);

            if (! $result) {
                $this->debugService->log('用戶登入失敗', ['email' => $data['email']], 'warning');
            } else {
                $this->debugService->log('用戶登入成功', ['email' => $data['email']]);
            }

            return $result;
        } catch (\Exception $e) {
            $this->debugService->logError($e, '用戶登入發生錯誤', [
                'email' => $data['email']
            ]);
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function logout(): void
    {
        try {
            $userId = Auth::id();
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            $this->debugService->log('用戶登出成功', ['user_id' => $userId]);
        } catch (\Exception $e) {
            $this->debugService->logError($e, '用戶登出發生錯誤', [
                'user_id' => Auth::id()
            ]);
            throw $e;
        }
    }
}
