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

Route::view('/', 'home', ['title'=>'图片滤镜']);

Route::view('/image', 'home', ['title'=>'图片滤镜']);
Route::view('/image/home', 'home', ['title'=>'图片滤镜']);
