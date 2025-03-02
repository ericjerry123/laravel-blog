<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 獲取所有文章和分類
        $posts = Post::all();
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('沒有分類可用於分配給文章。');
            return;
        }
        
        // 為每篇文章隨機分配一個分類
        foreach ($posts as $post) {
            $randomCategory = $categories->random();
            $post->category_id = $randomCategory->id;
            $post->save();
            
            $this->command->info("文章 '{$post->title}' 已分配到分類 '{$randomCategory->name}'");
        }
        
        $this->command->info('所有文章已成功分配分類！');
    }
} 