<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogsExceptions;
use App\Services\Interfaces\CommentLikeServiceInterface;
use App\Services\DebugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
    use LogsExceptions;

    protected CommentLikeServiceInterface $commentLikeService;
    protected DebugService $debugService;

    /**
     * 構造函數
     *
     * @param CommentLikeServiceInterface $commentLikeService
     * @param DebugService $debugService
     */
    public function __construct(
        CommentLikeServiceInterface $commentLikeService,
        DebugService $debugService
    ) {
        $this->commentLikeService = $commentLikeService;
        $this->debugService = $debugService;
    }

    /**
     * 切換評論的點贊狀態
     *
     * @param Request $request
     * @param int $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request, int $commentId)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => '請先登入後再進行操作'
                ], 401);
            }

            $result = $this->commentLikeService->toggleLike($commentId, $userId);

            if ($result['success']) {
                return response()->json($result);
            } else {
                return response()->json($result, $result['error_code'] ?? 500);
            }
        } catch (Exception $e) {
            $this->logException($e, '操作失敗，請稍後再試');
            return response()->json([
                'success' => false,
                'message' => '操作失敗，請稍後再試'
            ], 500);
        }
    }

    /**
     * 獲取評論的點贊數
     *
     * @param int $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(int $commentId)
    {
        try {
            $result = $this->commentLikeService->getLikesCount($commentId);

            if ($result['success']) {
                return response()->json($result);
            } else {
                return response()->json($result, $result['error_code'] ?? 500);
            }
        } catch (Exception $e) {
            $this->logException($e, '獲取點贊數失敗');
            return response()->json([
                'success' => false,
                'message' => '獲取點贊數失敗'
            ], 500);
        }
    }
}
