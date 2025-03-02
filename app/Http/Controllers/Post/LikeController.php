<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Services\DebugService;
use App\Services\Interfaces\PostServiceInterface;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    use RespondsWithJson, LogsExceptions;

    /**
     * @var PostServiceInterface
     */
    private $postService;

    /**
     * @var DebugService
     */
    private $debugService;

    /**
     * LikeController 構造函數
     *
     * @param PostServiceInterface $postService
     * @param DebugService $debugService
     */
    public function __construct(
        PostServiceInterface $postService,
        DebugService $debugService
    ) {
        $this->middleware('auth');
        $this->postService = $postService;
        $this->debugService = $debugService;
    }

    /**
     * 喜歡或取消喜歡文章
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request, int $id)
    {
        try {
            $this->debugService->logPostLike('文章點贊請求開始', ['post_id' => $id, 'user_id' => Auth::id()]);

            $post = $this->postService->getPost($id);

            // 確保用戶已登入
            if (!Auth::check()) {
                $this->debugService->log('未登入用戶嘗試點贊', ['post_id' => $id, 'ip' => $request->ip()], 'warning');

                if ($request->ajax()) {
                    return ApiResponse::unauthorized('請先登入後再喜歡文章');
                }
                return redirect()->route('login.view')->with('error', '請先登入後再喜歡文章');
            }

            $isLiked = $post->toggleLike(Auth::id());
            $this->debugService->logPostLike('文章點贊狀態切換成功', [
                'post_id' => $id,
                'user_id' => Auth::id(),
                'is_liked' => $isLiked,
                'likes_count' => $post->likes_count
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return ApiResponse::success(
                    $isLiked ? '已喜歡文章' : '已取消喜歡',
                    [
                        'likes_count' => $post->likes_count,
                        'is_liked' => $isLiked
                    ]
                );
            }

            return redirect()->back()->with('success', $isLiked ? '已喜歡文章' : '已取消喜歡');
        } catch (QueryException $e) {
            // 處理資料庫查詢異常
            $this->debugService->logError($e, '文章點贊資料庫操作失敗', [
                'post_id' => $id,
                'user_id' => Auth::id()
            ]);

            // 返回友好的錯誤訊息
            if ($request->ajax() || $request->wantsJson()) {
                return ApiResponse::serverError('操作失敗，請稍後再試');
            }

            return redirect()->back()->with('error', '操作失敗，請稍後再試');
        } catch (Exception $e) {
            $this->debugService->logError($e, '文章點贊失敗', [
                'post_id' => $id,
                'user_id' => Auth::id()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return ApiResponse::serverError('操作失敗，請稍後再試');
            }

            return redirect()->back()->with('error', '操作失敗，請稍後再試');
        }
    }
} 