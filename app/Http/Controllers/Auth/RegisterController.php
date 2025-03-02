<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Services\Interfaces\AuthServiceInterface;
use Exception;

class RegisterController extends Controller
{
    use RespondsWithJson, LogsExceptions;

    /**
     * @var AuthServiceInterface
     */
    private $authService;

    /**
     * RegisterController 構造函數
     *
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 顯示註冊頁面
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 處理用戶註冊
     *
     * @param StoreRegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(StoreRegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $this->authService->register($data);

            return $this->respondWithSuccess(
                $request,
                '註冊成功，歡迎加入！',
                [],
                'posts.index'
            );
        } catch (Exception $e) {
            $this->logException($e, '用戶註冊失敗', [
                'email' => $request->email
            ]);

            return $this->respondWithError(
                $request,
                '註冊失敗: ' . $e->getMessage(),
                [],
                500,
                'auth.register'
            );
        }
    }
} 