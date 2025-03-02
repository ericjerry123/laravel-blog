<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\TagServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagService implements TagServiceInterface
{
    /**
     * @var TagRepositoryInterface
     */
    private $tagRepository;

    /**
     * TagService 構造函數
     *
     * @param TagRepositoryInterface $tagRepository
     */
    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllTags(): Collection
    {
        return $this->tagRepository->getAllTags();
    }

    /**
     * {@inheritdoc}
     */
    public function getPopularTags(int $limit = 3): Collection
    {
        return $this->tagRepository->getPopularTags($limit);
    }

    /**
     * {@inheritdoc}
     */
    public function getTag(int $id): Tag
    {
        $tag = $this->tagRepository->findById($id);

        if (!$tag) {
            throw new ModelNotFoundException('找不到標籤');
        }

        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagBySlug(string $slug): Tag
    {
        $tag = $this->tagRepository->findBySlug($slug);

        if (!$tag) {
            throw new ModelNotFoundException('找不到標籤');
        }

        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function createTag(array $data): Tag
    {
        // 在這裡可以添加業務邏輯，例如：
        // - 數據驗證
        // - 權限檢查
        // - 觸發事件等
        
        return $this->tagRepository->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateTag(int $id, array $data): Tag
    {
        // 確保標籤存在
        $tag = $this->getTag($id);
        
        // 在這裡可以添加更新相關的業務邏輯
        
        return $this->tagRepository->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTag(int $id): bool
    {
        // 確保標籤存在
        $tag = $this->getTag($id);
        
        // 在這裡可以添加刪除相關的業務邏輯
        
        return $this->tagRepository->delete($id);
    }
} 