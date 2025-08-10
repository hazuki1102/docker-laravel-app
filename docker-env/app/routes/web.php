<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostLikeController;
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

// ホーム（一覧）
Route::get('/', 'PostController@index')->name('posts.index');
Route::get('/home', function () {
    return redirect()->route('posts.index');
})->name('home');

// 投稿詳細（Post）
Route::get('/post/{id}', 'PostController@show')->name('posts.show');

// 素材詳細（Product）
Route::get('/product/{id}', 'PostController@showProduct')->name('product.show');

// 検索
Route::get('/post_search', 'PostController@search')->name('search.post_search');

// 購入製品（空の画面だけ）
Route::get('/purchase_list', function () {
    return view('purchase_list');
})->name('purchase_list');


Route::middleware('auth')->group(function () {

    // マイページ
    Route::get('/mypage', 'PostController@mypage')->name('mypage');

    // 作成フロー（投稿/素材）
    Route::get('/create/select', 'PostController@select')->name('create.create_select');
    Route::get('/create/post', 'PostController@post')->name('create.create_post');
    Route::get('/create/product', 'PostController@product')->name('create.create_product');

    Route::post('/create/post_conf', 'PostController@postConf')->name('create.post_conf');
    Route::post('/create/product_conf', 'PostController@productConf')->name('create.product_conf');
    Route::post('/product/store', 'PostController@productStore')->name('product.store');

    // 自分の投稿/素材詳細
    Route::get('/mypost/{id}', 'PostController@myPost')->name('mypost.show');
    Route::get('/myproduct/{id}', 'PostController@myProduct')->name('myproduct.show');

    // 投稿の編集
    Route::get('/post/{id}/edit', 'PostController@edit')->name('post.edit');
    Route::post('/post/{id}/edit_conf', 'PostController@editConf')->name('post.edit_conf');

    // 投稿削除
    Route::get('/post/{id}/delete_conf', 'PostController@deletePostConf')->name('post_delete_conf');
    Route::delete('/post/{id}', 'PostController@destroy')->name('post.delete');

    // コメント
    Route::post('/post/{id}/comment', 'CommentController@store')->name('comments.store');

    // ブックマーク
    Route::post('/post/{id}/bookmark', 'BookmarkController@store')->name('bookmark.store');
    Route::get('/bookmarks', 'BookmarkController@index')->name('bookmark_list');

    // いいね
    Route::post('/posts/{post}/like', 'PostLikeController@toggle')->name('posts.like');

    // アカウント編集
    Route::get('/user/edit', 'PostController@editAccount')->name('user.edit');
    Route::post('/user/edit_conf', 'PostController@editConf')->name('user.edit_conf');
    Route::post('/user/update', 'PostController@update')->name('user.update');

    // アカウント削除（確認→実行）
    Route::get('/user/delete_conf', 'PostController@deleteConf')->name('user.delete_conf');
    Route::delete('/user', 'PostController@destroyAccount')->name('user.destroy');
});

/**
 * パスワードリセット
 */
Route::prefix('reset')->group(function () {
    Route::get('/', 'UsersController@requestResetPassword')->name('reset.form');
    Route::post('/send', 'UsersController@sendResetPasswordMail')->name('reset.send');
    Route::get('/send/complete', 'UsersController@sendCompleteResetPasswordMail')->name('reset.send.complete');
    Route::get('/password/edit', 'UsersController@resetPassword')->name('reset.password.edit');
    Route::post('/password/update', 'UsersController@updatePassword')->name('reset.password.update');
});
Route::get('/reset-test', function () {
    return 'Reset Route OK';
});

// 管理者ページ
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/ownerpage', 'AdminController@index')->name('ownerpage');

    Route::get('/user_list', 'AdminController@userList')->name('admin.user_list');
    Route::get('/post_list', 'AdminController@postList')->name('admin.post_list');

    Route::get('/admin/users/{user}', 'AdminController@showUser')->name('admin.users.show');
    Route::get('/admin/posts/{post}', 'AdminController@showPost')->name('admin.posts.show');

    Route::delete('/admin/users/{user}', 'AdminController@destroyUser')->name('admin.users.destroy');
});