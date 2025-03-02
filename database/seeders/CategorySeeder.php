<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '技術',
                'slug' => 'technology',
                'description' => '技術相關文章',
                'color' => 'primary',
            ],
            [
                'name' => '生活',
                'slug' => 'lifestyle',
                'description' => '生活相關文章',
                'color' => 'secondary',
            ],
            [
                'name' => '旅遊',
                'slug' => 'travel',
                'description' => '旅遊相關文章',
                'color' => 'success',
            ],
            [
                'name' => '美食',
                'slug' => 'food',
                'description' => '美食相關文章',
                'color' => 'danger',
            ],
            [
                'name' => '健康',
                'slug' => 'health',
                'description' => '健康相關文章',
                'color' => 'warning',
            ],
            [
                'name' => '教育',
                'slug' => 'education',
                'description' => '教育相關文章',
                'color' => 'info',
            ],
            [
                'name' => '娛樂',
                'slug' => 'entertainment',
                'description' => '娛樂相關文章',
                'color' => 'accent',
            ],
        ];

        // 處理重複的分類
        if ($this->command->confirm('是否更新現有分類？', true)) {
            // 獲取所有重複的分類
            $duplicateCategories = Category::whereIn('name', array_column($categories, 'name'))
                ->whereNotIn('slug', array_column($categories, 'slug'))
                ->get();
            
            if ($duplicateCategories->count() > 0) {
                $this->command->info('發現以下重複的分類：');
                foreach ($duplicateCategories as $duplicate) {
                    $this->command->info("- {$duplicate->name} (slug: {$duplicate->slug})");
                }
                
                if ($this->command->confirm('是否刪除這些重複的分類？', true)) {
                    foreach ($duplicateCategories as $duplicate) {
                        // 將關聯的文章移動到正確的分類
                        $correctCategory = Category::where('slug', array_column(
                            array_filter($categories, function($cat) use ($duplicate) {
                                return $cat['name'] === $duplicate->name;
                            }),
                            'slug'
                        )[0])->first();
                        
                        if ($correctCategory) {
                            // 更新關聯的文章
                            DB::table('posts')
                                ->where('category_id', $duplicate->id)
                                ->update(['category_id' => $correctCategory->id]);
                            
                            $this->command->info("已將分類 '{$duplicate->name}' (slug: {$duplicate->slug}) 的文章移動到 '{$correctCategory->name}' (slug: {$correctCategory->slug})");
                            
                            // 刪除重複的分類
                            $duplicate->delete();
                            $this->command->info("已刪除重複的分類 '{$duplicate->name}' (slug: {$duplicate->slug})");
                        }
                    }
                }
            } else {
                $this->command->info('沒有發現重複的分類。');
            }
        }

        foreach ($categories as $category) {
            // 檢查分類是否已存在
            $existingCategory = Category::where('slug', $category['slug'])->first();
            
            if ($existingCategory) {
                // 更新現有分類
                $existingCategory->update([
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'color' => $category['color'],
                ]);
                
                $this->command->info("分類 '{$category['name']}' (slug: {$category['slug']}) 已更新。");
            } else {
                // 創建新分類
                Category::create([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'color' => $category['color'],
                ]);
                
                $this->command->info("分類 '{$category['name']}' 已創建，slug: {$category['slug']}");
            }
        }
        
        $this->command->info('所有分類已成功處理！');
    }
}
