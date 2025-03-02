<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
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

        static::creating(function ($tag) {
            $tag->slug = $tag->slug ?? Str::slug($tag->name);
        });
    }

    /**
     * 獲取此標籤關聯的所有文章
     *
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->withTimestamps();
    }

    /**
     * 根據名稱查找或創建標籤
     *
     * @param array $names
     * @return array
     */
    public static function findOrCreateMany(array $names): array
    {
        $tags = collect($names)->map(function ($name) {
            return trim($name);
        })->filter()->map(function ($name) {
            return static::firstOrCreate(['name' => $name], [
                'slug' => Str::slug($name),
                'color' => static::getRandomColor(),
            ]);
        });

        return $tags->pluck('id')->toArray();
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
     * 獲取熱門標籤（按文章數量排序）
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
