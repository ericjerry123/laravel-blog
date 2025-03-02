<?php

use App\Http\Controllers\Post\LikeController as PostLikeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\ViewController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Comment\CommentLikeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 文章相關路由
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// 文章點贊路由
Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle'])->name('posts.toggle-like');

// 文章閱讀計數路由
Route::post('/posts/{post}/increment-views', [ViewController::class, 'increment'])->name('posts.increment-views');

// 評論相關路由
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// 評論點贊相關路由
Route::prefix('comments')->group(function () {
    Route::post('{comment}/toggle-like', [CommentLikeController::class, 'toggle'])->middleware('auth')->name('comments.toggle-like');
    Route::get('{comment}/likes-count', [CommentLikeController::class, 'count'])->name('comments.likes-count');
});

// 身份驗證路由
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.view');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.view');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
