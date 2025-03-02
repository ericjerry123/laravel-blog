<?php

namespace App\Http\Responses;

class ApiResponse
{
    /**
     * 成功回應
     *
     * @param string $message 成功訊息
     * @param array $data 回應資料
     * @param int $statusCode HTTP 狀態碼
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success(string $message = '操作成功', array $data = [], int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * 失敗回應
     *
     * @param string $message 錯誤訊息
     * @param array $errors 錯誤詳情
     * @param int $statusCode HTTP 狀態碼
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(string $message = '操作失敗', array $errors = [], int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * 未授權回應
     *
     * @param string $message 錯誤訊息
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unauthorized(string $message = '請先登入後再操作')
    {
        return self::error($message, [], 401);
    }

    /**
     * 禁止訪問回應
     *
     * @param string $message 錯誤訊息
     * @return \Illuminate\Http\JsonResponse
     */
    public static function forbidden(string $message = '您沒有權限執行此操作')
    {
        return self::error($message, [], 403);
    }

    /**
     * 資源不存在回應
     *
     * @param string $message 錯誤訊息
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notFound(string $message = '資源不存在')
    {
        return self::error($message, [], 404);
    }

    /**
     * 伺服器錯誤回應
     *
     * @param string $message 錯誤訊息
     * @param array $errors 錯誤詳情
     * @return \Illuminate\Http\JsonResponse
     */
    public static function serverError(string $message = '伺服器錯誤，請稍後再試', array $errors = [])
    {
        return self::error($message, $errors, 500);
    }
} 