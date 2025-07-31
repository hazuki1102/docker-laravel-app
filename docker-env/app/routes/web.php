<?php

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


Route::post('/user/edit_conf', 'PostController@editConf')->name('user.edit_conf');
Route::get('/user/delete_conf', 'PostController@deleteConf')->name('user.delete_conf');

Route::post('/user/edit_conf', 'PostController@editConf')->name('user.edit_conf');

Route::post('/user/update', 'PostController@update')->name('user.update');



Route::post('/create/product_conf', 'PostController@productConf')->name('create.product_conf');
Route::post('/product/store', 'PostController@productStore')->name('product.store');

Route::get('/post_search', 'PostController@search')->name('search.post_search');



