<?php

namespace App\Services\Comment;

use App\Exceptions\CommentException;
use App\Models\Comment;
use App\Models\User;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Services\DebugService;
use App\Services\Interfaces\CommentServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class CommentService implements CommentServiceInterface
{
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;
    
    /**
     * @var DebugService
     */
    private $debugService;

    /**
     * CommentService 构造函数
     *
     * @param CommentRepositoryInterface $commentRepository
     * @param DebugService $debugService
     */
    public function __construct(CommentRepositoryInterface $commentRepository, DebugService $debugService)
    {
        $this->commentRepository = $commentRepository;
        $this->debugService = $debugService;
    }

    /**
     * {@inheritdoc}
     */
    public function createComment(array $data, int $postId, int $userId): Comment
    {
        try {
            $commentData = [
                'content' => $data['content'],
                'post_id' => $postId,
                'user_id' => $userId,
                'parent_id' => $data['parent_id'] ?? null,
            ];
            
            $comment = $this->commentRepository->create($commentData);

            $this->debugService->log("用戶 {$userId} 在文章 {$postId} 發表了評論");

            return $comment->load('user');
        } catch (ModelNotFoundException $e) {
            $this->debugService->logError($e, '文章不存在', ['post_id' => $postId]);
            throw CommentException::createFailed('文章不存在');
        } catch (\Exception $e) {
            $this->debugService->logError($e, '評論發表失敗');
            throw CommentException::createFailed($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateComment(int $commentId, array $newComment, int $userId): Comment
    {
        try {
            $comment = $this->commentRepository->findById($commentId);
            
            if (!$comment) {
                throw new ModelNotFoundException('評論不存在');
            }
            
            $user = User::findOrFail($userId);
            if (Gate::forUser($user)->denies('update', $comment)) {
                throw CommentException::unauthorized($commentId);
            }
            
            $comment = $this->commentRepository->update($commentId, [
                'content' => $newComment['content']
            ]);

            $this->debugService->log("用戶 {$userId} 更新了評論 {$commentId}");

            return $comment;
        } catch (ModelNotFoundException $e) {
            $this->debugService->logError($e, '評論不存在', ['comment_id' => $commentId]);
            throw CommentException::notFound($commentId);
        } catch (CommentException $e) {
            $this->debugService->logError($e, $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            $this->debugService->logError($e, '評論更新失敗', ['comment_id' => $commentId]);
            throw CommentException::updateFailed($commentId, $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteComment(int $commentId, int $userId): bool
    {
        try {
            $comment = $this->commentRepository->findById($commentId);
            
            if (!$comment) {
                throw new ModelNotFoundException('評論不存在');
            }
            
            $user = User::findOrFail($userId);
            if (Gate::forUser($user)->denies('delete', $comment)) {
                throw CommentException::unauthorized($commentId);
            }
            
            $result = $this->commentRepository->delete($commentId);
            
            $this->debugService->log("用戶 {$userId} 刪除了評論 {$commentId}");
            
            return $result;
        } catch (ModelNotFoundException $e) {
            $this->debugService->logError($e, '評論不存在', ['comment_id' => $commentId]);
            throw CommentException::notFound($commentId);
        } catch (CommentException $e) {
            $this->debugService->logError($e, $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->debugService->logError($e, '評論刪除失敗', ['comment_id' => $commentId]);
            throw CommentException::deleteFailed($commentId, $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPostComments(int $postId): Collection
    {
        try {
            return $this->commentRepository->getPostComments($postId);
        } catch (\Exception $e) {
            $this->debugService->logError($e, '獲取評論失敗', ['post_id' => $postId]);
            throw new \Exception('獲取評論失敗: ' . $e->getMessage());
        }
    }
} 