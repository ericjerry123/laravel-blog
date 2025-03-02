<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    /**
     * 根据ID查找评论
     *
     * @param int $id
     * @return Comment|null
     */
    public function findById(int $id): ?Comment;

    /**
     * 创建新评论
     *
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment;

    /**
     * 更新评论
     *
     * @param int $id
     * @param array $data
     * @return Comment
     */
    public function update(int $id, array $data): Comment;

    /**
     * 删除评论
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * 獲取文章的所有評論
     *
     * @param int $postId
     * @return Collection
     */
    public function getPostComments(int $postId): Collection;
} 