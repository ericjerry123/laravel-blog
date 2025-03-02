<?php

namespace App\Http\Traits;

use Exception;
use App\Services\DebugService;
use Illuminate\Support\Facades\App;

trait LogsExceptions
{
    /**
     * 記錄異常
     *
     * @param Exception $exception
     * @param string $message
     * @param array $context
     * @return void
     */
    protected function logException(Exception $exception, string $message, array $context = []): void
    {
        // 使用 DebugService 記錄異常
        $debugService = App::make(DebugService::class);
        $debugService->logError($exception, $message, $context);
    }
} 