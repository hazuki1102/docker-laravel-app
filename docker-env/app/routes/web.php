<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductLikeController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Auth::routes();


// ホーム（一覧）
Route::get('/', [PostController::class, 'index'])->name('posts.index');

Route::get('/home', fn () => redirect()->route('posts.index'))->name('home');

// 投稿・素材の詳細
Route::get('/post/{id}',    [PostController::class, 'show'])->name('posts.show');
Route::get('/product/{id}', [PostController::class, 'showProduct'])->name('product.show');

// 検索
Route::get('/post_search', [PostController::class, 'search'])->name('search.post_search');

// 購入（ダミー）
Route::get('/purchase_list', fn () => view('purchase_list'))->name('purchase_list');

Route::middleware('auth')->group(function () {

    // カート
    Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::get('/cart_list',      [\App\Http\Controllers\CartController::class, 'index'])->name('cart.list');

    // マイページ
    Route::get('/mypage', [PostController::class, 'mypage'])->name('mypage');

    // 作成フロー（投稿/素材）
    Route::get('/create/select',   [PostController::class, 'select'])->name('create.create_select');
    Route::get('/create/post',     [PostController::class, 'post'])->name('create.create_post');
    Route::get('/create/product',  [PostController::class, 'product'])->name('create.create_product');

    Route::post('/create/post_conf',    [PostController::class, 'postConf'])->name('create.post_conf');
    Route::post('/create/product_conf', [PostController::class, 'productConf'])->name('create.product_conf');

    Route::post('/posts',           [PostController::class, 'store'])->name('posts.store');
    Route::post('/product/store',   [PostController::class, 'productStore'])->name('product.store');

    // 自分の投稿/素材詳細
    Route::get('/mypost/{id}',    [PostController::class, 'myPost'])->name('mypost.show');
    Route::post('/create/post_back', 'PostController@postBack')->name('create.post_back');

    Route::get('/myproduct/{id}', [PostController::class, 'myProduct'])->name('myproduct.show');

    // 投稿の編集
    Route::get('/post/{id}/edit',        [PostController::class, 'edit'])->name('post.edit');
    Route::post('/post/{id}/edit_conf',  [PostController::class, 'postEditConf'])->name('post.edit_conf');
    Route::put('/post/{id}',             [PostController::class, 'updatePost'])->name('post.update');

    // 素材の編集
    Route::get('/product/{id}/edit',       [PostController::class, 'editProduct'])->name('product.edit');
    Route::post('/product/{id}/edit_conf', [PostController::class, 'productEditConf'])->name('product.edit_conf');
    Route::put('/product/{id}',            [PostController::class, 'updateProduct'])->name('product.update');

    // 削除（確認→実行）
    Route::get('/post/{id}/delete_conf',    [PostController::class, 'deletePostConf'])->name('post_delete_conf');
    Route::delete('/post/{id}',             [PostController::class, 'destroy'])->name('post.delete');

    Route::get('/product/{id}/delete_conf', [PostController::class, 'deleteProductConf'])->name('product_delete_conf');
    Route::delete('/product/{id}',          [PostController::class, 'destroyProduct'])->name('product.delete');

    // コメント
    Route::post('/post/{id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // ブックマーク
    Route::post('/post/{id}/bookmark', 'BookmarkController@toggle')
        ->middleware('auth')->defaults('type', 'post')
        ->name('bookmark.store');

    Route::get('/bookmarks', 'BookmarkController@index')->name('bookmark_list');

    Route::post('/product/{id}/bookmark', 'BookmarkController@toggle')
        ->middleware('auth')->defaults('type', 'product')
        ->name('products.bookmark.store');


    // いいね（投稿 / 素材）
    Route::post('/posts/{post}/like',     [PostLikeController::class, 'toggle'])->name('posts.like');
    Route::post('/products/{product}/like',[ProductLikeController::class, 'toggle'])->name('products.like');

    // アカウント編集・削除
    Route::get('/user/edit',        [PostController::class, 'editAccount'])->name('user.edit');
    Route::post('/user/edit_conf',  [PostController::class, 'editConf'])->name('user.edit_conf');
    Route::post('/user/update',     [PostController::class, 'update'])->name('user.update');

    Route::get('/user/delete_conf', [PostController::class, 'deleteConf'])->name('user.delete_conf');
    Route::delete('/user',          [PostController::class, 'destroyAccount'])->name('user.destroy');
});


Route::prefix('reset')->group(function () {
    Route::get('/',                [UsersController::class, 'requestResetPassword'])->name('reset.form');
    Route::post('/send',           [UsersController::class, 'sendResetPasswordMail'])->name('reset.send');
    Route::get('/send/complete',   [UsersController::class, 'sendCompleteResetPasswordMail'])->name('reset.send.complete');
    Route::get('/password/edit',   [UsersController::class, 'resetPassword'])->name('reset.password.edit');
    Route::post('/password/update',[UsersController::class, 'updatePassword'])->name('reset.password.update');
});
Route::get('/reset-test', fn () => 'Reset Route OK');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/ownerpage', [AdminController::class, 'index'])->name('ownerpage');

    Route::get('/user_list', [AdminController::class, 'userList'])->name('user_list');
    Route::get('/post_list', [AdminController::class, 'postList'])->name('post_list');

    Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('admin.user_show');
    Route::get('/admin/posts/{post}', [AdminController::class, 'showPost'])->name('admin.post_show');

    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::delete('/admin/posts/{post}', [AdminController::class, 'destroyPost'])->name('admin.posts.destroy');
});
