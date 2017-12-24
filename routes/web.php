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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/summary-articles', 'HomeController@summary');

// 메신저에서 로그인
Route::post('/entry', 'AuthController@entry');

// 공지사항
// Route::resource('notices', 'NoticeController')->middleware('auth');
Route::prefix('notices')->group(function () {
    Route::get('/', 'NoticeController@index')->name('notices.index');
    Route::get('list', 'NoticeController@list');

    Route::get('/{id}', 'NoticeController@show');

    Route::get('create', 'NoticeController@create')->name('notices.create');
    Route::post('/', 'NoticeController@store');
    
    Route::delete('/{id}', 'NoticeController@destroy');
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
