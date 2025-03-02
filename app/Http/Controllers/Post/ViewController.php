<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Services\DebugService;
use App\Services\Interfaces\PostServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ViewController extends Controller
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
     * ViewController 構造函數
     *
     * @param PostServiceInterface $postService
     * @param DebugService $debugService
     */
    public function __construct(
        PostServiceInterface $postService,
        DebugService $debugService
    ) {
        $this->postService = $postService;
        $this->debugService = $debugService;
    }

    /**
     * 增加文章閱讀數
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function increment(Request $request, int $id)
    {
        try {
            $post = $this->postService->getPost($id);
            
            // 檢查會話中是否已經記錄了最近查看此文章的時間
            $viewKey = 'post_' . $id . '_viewed';
            $lastViewTime = $request->session()->get($viewKey);
            $currentTime = time();
            
            // 如果沒有記錄或者距離上次查看超過30秒，則增加閱讀數
            if (!$lastViewTime || ($currentTime - $lastViewTime) > 30) {
                $post->increment('views_count');
                $request->session()->put($viewKey, $currentTime);
                
                $this->debugService->log('增加文章閱讀數', [
                    'post_id' => $id,
                    'views_count' => $post->views_count,
                    'user_id' => $request->user() ? $request->user()->id : 'guest',
                    'ip' => $request->ip()
                ]);
                
                return ApiResponse::success('閱讀數已增加', [
                    'views_count' => $post->views_count
                ]);
            }
            
            return ApiResponse::success('閱讀數未增加（30秒內重複查看）', [
                'views_count' => $post->views_count
            ]);
            
        } catch (ModelNotFoundException $e) {
            $this->debugService->logError($e, '增加閱讀數失敗：文章未找到', ['id' => $id]);
            return ApiResponse::notFound('文章未找到');
        } catch (\Exception $e) {
            $this->debugService->logError($e, '增加閱讀數失敗', ['id' => $id]);
            return ApiResponse::serverError('操作失敗，請稍後再試');
        }
    }
} 