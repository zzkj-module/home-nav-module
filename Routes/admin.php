<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

// 首页导航图标
Route::get('homenav/list', 'HomeNavController@list');
Route::get('homenav/ajaxList', 'HomeNavController@ajaxList');
Route::any('homenav/edit', 'HomeNavController@edit');
Route::post('homenav/del', 'HomeNavController@del');
Route::post('homenav/show', 'HomeNavController@show');
