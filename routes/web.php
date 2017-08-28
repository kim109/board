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

Route::resource('freeboard', 'FreeboardController');
Route::post('freeboard/{article}/comment', 'CommentController@store');
Route::patch('freeboard/{article}/comments/{comment}', 'CommentController@update')
        ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
Route::delete('freeboard/{article}/comments/{comment}', 'CommentController@destory')
        ->where(['article' => '[0-9]+', 'comment' => '[0-9]+']);
