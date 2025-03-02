<?php

namespace App\Exceptions;

use Exception;

class CommentException extends Exception
{
    /**
     * 評論不存在異常
     *
     * @param int $commentId
     * @return static
     */
    public static function notFound(int $commentId): self
    {
        return new static("評論 #{$commentId} 不存在");
    }

    /**
     * 無權限操作異常
     *
     * @param int $commentId
     * @return static
     */
    public static function unauthorized(int $commentId): self
    {
        return new static("您沒有權限操作評論 #{$commentId}");
    }

    /**
     * 創建評論失敗異常
     *
     * @param string $reason
     * @return static
     */
    public static function createFailed(string $reason): self
    {
        return new static("創建評論失敗: {$reason}");
    }

    /**
     * 更新評論失敗異常
     *
     * @param int $commentId
     * @param string $reason
     * @return static
     */
    public static function updateFailed(int $commentId, string $reason): self
    {
        return new static("更新評論 #{$commentId} 失敗: {$reason}");
    }

    /**
     * 刪除評論失敗異常
     *
     * @param int $commentId
     * @param string $reason
     * @return static
     */
    public static function deleteFailed(int $commentId, string $reason): self
    {
        return new static("刪除評論 #{$commentId} 失敗: {$reason}");
    }

    /**
     * 點贊評論失敗異常
     *
     * @param int $commentId
     * @param string $reason
     * @return static
     */
    public static function likeFailed(int $commentId, string $reason): self
    {
        return new static("點贊評論 #{$commentId} 失敗: {$reason}");
    }
} 