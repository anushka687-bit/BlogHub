<?php

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing & Public Blog Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/search', [BlogController::class, 'search'])->name('blogs.search');
Route::get('/blogs/category', [BlogController::class, 'byCategory'])->name('blogs.category');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Blog CRUD
    Route::get('/my-blogs', [BlogController::class, 'myBlogs'])->name('my-blogs');
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    Route::patch('/blogs/{blog}/publish', [BlogController::class, 'publish'])->name('blogs.publish');

    // Like
    Route::post('/blogs/{blog}/like', [LikeController::class, 'toggle'])->name('blogs.like');

    // Comment
    Route::post('/blogs/{blog}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Public Blog Detail (must be registered AFTER all literal /blogs/* routes)
|--------------------------------------------------------------------------
*/

Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-block', [AdminUserController::class, 'toggleBlock'])->name('users.toggle-block');

    Route::get('/blogs', [AdminBlogController::class, 'index'])->name('blogs.index');
    Route::delete('/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('blogs.destroy');
    Route::post('/blogs/{id}/restore', [AdminBlogController::class, 'restore'])->name('blogs.restore');

    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';