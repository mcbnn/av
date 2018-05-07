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

Route::resource('params', 'ParamsController');
Auth::routes();
Route::get('/home', 'ParamsController@index')->name('home');
Route::get('/', 'ParamsController@index')->name('home');
Route::get('/parser/checkurl', 'ParserController@checkUrlLimitCount');
Route::get('/parser/{id}', 'ParserController@parserUrl')->name('parser-url');
Route::get('/parser/test/{id}', 'ParserController@test')->name('parser-test');
Route::get('/parser', 'ParserController@parser');
Route::get('/contents/{param_id}', 'ContentsController@index')->name('contents');
Route::get('/init', 'ParserController@init');
Route::get('/test/mail', function () {
    Mail::send('emails.test', ['data' => ['fff']], function ($message) {
        $message->from('mcbnn123@gmail.com', date('d.m h:i:s'));
        $message->to('mc_bnn@mail.ru');
    });
});
