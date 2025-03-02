<?php

namespace App\Services\Interfaces;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagServiceInterface
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
    public function getPopularTags(int $limit = 3): Collection;

    /**
     * 根據ID獲取標籤
     *
     * @param int $id
     * @return Tag
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getTag(int $id): Tag;

    /**
     * 根據Slug獲取標籤
     *
     * @param string $slug
     * @return Tag
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getTagBySlug(string $slug): Tag;

    /**
     * 創建新標籤
     *
     * @param array $data
     * @return Tag
     */
    public function createTag(array $data): Tag;

    /**
     * 更新標籤
     *
     * @param int $id
     * @param array $data
     * @return Tag
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateTag(int $id, array $data): Tag;

    /**
     * 刪除標籤
     *
     * @param int $id
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteTag(int $id): bool;
} 