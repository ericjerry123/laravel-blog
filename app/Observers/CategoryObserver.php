<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{
    /**
     * 處理 Category "created" 事件
     */
    public function created(Category $category): void
    {
        // 初始化文章計數
        $this->updatePostsCount($category);
    }

    /**
     * 處理 Category "updated" 事件
     */
    public function updated(Category $category): void
    {
        // 如果分類名稱或 slug 發生變化，可能需要更新相關數據
    }

    /**
     * 處理 Category "deleted" 事件
     */
    public function deleted(Category $category): void
    {
        // 如果分類被刪除，可能需要處理相關文章
        // 例如，將文章移動到默認分類或更新文章的分類為 null
        DB::table('posts')->where('category_id', $category->id)->update(['category_id' => null]);
    }

    /**
     * 處理 Category "restored" 事件
     */
    public function restored(Category $category): void
    {
        // 如果分類被恢復，更新文章計數
        $this->updatePostsCount($category);
    }

    /**
     * 處理 Category "force deleted" 事件
     */
    public function forceDeleted(Category $category): void
    {
        // 如果分類被強制刪除，可能需要處理相關文章
        DB::table('posts')->where('category_id', $category->id)->update(['category_id' => null]);
    }
    
    /**
     * 更新分類的文章計數
     */
    protected function updatePostsCount(Category $category): void
    {
        $count = DB::table('posts')->where('category_id', $category->id)->count();
        $category->posts_count = $count;
        $category->saveQuietly();
    }
} 