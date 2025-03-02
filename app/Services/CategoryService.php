<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * CategoryService 構造函數
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAllCategories();
    }

    /**
     * {@inheritdoc}
     */
    public function getPopularCategories(int $limit = 5): Collection
    {
        return $this->categoryRepository->getPopularCategories($limit);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory(int $id): Category
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            throw new ModelNotFoundException('找不到分類');
        }

        return $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryBySlug(string $slug): Category
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (!$category) {
            throw new ModelNotFoundException('找不到分類');
        }

        return $category;
    }
} 