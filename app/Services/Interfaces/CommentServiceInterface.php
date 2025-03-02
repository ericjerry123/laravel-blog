<?php

namespace App\Services\Interfaces;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentServiceInterface
{
    /**
     * 創建新評論
     *
     * @param array $data 評論數據
     * @param int $postId 文章ID
     * @param int $userId 用戶ID
     * @return Comment
     */
    public function createComment(array $data, int $postId, int $userId): Comment;

    /**
     * 更新評論
     *
     * @param int $commentId 評論ID
     * @param array $data 更新數據
     * @param int $userId 當前用戶ID
     * @return Comment
     * @throws \Exception 如果用戶無權限或評論不存在
     */
    public function updateComment(int $commentId, array $data, int $userId): Comment;

    /**
     * 刪除評論
     *
     * @param int $commentId 評論ID
     * @param int $userId 當前用戶ID
     * @return bool
     * @throws \Exception 如果用戶無權限或評論不存在
     */
    public function deleteComment(int $commentId, int $userId): bool;

    /**
     * 獲取文章的所有評論
     *
     * @param int $postId 文章ID
     * @return Collection
     */
    public function getPostComments(int $postId): Collection;
} 