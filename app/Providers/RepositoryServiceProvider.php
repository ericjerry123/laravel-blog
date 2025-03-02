<?php

namespace App\Providers;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\SearchableRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * 註冊服務
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        
        // 當請求 SearchableRepositoryInterface 並且上下文是 Post 時，返回 PostRepository
        $this->app->when(PostRepository::class)
            ->needs(SearchableRepositoryInterface::class)
            ->give(function () {
                return app(PostRepository::class);
            });
    }

    /**
     * 啟動服務
     *
     * @return void
     */
    public function boot()
    {
        //
    }
} 