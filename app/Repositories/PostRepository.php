<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\SearchableRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface, SearchableRepositoryInterface
{
    /**
     * 獲取基礎查詢構建器
     *
     * @return Builder
     */
    protected function getBaseQuery(): Builder
    {
        return Post::with(['user', 'tags', 'category'])->latest();
    }

    /**
     * 應用搜索條件到查詢
     *
     * @param Builder $query
     * @param string|null $search
     * @return Builder
     */
    protected function applySearchCondition(Builder $query, ?string $search): Builder
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        return $query;
    }

    /**
     * 應用標籤過濾到查詢
     *
     * @param Builder $query
     * @param string|null $tag
     * @return Builder
     */
    protected function applyTagFilter(Builder $query, ?string $tag): Builder
    {
        if ($tag) {
            $query->whereHas('tags', function($q) use ($tag) {
                $q->where('slug', $tag);
            });
        }
        
        return $query;
    }
    
    /**
     * 應用分類過濾到查詢
     *
     * @param Builder $query
     * @param string|null $category
     * @return Builder
     */
    protected function applyCategoryFilter(Builder $query, ?string $category): Builder
    {
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        
        return $query;
    }

    /**
     * 應用排序條件到查詢
     *
     * @param Builder $query
     * @param string|null $sortBy
     * @return Builder
     */
    protected function applySortCondition(Builder $query, ?string $sortBy): Builder
    {
        switch ($sortBy) {
            case 'popular':
                // 按喜歡數排序（熱門）
                $query->orderByDesc('likes_count');
                break;
            case 'most_commented':
                // 按評論數排序
                $query->withCount('allComments')->orderByDesc('all_comments_count');
                break;
            case 'latest':
            default:
                // 默認按創建時間排序（最新）
                $query->latest();
                break;
        }
        
        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function search(?string $search = null): LengthAwarePaginator
    {
        $query = $this->getBaseQuery();
        $query = $this->applySearchCondition($query, $search);
        
        return $query->paginate(10);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllPosts(?string $search = null, ?string $tag = null, ?string $category = null, ?string $sortBy = 'latest'): LengthAwarePaginator
    {
        $query = Post::with(['user', 'tags', 'category']);
        $query = $this->applySearchCondition($query, $search);
        $query = $this->applyTagFilter($query, $tag);
        $query = $this->applyCategoryFilter($query, $category);
        $query = $this->applySortCondition($query, $sortBy);
        
        return $query->paginate(10);
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Post
    {
        return $this->getBaseQuery()
            ->with(['likes', 'allComments.user', 'allComments.replies.user'])
            ->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        return $post;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        return (bool) Post::destroy($id);
    }
}
