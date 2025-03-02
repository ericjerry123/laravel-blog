<?php

namespace App\Http\Traits;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RespondsWithJson
{
    /**
     * 處理成功回應
     *
     * @param Request $request
     * @param string $message
     * @param array $data
     * @param string $redirectRoute
     * @param string $flashKey
     * @return JsonResponse|RedirectResponse
     */
    protected function respondWithSuccess(
        Request $request,
        string $message,
        array $data = [],
        string $redirectRoute = 'back',
        string $flashKey = 'success'
    ) {
        if ($request->ajax()) {
            return ApiResponse::success($message, $data);
        }

        if ($redirectRoute === 'back') {
            return redirect()->back()->with($flashKey, $message);
        }

        return redirect()->route($redirectRoute)->with($flashKey, $message);
    }

    /**
     * 處理錯誤回應
     *
     * @param Request $request
     * @param string $message
     * @param array $errors
     * @param int $statusCode
     * @param string $redirectRoute
     * @return JsonResponse|RedirectResponse
     */
    protected function respondWithError(
        Request $request,
        string $message,
        array $errors = [],
        int $statusCode = 500,
        string $redirectRoute = 'back'
    ) {
        if ($request->ajax()) {
            return ApiResponse::serverError($message, $errors);
        }

        if ($redirectRoute === 'back') {
            return redirect()->back()->with('error', $message);
        }

        return redirect()->route($redirectRoute)->with('error', $message);
    }
} 