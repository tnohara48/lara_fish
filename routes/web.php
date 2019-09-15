<?php

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
    return view('index');
});
Route::post('/', function () {
    return view('index');
});

/* 初期画面表示 */
Route::get('/FishContrller', 'FishController@index');
Route::post('/FishContrller', 'FishController@index');

/* 商品情報一覧更新 */
Route::get('/FishContrller/action', 'FishController@action');
Route::post('/FishContrller/action', 'FishController@action');

/* 商品情報登録 */
Route::get('/FishContrller/entry_product', 'FishController@entry_product');
Route::post('/FishContrller/entry_product', 'FishController@entry_product')->name('FishContrller.entry_product');
Route::get('/FishContrller/entry_action_product', 'FishController@entry_action_product')->name('FishContrller.entry_action_product');
Route::post('/FishContrller/entry_action_product', 'FishController@entry_action_product')->name('FishContrller.entry_action_product');

/* 商品情報編集 */
Route::get('/FishContrller/edit_product', 'FishController@edit_product')->name('FishContrller.edit_product');
Route::post('/FishContrller/edit_product', 'FishController@edit_product')->name('FishContrller.edit_product');
Route::get('/FishContrller/edit_action_product', 'FishController@edit_action_product')->name('FishContrller.edit_action_product');
Route::post('/FishContrller/edit_action_product', 'FishController@edit_action_product')->name('FishContrller.edit_action_product');

/* 商品情報削除 */
Route::get('/FishContrller/delete_product', 'FishController@delete_product')->name('FishContrller.delete_product');
Route::post('/FishContrller/delete_product', 'FishController@delete_product')->name('FishContrller.delete_product');
Route::get('/FishContrller/delete_action_product', 'FishController@delete_action_product')->name('FishContrller.delete_action_product');
Route::post('/FishContrller/delete_action_product', 'FishController@delete_action_product')->name('FishContrller.delete_action_product');

/* 状態登録 */
Route::get('/FishContrller/entry_condition', 'FishController@entry_condition')->name('FishContrller.entry_condition');
Route::get('/FishContrller/entry_action_condition', 'FishController@entry_action_condition')->name('FishContrller.entry_action_condition');
Route::post('/FishContrller/entry_condition', 'FishController@entry_condition')->name('FishContrller.entry_condition');
Route::post('/FishContrller/entry_action_condition', 'FishController@entry_action_condition')->name('FishContrller.entry_action_condition');

/* 状態編集 */
Route::get('/FishContrller/edit_condition', 'FishController@edit_condition')->name('FishContrller.edit_condition');
Route::post('/FishContrller/edit_condition', 'FishController@edit_condition')->name('FishContrller.edit_condition');
Route::get('/FishContrller/edit_action_condition', 'FishController@edit_action_condition')->name('FishContrller.edit_action_condition');
Route::post('/FishContrller/edit_action_condition', 'FishController@edit_action_condition')->name('FishContrller.edit_action_condition');

/* 状態削除 */
Route::get('/FishContrller/delete_condition', 'FishController@delete_condition')->name('FishContrller.delete_condition');
Route::get('/FishContrller/delete_action_condition', 'FishController@delete_action_condition')->name('FishContrller.delete_action_condition');
Route::post('/FishContrller/delete_condition', 'FishController@delete_condition')->name('FishContrller.delete_condition');
Route::post('/FishContrller/delete_action_condition', 'FishController@delete_action_condition')->name('FishContrller.delete_action_condition');

Route::get('/FishContrller/view', 'FishController@view');

Route::get('/FishContrller/error', 'FishController@error');
Route::post('/FishContrller/error', 'FishController@error');

