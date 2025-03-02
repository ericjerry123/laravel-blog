<?php

namespace App\Services\Interfaces;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    /**
     * 獲取所有文章，可選搜索、標籤和分類
     *
     * @param string|null $search
     * @param string|null $tag
     * @param string|null $category
     * @param string|null $sortBy 排序方式：latest(最新)、popular(最熱門)、most_commented(最多評論)
     * @return LengthAwarePaginator
     */
    public function getAllPosts(?string $search = null, ?string $tag = null, ?string $category = null, ?string $sortBy = 'latest'): LengthAwarePaginator;

    /**
     * 根據ID獲取文章
     *
     * @param int $id
     * @return Post
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getPost(int $id): Post;

    /**
     * 創建新文章
     *
     * @param array $data
     * @param array|null $tags
     * @return Post
     */
    public function createPost(array $data, ?array $tags = null): Post;

    /**
     * 更新文章
     *
     * @param int $id
     * @param array $data
     * @param array|null $tags
     * @return Post
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updatePost(int $id, array $data, ?array $tags = null): Post;

    /**
     * 刪除文章
     *
     * @param int $id
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deletePost(int $id): bool;
} 