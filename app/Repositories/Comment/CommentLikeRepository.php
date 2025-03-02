<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentLikeRepositoryInterface;
use App\Services\DebugService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CommentLikeRepository implements CommentLikeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function isLikedByUser(int $commentId, int $userId): bool
    {
        try {
            $comment = Comment::findOrFail($commentId);
            return $comment->isLikedByUser($userId);
        } catch (\Exception $e) {
            $debugService = App::make(DebugService::class);
            $debugService->logError($e, '檢查點贊狀態失敗', [
                'comment_id' => $commentId,
                'user_id' => $userId
            ]);
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toggleLike(int $commentId, int $userId): Comment
    {
        $comment = Comment::findOrFail($commentId);
        
        // 檢查用戶是否已經點贊過，並執行相應操作
        if ($this->isLikedByUser($commentId, $userId)) {
            return $this->removeLike($commentId, $userId);
        } else {
            return $this->addLike($commentId, $userId);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addLike(int $commentId, int $userId): Comment
    {
        $comment = Comment::findOrFail($commentId);
        $debugService = App::make(DebugService::class);
        
        // 如果用戶未點贊過，則添加點贊
        if (!$comment->isLikedByUser($userId)) {
            try {
                $comment->likes()->create(['user_id' => $userId]);
                $comment->increment('likes_count');
                $debugService->log("用戶 {$userId} 點贊了評論 {$commentId}");
            } catch (\Illuminate\Database\QueryException $e) {
                // 處理唯一性約束違反的情況
                if ($e->errorInfo[1] == 1062) {
                    // 如果是唯一性約束違反，說明記錄已存在，不需要再增加計數
                    $debugService->log('嘗試創建重複的評論點贊記錄', [
                        'comment_id' => $commentId,
                        'user_id' => $userId
                    ], 'warning');
                } else {
                    throw $e;
                }
            }
        }
        
        return $comment->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function removeLike(int $commentId, int $userId): Comment
    {
        $comment = Comment::findOrFail($commentId);
        $debugService = App::make(DebugService::class);
        
        // 如果用戶已經點贊過，則移除點贊
        if ($comment->isLikedByUser($userId)) {
            $comment->likes()->where('user_id', $userId)->delete();
            // 確保 likes_count 不會變成負數
            if ($comment->likes_count > 0) {
                $comment->decrement('likes_count');
            } else {
                // 如果 likes_count 已經是 0，則直接設為 0
                $comment->update(['likes_count' => 0]);
            }
            $debugService->log("用戶 {$userId} 取消點贊了評論 {$commentId}");
        }
        
        return $comment->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function getLikesCount(int $commentId): int
    {
        try {
            $comment = Comment::findOrFail($commentId);
            return $comment->likes_count;
        } catch (\Exception $e) {
            $debugService = App::make(DebugService::class);
            $debugService->logError($e, '獲取點贊數失敗', ['comment_id' => $commentId]);
            return 0;
        }
    }
} 