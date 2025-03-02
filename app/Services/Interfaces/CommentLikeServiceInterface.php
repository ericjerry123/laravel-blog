<?php

namespace App\Services\Interfaces;

use App\Models\Comment;

interface CommentLikeServiceInterface
{
    /**
     * 切換評論的點贊狀態
     *
     * @param int $commentId 評論ID
     * @param int $userId 用戶ID
     * @return array 包含操作結果的數組
     */
    public function toggleLike(int $commentId, int $userId): array;

    /**
     * 檢查用戶是否已點贊評論
     *
     * @param int $commentId 評論ID
     * @param int $userId 用戶ID
     * @return bool
     */
    public function isLikedByUser(int $commentId, int $userId): bool;

    /**
     * 獲取評論的點贊數
     *
     * @param int $commentId 評論ID
     * @return array 包含點贊數的數組
     */
    public function getLikesCount(int $commentId): array;
}
