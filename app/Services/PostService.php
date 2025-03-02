<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * PostService 構造函數
     *
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllPosts(?string $search = null, ?string $tag = null, ?string $category = null, ?string $sortBy = 'latest'): LengthAwarePaginator
    {
        return $this->postRepository->getAllPosts($search, $tag, $category, $sortBy);
    }

    /**
     * {@inheritdoc}
     */
    public function getPost(int $id): Post
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new ModelNotFoundException('找不到文章');
        }

        return $post;
    }

    /**
     * {@inheritdoc}
     */
    public function createPost(array $data, ?array $tags = null): Post
    {
        // 在這裡可以添加業務邏輯，例如：
        // - 數據驗證
        // - 權限檢查
        // - 觸發事件
        // - 處理標籤、分類等

        $data['user_id'] = auth()->id() ?? 1; // 設置當前用戶ID，如果未登錄則使用默認ID
        
        $post = $this->postRepository->create($data);
        
        // 處理標籤
        if ($tags && !empty($tags)) {
            $post->syncTags($tags);
        }
        
        return $post;
    }

    /**
     * {@inheritdoc}
     */
    public function updatePost(int $id, array $data, ?array $tags = null): Post
    {
        // 確保文章存在
        $post = $this->getPost($id);
        
        // 在這裡可以添加更新相關的業務邏輯
        // 例如：檢查權限、驗證數據等
        
        $post = $this->postRepository->update($id, $data);
        
        // 處理標籤
        if ($tags !== null) {
            $post->syncTags($tags);
        }
        
        return $post;
    }

    /**
     * {@inheritdoc}
     */
    public function deletePost(int $id): bool
    {
        // 確保文章存在
        $post = $this->getPost($id);
        
        // 在這裡可以添加刪除相關的業務邏輯
        // 例如：檢查權限、處理關聯數據等
        
        return $this->postRepository->delete($id);
    }
}
