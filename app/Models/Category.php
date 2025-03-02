<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * 可批量賦值的屬性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
    ];

    /**
     * 自動遞增的屬性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'posts_count' => 'integer',
    ];

    /**
     * 模型啟動時的鉤子
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });
    }

    /**
     * 獲取此分類下的所有文章
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 獲取隨機顏色
     *
     * @return string
     */
    protected static function getRandomColor(): string
    {
        $colors = ['primary', 'secondary', 'accent', 'info', 'success', 'warning', 'error'];
        return $colors[array_rand($colors)];
    }

    /**
     * 獲取熱門分類（按文章數量排序）
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function popular($limit = 10)
    {
        return static::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit($limit);
    }
}
