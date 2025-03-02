<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Services\DebugService;
use App\Services\Interfaces\CommentServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use RespondsWithJson, LogsExceptions;

    /**
     * @var CommentServiceInterface
     */
    private $commentService;
    
    /**
     * @var DebugService
     */
    private $debugService;

    /**
     * CommentController 構造函數
     * 
     * @param CommentServiceInterface $commentService
     * @param DebugService $debugService
     */
    public function __construct(CommentServiceInterface $commentService, DebugService $debugService)
    {
        $this->middleware('auth');
        $this->commentService = $commentService;
        $this->debugService = $debugService;
    }

    /**
     * 儲存新評論
     *
     * @param StoreCommentRequest $request
     * @param int $postId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request, int $postId)
    {
        try {
            $comment = $this->commentService->createComment($request->validated(), $postId, Auth::id());

            return $this->respondWithSuccess($request, '評論發表成功', ['comment' => $comment]);
        } catch (Exception $e) {
            $this->logException($e, '評論發表失敗', [
                'post_id' => $postId,
                'user_id' => Auth::id()
            ]);

            return $this->respondWithError($request, '評論發表失敗: ' . $e->getMessage());
        }
    }

    /**
     * 更新評論
     *
     * @param StoreCommentRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCommentRequest $request, int $id)
    {
        try {
            $comment = $this->commentService->updateComment($id, $request->validated(), Auth::id());

            return $this->respondWithSuccess($request, '評論更新成功', ['comment' => $comment]);
        } catch (Exception $e) {
            $this->logException($e, '評論更新失敗', [
                'comment_id' => $id,
                'user_id' => Auth::id()
            ]);

            return $this->respondWithError($request, '評論更新失敗: ' . $e->getMessage());
        }
    }

    /**
     * 刪除評論
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $this->commentService->deleteComment($id, Auth::id());

            return $this->respondWithSuccess($request, '評論刪除成功');
        } catch (Exception $e) {
            $this->logException($e, '評論刪除失敗', [
                'comment_id' => $id,
                'user_id' => Auth::id()
            ]);

            return $this->respondWithError($request, '評論刪除失敗: ' . $e->getMessage());
        }
    }
} 