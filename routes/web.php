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
    $categories = \App\Category::where('open', true)->get();

    return view('welcome', ['categories' => $categories]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/entry', 'AuthController@entry');

// 자유게시판
Route::resource('freeboard', 'FreeboardController');
Route::prefix('freeboard')->group(function () {
    Route::post('{article}/comment', 'CommentController@store');
    Route::patch('{article}/comments/{comment}', 'CommentController@update')
                ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
    Route::delete('{article}/comments/{comment}', 'CommentController@destroy')
                ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
});

// 중고장터
Route::resource('market', 'MarketController');
Route::prefix('market')->group(function () {
    Route::post('{article}/comment', 'CommentController@store');
    Route::patch('{article}/comments/{comment}', 'CommentController@update')
                ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
    Route::delete('{article}/comments/{comment}', 'CommentController@destroy')
                ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
});

// 첨부파일
Route::get('attachments/{id}/{md5}', 'AttachmentController@download');
Route::get('thumbnail/{id}', 'AttachmentController@thumbnail');