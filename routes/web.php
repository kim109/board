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
Route::post('/entry', 'AuthController@entry');

// 자유게시판
Route::resource('freeboard', 'FreeboardController');
// 중고장터
Route::resource('market', 'MarketController');

// 댓글
Route::prefix('{type}/{id}/comments')->group(function () {
    Route::get('/', 'CommentController@get');
    Route::post('/', 'CommentController@store');
    Route::patch('/{comment}', 'CommentController@update');
    Route::delete('/{comment}', 'CommentController@destroy');
    Route::post('/{comment}/reply', 'CommentController@reply');
});


// 첨부파일
Route::post('attachments', 'AttachmentController@store');
Route::get('attachments/{id}/{md5}', 'AttachmentController@download');
Route::get('thumbnail/{id}', 'AttachmentController@thumbnail');
Route::delete('attachments/{id}', 'AttachmentController@remove');
