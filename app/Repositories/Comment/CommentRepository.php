<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
} 