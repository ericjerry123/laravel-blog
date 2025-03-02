<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Comment;

class DebugService
{
    /**
     * 是否啟用調試日誌
     *
     * @var bool
     */
    protected $enabled;
    
    /**
     * 是否記錄集合詳細信息
     *
     * @var bool
     */
    protected $logCollections;
    
    /**
     * 構造函數
     */
    public function __construct()
    {
        $this->enabled = config('app.debug') && config('logging.debug.enable_debug_logs', false);
        $this->logCollections = $this->enabled && config('logging.debug.log_collections', false);
    }
    
    /**
     * 記錄標籤相關信息
     *
     * @param Collection $tags 標籤集合
     * @return void
     */
    public function logTags(Collection $tags)
    {
        if (!$this->enabled) {
            return;
        }
        
        Log::info('熱門標籤數量: ' . $tags->count());
        
        if ($this->logCollections) {
            foreach ($tags as $tag) {
                Log::info("標籤: {$tag->name}, 文章數: {$tag->posts_count}");
            }
        }
    }
    
    /**
     * 記錄分類相關信息
     *
     * @param Collection $categories 分類集合
     * @return void
     */
    public function logCategories(Collection $categories)
    {
        if (!$this->enabled) {
            return;
        }
        
        Log::info('分類數量: ' . $categories->count());
        
        if ($this->logCollections) {
            foreach ($categories as $cat) {
                Log::info("分類: {$cat->name}, 文章數: {$cat->posts_count}, 顏色: {$cat->color}");
            }
        }
    }
    
    /**
     * 記錄文章詳情相關信息
     *
     * @param Post $post 文章對象
     * @param Collection $categories 分類集合
     * @return void
     */
    public function logPostDetails(Post $post, Collection $categories)
    {
        if (!$this->enabled) {
            return;
        }
        
        Log::info('文章詳情頁 - 分類數量: ' . $categories->count());
        
        if ($post->category) {
            Log::info("文章分類: {$post->category->name}, 顏色: {$post->category->color}");
        } else {
            Log::info('文章沒有分類');
        }
        
        Log::info('文章評論數量: ' . $post->allComments->count());
    }
    
    /**
     * 記錄文章點贊相關信息
     *
     * @param string $action 操作描述
     * @param array $data 相關數據
     * @return void
     */
    public function logPostLike(string $action, array $data)
    {
        if (!$this->enabled) {
            return;
        }
        
        Log::info($action, $data);
    }
    
    /**
     * 記錄評論點贊相關信息
     *
     * @param string $action 操作描述
     * @param array $data 相關數據
     * @return void
     */
    public function logCommentLike(string $action, array $data)
    {
        if (!$this->enabled) {
            return;
        }
        
        Log::info($action, $data);
    }
    
    /**
     * 記錄一般調試信息
     *
     * @param string $message 信息
     * @param array $context 上下文數據
     * @param string $level 日誌級別 (info, error, warning, debug)
     * @return void
     */
    public function log(string $message, array $context = [], string $level = 'info')
    {
        if (!$this->enabled && $level !== 'error') {
            return;
        }
        
        switch ($level) {
            case 'error':
                Log::error($message, $context);
                break;
            case 'warning':
                Log::warning($message, $context);
                break;
            case 'debug':
                Log::debug($message, $context);
                break;
            case 'info':
            default:
                Log::info($message, $context);
                break;
        }
    }
    
    /**
     * 記錄錯誤信息
     *
     * @param \Exception $exception 異常對象
     * @param string $message 錯誤描述
     * @param array $context 上下文數據
     * @return void
     */
    public function logError(\Exception $exception, string $message, array $context = [])
    {
        $context['exception'] = get_class($exception);
        $context['message'] = $exception->getMessage();
        $context['code'] = $exception->getCode();
        
        if ($exception instanceof \Illuminate\Database\QueryException) {
            $context['sql'] = $exception->getSql() ?? 'N/A';
        }
        
        Log::error($message, $context);
        
        // 在開發環境中記錄堆疊跟踪
        if (config('app.debug')) {
            Log::debug('Exception trace: ' . $exception->getTraceAsString());
        }
    }
} 