<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTagsCount extends Command
{
    /**
     * 命令名稱
     *
     * @var string
     */
    protected $signature = 'tags:update-count';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '更新所有標籤的文章計數';

    /**
     * 執行命令
     */
    public function handle()
    {
        $this->info('開始更新標籤文章計數...');

        // 獲取所有標籤的文章計數
        $tagCounts = DB::table('post_tag')
            ->select('tag_id', DB::raw('count(*) as count'))
            ->groupBy('tag_id')
            ->get();

        // 更新每個標籤的計數
        foreach ($tagCounts as $tagCount) {
            Tag::where('id', $tagCount->tag_id)
                ->update(['posts_count' => $tagCount->count]);
        }

        $this->info('標籤文章計數更新完成！');
    }
} 