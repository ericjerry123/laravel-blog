<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCategoriesCount extends Command
{
    /**
     * 命令名稱
     *
     * @var string
     */
    protected $signature = 'categories:update-count';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '更新所有分類的文章計數';

    /**
     * 執行命令
     */
    public function handle()
    {
        $this->info('開始更新分類文章計數...');
        
        // 獲取所有分類
        $categories = Category::all();
        $this->info("找到 {$categories->count()} 個分類");
        
        // 更新每個分類的文章計數
        foreach ($categories as $category) {
            $count = DB::table('posts')->where('category_id', $category->id)->count();
            
            $this->info("分類 '{$category->name}' 的文章計數: {$count}");
            
            // 更新計數
            $category->posts_count = $count;
            $category->saveQuietly();
        }
        
        $this->info('分類文章計數更新完成！');
        
        // 顯示熱門分類
        $this->info('熱門分類:');
        $popularCategories = Category::where('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();
            
        if ($popularCategories->isEmpty()) {
            $this->warn('沒有找到熱門分類');
        } else {
            foreach ($popularCategories as $category) {
                $this->info("- {$category->name}: {$category->posts_count} 篇文章");
            }
        }
        
        return Command::SUCCESS;
    }
} 