<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
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

Auth::routes();

Route::get('/', 'PostController@index');

Route::resource('posts', 'PostController');

Route::get('/home', 'PostController@index')->name('home');
Route::get('/mypage', 'PostController@mypage')->name('mypage')->middleware('auth');
Route::get('/create/select', 'PostController@select')->name('create.create_select');
Route::get('/create/post', 'PostController@post')->name('create.create_post');
Route::get('/create/product', 'PostController@product')->name('create.create_product');

Route::post('/create/post_conf', 'PostController@postConf')->name('create.post_conf');

Route::get('/user/edit', 'PostController@edit')->name('user.user_edit');


Route::get('/user/edit', 'PostController@editAccount')->name('user.edit');


Route::post('/user/edit_conf', 'PostController@editConf')->name('user.edit_conf');
Route::get('/user/delete_conf', 'PostController@deleteConf')->name('user.delete_conf');

Route::post('/user/edit_conf', 'PostController@editConf')->name('user.edit_conf');

Route::post('/user/update', 'PostController@update')->name('user.update');



Route::post('/create/product_conf', 'PostController@productConf')->name('create.product_conf');
Route::post('/product/store', 'PostController@productStore')->name('product.store');

Route::get('/post_search', 'PostController@search')->name('search.post_search');

Route::get('/mypost/{id}', 'PostController@myPost')->name('mypost.show');

Route::get('/post/{id}/edit', 'PostController@edit')->name('post.edit');
Route::post('/post/{id}/edit_conf', 'PostController@editConf')->name('post.edit_conf');

// 投稿詳細ページ
Route::get('/post/{id}', 'PostController@show')->name('posts.show');

Route::get('/product/{id}', 'PostController@show')->name('product.show');
Route::get('/myproduct/{id}', 'PostController@myProduct')->name('myproduct.show');


// ブックマーク保存（POST）
Route::post('/post/{id}/bookmark', 'BookmarkController@store')->name('bookmark.store');

// 投稿削除確認画面
Route::get('/post/{id}/delete_conf', 'PostController@deletePostConf')->name('post_delete_conf');

// 投稿削除処理（DELETE）
Route::delete('/post/{id}', 'PostController@destroy')->name('post.delete');

// コメント機能
Route::post('/post/{id}/comment', [CommentController::class, 'store'])->name('comments.store');

// ブックマーク機能
Route::post('/bookmark/{id}', [BookmarkController::class, 'store'])->name('bookmark.store');
// ブックマーク一覧
Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmark_list');


// Laravel 6 向けのルート定義に書き換え
Route::prefix('reset')->group(function () {
    Route::get('/', [UsersController::class, 'requestResetPassword'])->name('reset.form');
    Route::post('/send', [UsersController::class, 'sendResetPasswordMail'])->name('reset.send');
    Route::get('/send/complete', [UsersController::class, 'sendCompleteResetPasswordMail'])->name('reset.send.complete');
    Route::get('/password/edit', [UsersController::class, 'resetPassword'])->name('reset.password.edit');
    Route::post('/password/update', [UsersController::class, 'updatePassword'])->name('reset.password.update');
});
Route::get('/reset-test', function () {
    return 'Reset Route OK';
});

//管理ユーザーページ
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/ownerpage', [AdminController::class, 'index'])->name('ownerpage');
    Route::get('/user_list', [AdminController::class, 'userList'])->name('user.list');
    Route::get('/post_list', [AdminController::class, 'postList'])->name('post.list');
    Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/admin/posts/{post}', [AdminController::class, 'showPost'])->name('posts.show');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});


//購入製品ページ（あるだけ）
Route::get('/purchase_list', function () {
    return view('purchase_list');
})->name('purchase_list');
