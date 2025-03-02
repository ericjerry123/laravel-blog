<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Tag;

class PostTagObserver
{
    /**
     * 處理文章「已創建」事件
     */
    public function created(Post $post): void
    {
        // 當文章創建時，不需要處理標籤計數，因為標籤會在之後同步
    }

    /**
     * 處理文章「已更新」事件
     */
    public function updated(Post $post): void
    {
        // 當文章更新時，不需要處理標籤計數，因為標籤會在之後同步
    }

    /**
     * 處理文章「已刪除」事件
     */
    public function deleted(Post $post): void
    {
        // 當文章被刪除時，更新相關標籤的計數
        $this->updateTagsCount($post->tags->pluck('id')->toArray());
    }

    /**
     * 更新標籤計數
     */
    private function updateTagsCount(array $tagIds): void
    {
        if (empty($tagIds)) {
            return;
        }

        foreach ($tagIds as $tagId) {
            $tag = Tag::find($tagId);
            if ($tag) {
                $tag->posts_count = $tag->posts()->count();
                $tag->save();
            }
        }
    }
} 