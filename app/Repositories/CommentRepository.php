<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use App\Services\DebugService;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Comment
    {
        return Comment::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): Comment
    {
        $comment = Comment::findOrFail($id);
        $comment->update($data);
        return $comment;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        return (bool) Comment::destroy($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getPostComments(int $postId): Collection
    {
        return Comment::where('post_id', $postId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function incrementLikes(int $id): Comment
    {
        $comment = Comment::findOrFail($id);
        $comment->increment('likes_count');
        return $comment->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function isLikedByUser(int $commentId, int $userId): bool
    {
        $comment = Comment::findOrFail($commentId);
        return $comment->isLikedByUser($userId);
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
            } catch (\Illuminate\Database\QueryException $e) {
                // 處理唯一性約束違反的情況
                if ($e->errorInfo[1] == 1062) {
                    // 如果是唯一性約束違反，說明記錄已存在，不需要再增加計數
                    $debugService->log('嘗試創建重複的評論喜歡記錄', [
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
        }
        
        return $comment->fresh();
    }
} 