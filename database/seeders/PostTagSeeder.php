<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostTagSeeder extends Seeder
{
    /**
     * 運行數據庫填充
     */
    public function run(): void
    {
        $this->command->info('開始為文章添加標籤...');
        
        // 獲取所有文章和標籤
        $posts = Post::all();
        $tags = Tag::all();
        $tagIds = $tags->pluck('id')->toArray();
        
        // 為每篇文章隨機添加1-3個標籤
        foreach ($posts as $post) {
            // 隨機選擇1-3個標籤
            $randomTagCount = rand(1, 3);
            $randomTagIds = array_rand(array_flip($tagIds), $randomTagCount);
            
            // 如果只有一個標籤，確保它是數組
            if (!is_array($randomTagIds)) {
                $randomTagIds = [$randomTagIds];
            }
            
            // 同步標籤
            $post->tags()->sync($randomTagIds);
        }
        
        // 更新標籤計數
        $this->command->call('tags:update-count');
        
        $this->command->info('文章標籤添加完成！');
    }
} 