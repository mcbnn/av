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
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'ParamsController@index');
Route::get('/parser/{id}', 'ParserController@parserUrl')->name('parser-url');
Route::get('/parser', 'ParserController@parser');
Route::get('/init', 'ParserController@init');