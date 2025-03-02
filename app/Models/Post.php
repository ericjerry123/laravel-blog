<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Services\DebugService;

class Post extends Model
{
    use HasFactory;

    /**
     * 可批量賦值的屬性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'views_count',
        'likes_count',
    ];

    /**
     * 獲取此文章的作者
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 獲取此文章的分類
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 獲取此文章的所有留言
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * 獲取此文章的所有留言（包括父留言和子留言）
     *
     * @return HasMany
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 獲取此文章的所有標籤
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps();
    }

    /**
     * 同步文章標籤
     *
     * @param array $tagNames
     * @return array
     */
    public function syncTags(array $tagNames): array
    {
        $tagIds = Tag::findOrCreateMany($tagNames);
        $result = $this->tags()->sync($tagIds);
        
        // 直接更新所有受影響的標籤計數
        $allTagIds = array_unique(array_merge(
            $result['attached'] ?? [],
            $result['detached'] ?? [],
            $result['updated'] ?? []
        ));
        
        $this->updateTagsCount($allTagIds);
        
        return $result;
    }
    
    /**
     * 更新標籤計數
     *
     * @param array $tagIds
     * @return void
     */
    protected function updateTagsCount(array $tagIds): void
    {
        if (empty($tagIds)) {
            return;
        }
        
        // 使用 SQL 查詢直接更新標籤計數
        foreach ($tagIds as $tagId) {
            $count = DB::table('post_tag')->where('tag_id', $tagId)->count();
            DB::table('tags')->where('id', $tagId)->update(['posts_count' => $count]);
        }
    }

    /**
     * 更新分類計數
     *
     * @return void
     */
    public function updateCategoryCount(): void
    {
        if ($this->category_id) {
            $count = static::where('category_id', $this->category_id)->count();
            DB::table('categories')->where('id', $this->category_id)->update(['posts_count' => $count]);
        }
    }

    /**
     * 獲取此文章的所有喜歡
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * 檢查用戶是否喜歡此文章
     *
     * @param int $userId
     * @return bool
     */
    public function isLikedByUser(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * 切換用戶對此文章的喜歡狀態
     *
     * @param int $userId
     * @return bool 返回喜歡狀態，true 表示喜歡，false 表示取消喜歡
     * @throws \Exception 如果操作失敗
     */
    public function toggleLike(int $userId): bool
    {
        try {
            $debugService = App::make(DebugService::class);
            
            // 檢查用戶是否已經喜歡過這篇文章
            if ($this->isLikedByUser($userId)) {
                // 如果已經喜歡過，則移除喜歡
                $this->likes()->where('user_id', $userId)->delete();
                
                // 確保 likes_count 不會變成負數
                if ($this->likes_count > 0) {
                    $this->decrement('likes_count');
                } else {
                    $this->update(['likes_count' => 0]);
                }
                
                return false;
            } else {
                // 如果還沒喜歡，則添加喜歡
                try {
                    $this->likes()->create(['user_id' => $userId]);
                    $this->increment('likes_count');
                    return true;
                } catch (\Illuminate\Database\QueryException $e) {
                    // 處理唯一性約束違反的情況
                    if ($e->errorInfo[1] == 1062) {
                        // 如果是唯一性約束違反，說明記錄已存在，視為已喜歡
                        $debugService->log('嘗試創建重複的喜歡記錄', [
                            'post_id' => $this->id,
                            'user_id' => $userId
                        ], 'warning');
                        return true;
                    }
                    throw $e;
                }
            }
        } catch (\Exception $e) {
            $debugService->logError($e, '切換文章喜歡狀態失敗', [
                'post_id' => $this->id,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }
}
