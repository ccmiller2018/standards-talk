<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PostController::class, 'index']);
Route::get('/home', [PostController::class, 'index']);

//authentication
Route::get('/logout', [UserController::class, 'logout']);
Route::group(['prefix' => 'auth'], function () {
    Auth::routes();
});

// check for logged in user
Route::middleware(['auth'])->group(function () {
    // show new post form
    Route::get('new-post', [PostController::class, 'create']);
    // save new post
    Route::post('new-post', [PostController::class, 'store']);
    // edit post form
    Route::get('edit/{slug}', [PostController::class, 'edit']);
    // update post
    Route::post('update', [PostController::class, 'update']);
    // delete post
    Route::get('delete/{id}', [PostController::class, 'destroy']);
    // add comment
    Route::post('comment/add', [CommentController::class, 'store']);
});

// display single post
Route::get('/{slug}', [PostController::class, 'show'])->where('slug', '[A-Za-z0-9-_]+');
