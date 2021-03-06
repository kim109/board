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


// 로그인
Auth::routes();
Route::post('login', 'AuthController@login')->middleware('guest');
Route::post('entry', 'AuthController@entry');

// 홈
Route::view('/', 'home')->name('home');
Route::prefix('home')->group(function () {
    Route::get('popularity', 'HomeController@popularity');
    Route::get('list', 'HomeController@list');
});

// 치카 지식인
Route::prefix('qna')->group(function () {
    Route::view('/', 'qna.list')->name('qna.index');
    Route::get('categories', 'QnAController@category');
    Route::get('popularity', 'QnAController@popularity');
    Route::get('list', 'QnAController@list');

    Route::get('{id}', 'QnAController@show')->name('qna.show');

    Route::get('create', 'QnAController@create')->name('qna.create');
    Route::post('/', 'QnAController@store');

    Route::get('{id}/answer', 'QnAController@answer')->name('qna.answer');
    Route::post('{id}/answer', 'QnAController@storeAnswer');
    Route::delete('{id}/answers/{answer}', 'QnAController@destroyAnswer');

    Route::get('{id}/edit', 'QnAController@edit')->name('qna.edit');
    Route::patch('{id}', 'QnAController@update');

    Route::delete('{id}', 'QnAController@destroy');
});


// 치카 컬럼
Route::prefix('columns')->group(function () {
    Route::get('/', 'ColumnController@index')->name('columns.index');
    Route::get('categories', 'ColumnController@category');
    Route::get('popularity', 'ColumnController@popularity');
    Route::get('list', 'ColumnController@list');

    Route::get('{id}', 'ColumnController@show')->name('columns.show');

    Route::get('create', 'ColumnController@create')->name('columns.create');
    Route::post('/', 'ColumnController@store');

    Route::get('{id}/edit', 'ColumnController@edit')->name('columns.edit');
    Route::patch('{id}', 'ColumnController@update');

    Route::delete('{id}', 'ColumnController@destroy');
});

// 세미나 소식
Route::prefix('seminars')->group(function () {
    Route::view('/', 'seminars.list')->name('seminars.index');
    Route::get('categories', 'SeminarController@category');
    Route::get('list', 'SeminarController@list');

    Route::get('{id}', 'SeminarController@show')->name('seminars.show');

    Route::get('create', 'SeminarController@create')->name('seminars.create');
    Route::post('/', 'SeminarController@store');

    Route::get('{id}/edit', 'SeminarController@edit')->name('seminars.edit');
    Route::patch('{id}', 'SeminarController@update');

    Route::delete('{id}', 'SeminarController@destroy');
});

// 공지사항
Route::prefix('notices')->group(function () {
    Route::get('/', 'NoticeController@index')->name('notices.index');
    Route::get('list', 'NoticeController@list');

    Route::get('{id}', 'NoticeController@show')->name('notices.show');

    Route::get('create', 'NoticeController@create')->name('notices.create');
    Route::post('/', 'NoticeController@store');

    Route::get('{id}/edit', 'NoticeController@edit')->name('notices.edit');
    Route::patch('{id}', 'NoticeController@update');

    Route::delete('{id}', 'NoticeController@destroy');
});

// 자유게시판
Route::resource('freeboards', 'FreeboardController')->middleware('auth');

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

// 메신저에 표시될 요약 페이지
Route::prefix('summary')->group(function () {
    Route::view('/', 'summary')->name('summary.index')->middleware('auth');
    Route::get('list', 'SummaryController@list');
    Route::get('jisiklist', 'SummaryController@jisiklist');
});