<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;

interface CommentLikeRepositoryInterface
{
    /**
     * 檢查用戶是否已點贊評論
     *
     * @param int $commentId
     * @param int $userId
     * @return bool
     */
    public function isLikedByUser(int $commentId, int $userId): bool;

    /**
     * 切換用戶點贊狀態
     *
     * @param int $commentId
     * @param int $userId
     * @return Comment
     */
    public function toggleLike(int $commentId, int $userId): Comment;

    /**
     * 添加用戶點贊
     *
     * @param int $commentId
     * @param int $userId
     * @return Comment
     */
    public function addLike(int $commentId, int $userId): Comment;

    /**
     * 移除用戶點贊
     *
     * @param int $commentId
     * @param int $userId
     * @return Comment
     */
    public function removeLike(int $commentId, int $userId): Comment;

    /**
     * 獲取評論的點贊數
     *
     * @param int $commentId
     * @return int
     */
    public function getLikesCount(int $commentId): int;
} 