<?php

namespace App\Providers;

use App\Models\{
    Category,
    Post,
    Tag
};
use App\Services\Interfaces\{
    AuthServiceInterface,
    CategoryServiceInterface,
    CommentLikeServiceInterface,
    CommentServiceInterface,
    PostServiceInterface,
    TagServiceInterface
};
use App\Repositories\Interfaces\{
    AuthRepositoryInterface,
    CategoryRepositoryInterface,
    CommentLikeRepositoryInterface,
    CommentRepositoryInterface,
    PostRepositoryInterface,
    TagRepositoryInterface
};
use App\Services\{
    AuthService,
    CategoryService,
    DebugService,
    PostService,
    TagService
};
use App\Services\Comment\{
    CommentLikeService,
    CommentService
};
use App\Repositories\{
    AuthRepository,
    CategoryRepository,
    PostRepository,
    TagRepository
};
use App\Repositories\Comment\{
    CommentLikeRepository,
    CommentRepository
};
use App\Observers\{
    CategoryObserver,
    PostCategoryObserver,
    PostTagObserver,
    TagObserver
};

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 註冊應用服務
     */
    public function register(): void
    {
        // 註冊 Repository
        $this->registerRepositories();

        // 註冊 Services
        $this->registerServices();

        // 註冊 Singletons
        $this->registerSingletons();
    }

    /**
     * 註冊 Repositories
     */
    private function registerRepositories(): void
    {
        // 文章相關 Repositories
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // 評論相關 Repositories
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(CommentLikeRepositoryInterface::class, CommentLikeRepository::class);

        // 認證相關 Repositories
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    }

    /**
     * 註冊服務
     */
    private function registerServices(): void
    {
        // 文章相關 Services
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(TagServiceInterface::class, TagService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);

        // 評論相關 Services
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(CommentLikeServiceInterface::class, CommentLikeService::class);

        // 認證相關 Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * 註冊 Singletons
     */
    private function registerSingletons(): void
    {
        // 註冊 DebugService 單例
        $this->app->singleton(DebugService::class, function ($app) {
            return new DebugService();
        });
    }

    /**
     * 引導應用服務
     */
    public function boot(): void
    {
        // 註冊模型 Observers
        Post::observe(PostTagObserver::class);
        Post::observe(PostCategoryObserver::class);
        Tag::observe(TagObserver::class);
        Category::observe(CategoryObserver::class);
    }
}
