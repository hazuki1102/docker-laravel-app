<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('home');
});

Route::resource('posts', PostController::class);



Auth::routes();

Route::get('/home', [PostController::class, 'index'])->name('home');
Route::get('/mypage', [PostController::class, 'mypage'])->name('mypage')->middleware('auth');
Route::get('/create/select', [PostController::class, 'select'])->name('create.create_select');
Route::get('/create/post', [PostController::class, 'post'])->name('create.create_post');
Route::get('/create/product', [PostController::class, 'product'])->name('create.create_product');


Route::post('/create/post_conf', [PostController::class, 'postConf'])->name('create.post_conf');

Route::get('/create/product_conf', [PostController::class, 'productConf'])->name('create.product_conf');

Route::get('/user/edit/{id}', [PostController::class, 'edit'])->name('user.user_edit');


Route::post('/user/edit_conf', [PostController::class, 'editConf'])->name('user.edit_conf');
Route::get('/user/delete_conf', [PostController::class, 'deleteConf'])->name('user.delete_conf');
