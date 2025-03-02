<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * 獲取所有分類
     *
     * @return Collection
     */
    public function getAllCategories(): Collection;

    /**
     * 獲取熱門分類
     *
     * @param int $limit
     * @return Collection
     */
    public function getPopularCategories(int $limit = 5): Collection;

    /**
     * 根據ID查找分類
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category;

    /**
     * 根據 slug 查找分類
     *
     * @param string $slug
     * @return Category|null
     */
    public function findBySlug(string $slug): ?Category;

    /**
     * 創建新分類
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * 更新分類
     *
     * @param int $id
     * @param array $data
     * @return Category
     */
    public function update(int $id, array $data): Category;

    /**
     * 刪除分類
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
} 