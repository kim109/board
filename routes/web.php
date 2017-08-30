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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// 자유게시판
Route::resource('freeboard', 'FreeboardController');
Route::prefix('freeboard')->group(function () {
    Route::get('{article}/attachs/{attach}', 'FreeboardController@attach');
    Route::post('{article}/comment', 'CommentController@store');
    Route::patch('{article}/comments/{comment}', 'CommentController@update')
                ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
    Route::delete('{article}/comments/{comment}', 'CommentController@destroy')
                ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
});

// 중고장터
Route::resource('market', 'MarketController');
Route::prefix('market')->group(function () {
    Route::get('{article}/thumbnail', 'MarketController@thumbnail');
});
