<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// CSRF Token Route
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Route to get authenticated user's information
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Register route
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login route
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// Public routes for Blog Posts
Route::get('posts', [PostController::class, 'publicIndex']);
Route::get('posts/{post_id}', [PostController::class, 'show']);
Route::get('posts/{post_id}/comments', [CommentController::class, 'index']); // Fetch comments of a post
Route::post('posts/{post_id}/comments', [CommentController::class, 'store']); // Public comment on a post
Route::post('posts/{post_id}/like', [PostController::class, 'like']);
Route::post('posts/{post_id}/dislike', [PostController::class, 'dislike']);
Route::post('comments/{comment_id}/like', [CommentController::class, 'like']);
Route::post('comments/{comment_id}/dislike', [CommentController::class, 'dislike']);

// Protected routes for Blog Posts (only for post author)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/posts', [PostController::class, 'index']); // Authenticated user's posts
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post_id}', [PostController::class, 'update']);
    Route::delete('posts/{post_id}', [PostController::class, 'destroy']);
    
    // Protected routes for Comments
    Route::put('comments/{comment_id}', [CommentController::class, 'update']);
    Route::delete('comments/{comment_id}', [CommentController::class, 'destroy']);
    Route::post('posts/{post_id}/comments/delete', [CommentController::class, 'deleteSelected']); // Delete multiple comments
});
