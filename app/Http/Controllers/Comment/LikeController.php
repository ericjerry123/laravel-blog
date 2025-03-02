<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Models\Comment;
use App\Services\DebugService;
use App\Services\Interfaces\CommentLikeServiceInterface;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    use RespondsWithJson, LogsExceptions;

    /**
     * @var CommentLikeServiceInterface
     */
    private $commentLikeService;
    
    /**
     * @var DebugService
     */
    private $debugService;

    /**
     * LikeController constructor
     * 
     * @param CommentLikeServiceInterface $commentLikeService
     * @param DebugService $debugService
     */
    public function __construct(CommentLikeServiceInterface $commentLikeService, DebugService $debugService)
    {
        $this->middleware('auth');
        $this->commentLikeService = $commentLikeService;
        $this->debugService = $debugService;
    }

    /**
     * 點讚或取消點讚評論
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function toggle(Request $request, int $id)
    {
        try {
            $this->debugService->logCommentLike('評論點讚請求開始', ['comment_id' => $id, 'user_id' => Auth::id()]);
            
            $comment = $this->commentLikeService->toggleLike($id, Auth::id());
            $isLiked = $comment->isLikedByUser(Auth::id());
            
            $this->debugService->logCommentLike('評論點讚狀態切換成功', [
                'comment_id' => $id,
                'user_id' => Auth::id(),
                'is_liked' => $isLiked,
                'likes_count' => $comment->likes_count
            ]);

            return $this->respondWithSuccess(
                $request,
                $isLiked ? '點讚成功' : '取消點讚成功',
                [
                    'likes_count' => $comment->likes_count,
                    'is_liked' => $isLiked
                ]
            );
        } catch (QueryException $e) {
            // 處理資料庫查詢異常
            $this->logException($e, '評論點讚資料庫操作失敗', [
                'comment_id' => $id,
                'user_id' => Auth::id(),
                'error_code' => $e->getCode()
            ]);

            return $this->respondWithError($request, '操作失敗，請稍後再試');
        } catch (Exception $e) {
            $this->logException($e, '評論點讚失敗', [
                'comment_id' => $id,
                'user_id' => Auth::id()
            ]);

            return $this->respondWithError($request, '操作失敗，請稍後再試');
        }
    }
} 