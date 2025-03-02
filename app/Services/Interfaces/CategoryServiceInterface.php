<?php

namespace App\Services\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
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
     * 根據ID獲取分類
     *
     * @param int $id
     * @return Category
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getCategory(int $id): Category;

    /**
     * 根據 slug 獲取分類
     *
     * @param string $slug
     * @return Category
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getCategoryBySlug(string $slug): Category;
} 