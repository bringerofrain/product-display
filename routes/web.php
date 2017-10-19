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

Route::get('/', 'ProductController@index');
Route::post('/search','ProductController@search');
Route::get('/product/{id}','ProductController@product')->where('id', '[A-Za-z0-9\-]+');
Route::get('/initialload','ProductController@initialload');
Route::get('/admin','ProductController@admin');


//TODO Remove This
Route::get('/test', 'ProductController@test');
