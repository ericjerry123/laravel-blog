<?php

namespace App\Repositories\Interfaces;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    /**
     * 獲取所有標籤
     *
     * @return Collection
     */
    public function getAllTags(): Collection;

    /**
     * 獲取熱門標籤
     *
     * @param int $limit
     * @return Collection
     */
    public function getPopularTags(int $limit = 10): Collection;

    /**
     * 根據ID查找標籤
     *
     * @param int $id
     * @return Tag|null
     */
    public function findById(int $id): ?Tag;

    /**
     * 根據名稱查找標籤
     *
     * @param string $name
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag;

    /**
     * 根據Slug查找標籤
     *
     * @param string $slug
     * @return Tag|null
     */
    public function findBySlug(string $slug): ?Tag;

    /**
     * 創建新標籤
     *
     * @param array $data
     * @return Tag
     */
    public function create(array $data): Tag;

    /**
     * 更新標籤
     *
     * @param int $id
     * @param array $data
     * @return Tag
     */
    public function update(int $id, array $data): Tag;

    /**
     * 刪除標籤
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
} 