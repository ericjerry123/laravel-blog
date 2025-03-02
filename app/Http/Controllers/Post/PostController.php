<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Traits\LogsDebugInfo;
use App\Http\Traits\LogsExceptions;
use App\Http\Traits\RespondsWithJson;
use App\Services\DebugService;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\PostServiceInterface;
use App\Services\Interfaces\TagServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;

class PostController extends Controller
{
    use RespondsWithJson, LogsExceptions, LogsDebugInfo;

    /**
     * @var PostServiceInterface
     */
    private $postService;

    /**
     * @var TagServiceInterface
     */
    private $tagService;

    /**
     * @var CategoryServiceInterface
     */
    private $categoryService;

    /**
     * @var DebugService
     */
    private $debugService;

    /**
     * PostController 構造函數
     *
     * @param PostServiceInterface $postService
     * @param TagServiceInterface $tagService
     * @param CategoryServiceInterface $categoryService
     * @param DebugService $debugService
     */
    public function __construct(
        PostServiceInterface $postService,
        TagServiceInterface $tagService,
        CategoryServiceInterface $categoryService,
        DebugService $debugService
    ) {
        $this->middleware('auth')->except(['index', 'show']);

        $this->postService = $postService;
        $this->tagService = $tagService;
        $this->categoryService = $categoryService;
        $this->debugService = $debugService;
    }

    /**
     * 顯示文章列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $tag = $request->input('tag');
            $category = $request->input('category');
            $sortBy = $request->input('sort', 'latest'); // 獲取排序方式，默認為最新
            
            $posts = $this->postService->getAllPosts($search, $tag, $category, $sortBy);
            
            // 獲取熱門標籤和分類
            $popularTags = $this->tagService->getPopularTags();
            $categories = $this->categoryService->getAllCategories();
            
            // 記錄熱門標籤和分類
            $this->debugService->log('Popular Tags', ['count' => $popularTags->count()]);
            $this->debugService->log('Popular Categories', ['count' => $categories->count()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('posts._post_list', compact('posts'))->render(),
                    'nextPageUrl' => $posts->nextPageUrl(),
                ]);
            }
            
            return view('posts.index', compact('posts', 'popularTags', 'categories'));
        } catch (\Exception $e) {
            $this->logException($e, '獲取文章列表時發生錯誤');
            
            // 創建空的分頁對象而不是集合
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                [], // 空數據
                0,  // 總數
                10, // 每頁數量
                1,  // 當前頁
                ['path' => request()->url()] // 設置路徑
            );
            
            // 獲取熱門標籤和分類
            $popularTags = $this->tagService->getPopularTags();
            $categories = $this->categoryService->getAllCategories();
            
            return view('posts.index', [
                'posts' => $emptyPaginator, 
                'error' => '獲取文章列表時發生錯誤',
                'popularTags' => $popularTags,
                'categories' => $categories
            ]);
        }
    }

    /**
     * 顯示文章詳情
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $post = $this->postService->getPost($id);

            $popularTags = $this->tagService->getPopularTags();
            $categories = $this->categoryService->getAllCategories();

            // 加載評論數據，包括回覆和用戶信息
            $post->load(['comments.user', 'comments.replies.user', 'allComments']);

            // 使用 DebugService 記錄調試信息
            $this->debugService->logPostDetails($post, $categories);

            return view('posts.show', compact('post', 'popularTags', 'categories'));
        } catch (ModelNotFoundException $e) {
            // 處理文章未找到的情況
            $this->debugService->logError($e, '文章未找到', ['id' => $id]);
            return redirect()->route('posts.index')->with('error', '文章未找到');
        } catch (Exception $e) {
            // 處理其他異常
            $this->debugService->logError($e, '獲取文章詳情失敗');
            return redirect()->route('posts.index')->with('error', '獲取文章詳情失敗');
        }
    }

    /**
     * 顯示創建文章表單
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $tags = $this->tagService->getAllTags();
        $categories = $this->categoryService->getAllCategories();

        return view('posts.create', compact('tags', 'categories'));
    }

    /**
     * 新增文章
     *
     * @param StorePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePostRequest $request)
    {
        try {
            $tags = $request->input('tags', []);

            $post = $this->postService->createPost($request->validated(), $tags);

            return redirect()
                ->route('posts.show', $post)
                ->with('success', '文章創建成功');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', '文章創建失敗: ' . $e->getMessage());
        }
    }

    /**
     * 顯示編輯文章表單
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $post = $this->postService->getPost($id);
            $tags = $this->tagService->getAllTags();
            $categories = $this->categoryService->getAllCategories();
            $selectedTags = $post->tags->pluck('name')->toArray();

            return view('posts.edit', compact('post', 'tags', 'categories', 'selectedTags'));
        } catch (ModelNotFoundException $e) {
            return back()->with('error', '找不到該文章');
        } catch (Exception $e) {
            return back()->with('error', '獲取文章失敗: ' . $e->getMessage());
        }
    }

    /**
     * 更新文章
     *
     * @param StorePostRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StorePostRequest $request, int $id)
    {
        try {
            $tags = $request->input('tags', []);
            $post = $this->postService->updatePost($id, $request->validated(), $tags);
            return redirect()
                ->route('posts.show', $post)
                ->with('success', '文章更新成功');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', '文章更新失敗: ' . $e->getMessage());
        }
    }

    /**
     * 刪除文章
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        try {
            $this->postService->deletePost($id);
            return redirect()
                ->route('posts.index')
                ->with('success', '文章刪除成功');
        } catch (Exception $e) {
            return back()->with('error', '文章刪除失敗: ' . $e->getMessage());
        }
    }
} 