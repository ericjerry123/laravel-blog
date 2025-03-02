<?php

namespace App\Services\Comment;

use App\Exceptions\CommentException;
use App\Models\Comment;
use App\Models\User;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\CommentLikeRepositoryInterface;
use App\Services\DebugService;
use App\Services\Interfaces\CommentLikeServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CommentLikeService implements CommentLikeServiceInterface
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
     * @var CommentLikeRepositoryInterface
     */
    private $commentLikeRepository;

    /**
     * CommentLikeService 構造函數
     *
     * @param CommentRepositoryInterface $commentRepository
     * @param DebugService $debugService
     * @param CommentLikeRepositoryInterface $commentLikeRepository
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository,
        DebugService $debugService,
        CommentLikeRepositoryInterface $commentLikeRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->debugService = $debugService;
        $this->commentLikeRepository = $commentLikeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function toggleLike(int $commentId, int $userId): array
    {
        try {
            // 獲取評論實例
            $comment = $this->commentRepository->findById($commentId);
            if (!$comment) {
                throw new CommentException('評論不存在', 404);
            }

            // 檢查用戶是否有權限點贊評論
            $user = Auth::user();
            if (Gate::denies('like', $comment)) {
                throw new CommentException('您沒有權限點贊此評論', 403);
            }

            // 切換點贊狀態
            $comment = $this->commentLikeRepository->toggleLike($commentId, $userId);

            // 檢查當前點贊狀態
            $isLiked = $this->commentLikeRepository->isLikedByUser($commentId, $userId);

            return [
                'success' => true,
                'message' => $isLiked ? '點贊成功' : '取消點贊成功',
                'data' => [
                    'comment_id' => $comment->id,
                    'likes_count' => $comment->likes_count,
                    'is_liked' => $isLiked
                ]
            ];
        } catch (CommentException $e) {
            $this->debugService->logError($e, '點贊評論失敗', [
                'comment_id' => $commentId,
                'user_id' => $userId,
                'error_code' => $e->getCode()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => $e->getCode()
            ];
        } catch (\Exception $e) {
            $this->debugService->logError($e, '點贊評論時發生未知錯誤', [
                'comment_id' => $commentId,
                'user_id' => $userId
            ]);

            return [
                'success' => false,
                'message' => '點贊操作失敗，請稍後再試',
                'error_code' => 500
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLikedByUser(int $commentId, int $userId): bool
    {
        try {
            return $this->commentLikeRepository->isLikedByUser($commentId, $userId);
        } catch (\Exception $e) {
            $this->debugService->logError($e, '檢查點贊狀態失敗', [
                'comment_id' => $commentId,
                'user_id' => $userId
            ]);
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLikesCount(int $commentId): array
    {
        try {
            $count = $this->commentLikeRepository->getLikesCount($commentId);

            return [
                'success' => true,
                'data' => [
                    'comment_id' => $commentId,
                    'likes_count' => $count
                ]
            ];
        } catch (\Exception $e) {
            $this->debugService->logError($e, '獲取評論點贊數失敗', [
                'comment_id' => $commentId
            ]);

            return [
                'success' => false,
                'message' => '獲取點贊數失敗',
                'error_code' => 500
            ];
        }
    }
}
