<?php

namespace App\Observers;

use App\Models\Tag;
use App\Services\DebugService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagObserver
{
    /**
     * 處理標籤「已創建」事件
     */
    public function created(Tag $tag): void
    {
        // 新標籤創建時，posts_count 默認為 0
    }

    /**
     * 處理標籤「已更新」事件
     */
    public function updated(Tag $tag): void
    {
        // 標籤更新時，不需要特殊處理
    }

    /**
     * 處理標籤「已刪除」事件
     */
    public function deleted(Tag $tag): void
    {
        // 標籤刪除時，不需要特殊處理，因為外鍵約束會自動處理關聯
    }

    /**
     * 處理標籤「已檢索」事件
     */
    public function retrieved(Tag $tag): void
    {
        // 當標籤被檢索時，更新其文章計數
        $count = DB::table('post_tag')->where('tag_id', $tag->id)->count();
        
        // 只有當計數不同時才更新，避免不必要的數據庫操作
        if ($tag->posts_count != $count) {
            $debugService = App::make(DebugService::class);
            $debugService->log(
                'info',
                "更新標籤 '{$tag->name}' (ID: {$tag->id}) 的文章計數從 {$tag->posts_count} 到 {$count}",
                ['tag_id' => $tag->id, 'old_count' => $tag->posts_count, 'new_count' => $count]
            );
            $tag->posts_count = $count;
            $tag->saveQuietly(); // 使用 saveQuietly 避免觸發更新事件導致無限循環
        }
    }
} 