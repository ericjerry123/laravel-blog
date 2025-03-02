<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * 確定用戶是否可以更新評論
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * 確定用戶是否可以刪除評論
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * 確定用戶是否可以點贊評論
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function like(User $user, Comment $comment): bool
    {
        // 所有已認證用戶都可以點讚或取消點讚
        return true;
    }
} 