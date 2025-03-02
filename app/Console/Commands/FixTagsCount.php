<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTagsCount extends Command
{
    /**
     * 命令名稱
     *
     * @var string
     */
    protected $signature = 'tags:fix-count';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '檢查和修復標籤計數';

    /**
     * 執行命令
     */
    public function handle()
    {
        $this->info('開始檢查和修復標籤計數...');

        // 獲取所有標籤
        $tags = Tag::all();
        $this->info("找到 {$tags->count()} 個標籤");

        // 檢查每個標籤的文章計數
        foreach ($tags as $tag) {
            $actualCount = DB::table('post_tag')->where('tag_id', $tag->id)->count();
            
            $this->info("標籤 '{$tag->name}' (ID: {$tag->id}):");
            $this->info("  - 當前計數: {$tag->posts_count}");
            $this->info("  - 實際計數: {$actualCount}");
            
            if ($tag->posts_count != $actualCount) {
                $this->warn("  - 計數不匹配，正在修復...");
                $tag->posts_count = $actualCount;
                $tag->save();
                $this->info("  - 已修復");
            } else {
                $this->info("  - 計數正確");
            }
        }

        // 檢查熱門標籤
        $popularTags = Tag::where('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();
            
        $this->info("\n熱門標籤:");
        if ($popularTags->isEmpty()) {
            $this->warn("沒有找到熱門標籤");
        } else {
            foreach ($popularTags as $tag) {
                $this->info("- {$tag->name}: {$tag->posts_count} 篇文章");
            }
        }

        $this->info("\n標籤計數檢查和修復完成！");
    }
} 