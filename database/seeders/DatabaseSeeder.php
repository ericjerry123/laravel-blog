<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 先創建標籤和分類
        $this->call([
            TagSeeder::class,
            CategorySeeder::class,
        ]);

        // 創建用戶和文章
        User::factory()
            ->count(20)
            ->hasPosts(3)
            ->create();

        User::factory()
            ->hasPosts(5)
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
            
        // 為文章添加標籤和分類
        $this->call([
            PostTagSeeder::class,
            PostCategorySeeder::class,
        ]);
    }
}
