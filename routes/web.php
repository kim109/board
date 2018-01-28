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
    return view('home');
});

Auth::routes();

// 메신저에서 로그인
Route::post('/entry', 'AuthController@entry');

// 홈
Route::prefix('home')->group(function () {
    Route::view('/', 'home')->name('home');
    Route::get('summary-articles', 'HomeController@summary');
    Route::get('list', 'HomeController@list');
});

// 세미나 소식
Route::prefix('seminars')->group(function () {
    Route::get('/', 'SeminarController@index')->name('seminars.index');
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
// 사용문의 Q&A
Route::resource('supports', 'SupportController')->middleware('auth');
Route::get('supports/{id}/answer', function ($id) {
    $article = \App\Support::findorFail($id);
    return view('supports.answer', ['article' => $article]);
})->middleware(['auth', 'admin']);
Route::post('supports/{id}/answer', 'SupportController@answer')->middleware(['auth', 'admin']);

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
