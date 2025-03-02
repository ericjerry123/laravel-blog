<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    /**
     * 獲取所有文章，可選搜索、標籤和分類
     *
     * @param string|null $search
     * @param string|null $tag
     * @param string|null $category
     * @param string|null $sortBy 排序方式：latest(最新)、popular(最熱門)、most_commented(最多評論)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPosts(?string $search = null, ?string $tag = null, ?string $category = null, ?string $sortBy = 'latest'): \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * 根據ID查找文章
     *
     * @param int $id
     * @return Post|null
     */
    public function findById(int $id): ?Post;

    /**
     * 創建新文章
     *
     * @param array $data
     * @return Post
     */
    public function create(array $data): Post;

    /**
     * 更新文章
     *
     * @param int $id
     * @param array $data
     * @return Post
     */
    public function update(int $id, array $data): Post;

    /**
     * 刪除文章
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
} 