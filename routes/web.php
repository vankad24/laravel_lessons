<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikedPostsController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ModerationPageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePageController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/users/{user}', [ProfilePageController::class, 'show'])->name('profile.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/liked', LikedPostsController::class)->name('liked');
    Route::get('/moderation', ModerationPageController::class)->middleware('check_user_role')->name('moderation.page');
});

require __DIR__.'/auth.php';

Route::post('api/comments', [CommentController::class, 'store'])->name('comments.store');
Route::apiResource('api/posts', PostController::class);
Route::post('api/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::apiResource('api/categories', CategoryController::class);
Route::apiResource('api/tags', TagController::class);

Route::prefix('moderation')->name('moderation.')->middleware(['auth'])->group(function () {
    Route::get('posts', [ModerationController::class, 'indexPosts'])->name('posts.index');
    Route::get('comments', [ModerationController::class, 'indexComments'])->name('comments.index');
    Route::post('{moderation}/decline', [ModerationController::class, 'decline'])->name('decline');
    Route::post('{moderation}/accept', [ModerationController::class, 'accept'])->name('accept');
});