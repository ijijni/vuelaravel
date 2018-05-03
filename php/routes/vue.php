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
  //ueditor统一处理后台
Route::match(['get', 'post'], 'ueditor/index', 'UeditorController@index');



      //文章编辑
Route::get('article/init','ArticleController@init');//文章列表
Route::post('article/savetemplate','ArticleController@savetemplate');//保存文章
Route::delete('article/delete/{id}','ArticleController@delete');//删除文章
Route::put('article/update/{id}','ArticleController@update');//更新文章