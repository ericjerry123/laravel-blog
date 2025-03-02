<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * 運行數據庫填充
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'color' => 'primary'],
            ['name' => 'PHP', 'color' => 'secondary'],
            ['name' => 'Web開發', 'color' => 'accent'],
            ['name' => 'JavaScript', 'color' => 'info'],
            ['name' => 'Vue.js', 'color' => 'success'],
            ['name' => 'React', 'color' => 'warning'],
            ['name' => '前端', 'color' => 'error'],
            ['name' => '後端', 'color' => 'primary'],
            ['name' => '數據庫', 'color' => 'secondary'],
            ['name' => 'API', 'color' => 'accent'],
            ['name' => '安全', 'color' => 'info'],
            ['name' => '性能優化', 'color' => 'success'],
            ['name' => '測試', 'color' => 'warning'],
            ['name' => 'DevOps', 'color' => 'error'],
            ['name' => '人工智能', 'color' => 'primary'],
        ];

        foreach ($tags as $tag) {
            // 對於中文標籤，使用拼音或英文別名作為 slug
            $slug = match ($tag['name']) {
                '前端' => 'frontend',
                '後端' => 'backend',
                '數據庫' => 'database',
                '安全' => 'security',
                '性能優化' => 'performance',
                '測試' => 'testing',
                '人工智能' => 'ai',
                'Web開發' => 'web-development',
                default => Str::slug($tag['name']),
            };

            Tag::create([
                'name' => $tag['name'],
                'slug' => $slug,
                'color' => $tag['color'],
            ]);
        }
    }
} 