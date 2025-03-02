<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait LogsDebugInfo
{
    /**
     * 檢查是否應該記錄調試信息
     *
     * @return bool
     */
    protected function shouldLogDebug(): bool
    {
        return config('app.debug') && config('logging.debug.enable_debug_logs', false);
    }
    
    /**
     * 檢查是否應該記錄集合詳細信息
     *
     * @return bool
     */
    protected function shouldLogCollections(): bool
    {
        return $this->shouldLogDebug() && config('logging.debug.log_collections', false);
    }

    /**
     * 記錄集合的基本信息
     *
     * @param string $message 日誌前綴信息
     * @param Collection $collection 要記錄的集合
     * @param string $countField 計數字段名稱
     * @param array $fields 要記錄的字段
     * @return void
     */
    protected function logCollectionInfo(string $message, Collection $collection, string $countField = null, array $fields = [])
    {
        if (!$this->shouldLogDebug()) {
            return;
        }
        
        Log::info($message . ': ' . $collection->count());
        
        if ($this->shouldLogCollections() && !empty($fields)) {
            foreach ($collection as $item) {
                $logMessage = '';
                foreach ($fields as $field) {
                    $logMessage .= "{$field}: " . ($item->{$field} ?? 'N/A') . ", ";
                }
                
                if ($countField && isset($item->{$countField})) {
                    $logMessage .= "數量: {$item->{$countField}}";
                }
                
                Log::info(rtrim($logMessage, ', '));
            }
        }
    }
    
    /**
     * 記錄標籤相關信息
     *
     * @param Collection $tags 標籤集合
     * @return void
     */
    protected function logTagsInfo(Collection $tags)
    {
        $this->logCollectionInfo('熱門標籤數量', $tags, 'posts_count', ['name']);
    }
    
    /**
     * 記錄分類相關信息
     *
     * @param Collection $categories 分類集合
     * @return void
     */
    protected function logCategoriesInfo(Collection $categories)
    {
        $this->logCollectionInfo('分類數量', $categories, 'posts_count', ['name', 'color']);
    }
} 