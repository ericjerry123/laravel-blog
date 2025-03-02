<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostCategoryObserver
{
    /**
     * 處理 Post "created" 事件
     */
    public function created(Post $post): void
    {
        $this->updateCategoryCount($post);
    }

    /**
     * 處理 Post "updated" 事件
     */
    public function updated(Post $post): void
    {
        // 如果分類發生變化，更新舊分類和新分類的計數
        if ($post->isDirty('category_id')) {
            $oldCategoryId = $post->getOriginal('category_id');
            
            // 更新舊分類的計數（如果存在）
            if ($oldCategoryId) {
                $this->updateCategoryCountById($oldCategoryId);
            }
            
            // 更新新分類的計數
            $this->updateCategoryCount($post);
        }
    }

    /**
     * 處理 Post "deleted" 事件
     */
    public function deleted(Post $post): void
    {
        $this->updateCategoryCount($post);
    }

    /**
     * 處理 Post "restored" 事件
     */
    public function restored(Post $post): void
    {
        $this->updateCategoryCount($post);
    }

    /**
     * 處理 Post "force deleted" 事件
     */
    public function forceDeleted(Post $post): void
    {
        $this->updateCategoryCount($post);
    }
    
    /**
     * 更新文章分類的計數
     */
    protected function updateCategoryCount(Post $post): void
    {
        if ($post->category_id) {
            $this->updateCategoryCountById($post->category_id);
        }
    }
    
    /**
     * 根據分類 ID 更新分類的文章計數
     */
    protected function updateCategoryCountById(int $categoryId): void
    {
        $count = DB::table('posts')->where('category_id', $categoryId)->count();
        DB::table('categories')->where('id', $categoryId)->update(['posts_count' => $count]);
    }
} 