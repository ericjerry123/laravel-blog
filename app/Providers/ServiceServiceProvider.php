<?php

namespace App\Providers;

use App\Services\Interfaces\PostServiceInterface;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\PostService;
use App\Services\TagService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * 註冊服務
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(TagServiceInterface::class, TagService::class);
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