<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Services\Interfaces\AuthServiceInterface;
use Exception;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use RespondsWithJson, LogsExceptions;

    /**
     * @var AuthServiceInterface
     */
    private $authService;

    /**
     * LoginController 構造函數
     *
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 顯示登入頁面
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 處理用戶登入
     *
     * @param StoreLoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(StoreLoginRequest $request)
    {
        try {
            $data = $request->validated();

            $result = $this->authService->login($data);

            if ($result) {
                return $this->respondWithSuccess(
                    $request,
                    '登入成功，歡迎回來！',
                    [],
                    'posts.index'
                );
            }

            return $this->respondWithError(
                $request,
                '登入失敗，請檢查您的帳號和密碼',
                [],
                401,
                'auth.login'
            );
        } catch (Exception $e) {
            $this->logException($e, '用戶登入失敗', [
                'email' => $request->email
            ]);

            return $this->respondWithError(
                $request,
                '登入失敗: ' . $e->getMessage(),
                [],
                500,
                'auth.login'
            );
        }
    }

    /**
     * 處理用戶登出
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout();

            return $this->respondWithSuccess(
                $request,
                '您已成功登出',
                [],
                'posts.index'
            );
        } catch (Exception $e) {
            $this->logException($e, '用戶登出失敗');

            return $this->respondWithError(
                $request,
                '登出失敗: ' . $e->getMessage(),
                [],
                500,
                'posts.index'
            );
        }
    }
}
