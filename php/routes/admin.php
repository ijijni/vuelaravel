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
//获取配置
Route::post('base/getConfigs','BaseController@getConfigs');
//获取验证码
Route::get('base/getVerify','BaseController@getVerify');
// 【基础】登录
Route::post('base/login','BaseController@login');
// 【基础】记住登陆
Route::post('base/relogin','BaseController@relogin');
// 【基础】退出登陆
Route::post('base/logout','BaseController@logout');
// 【基础】修改密码
Route::post('base/setInfo','BaseController@setInfo');
/*
----资源路由----
*/
//用户组
Route::resource('rules', 'RulesController');//权限规则
Route::resource('groups', 'GroupsController');//用户组
Route::resource('users', 'UsersController');//用户管理
Route::resource('menus', 'MenusController');//菜单
Route::resource('structures', 'StructuresController');//部门
Route::resource('posts', 'PostsController');//岗位

// // 保存系统配置
Route::post('systemConfigs','SystemConfigsController@save');
// // 【规则】批量删除
Route::post('rules/deletes','RulesController@deletes');
// // 【规则】批量启用/禁用
Route::post('rules/enables','RulesController@enables');
// // 【用户组】批量删除
Route::post('groups/deletes','GroupsController@deletes');
// // 【用户组】批量启用/禁用
Route::post('groups/enables','GroupsController@enables');
// // 【用户】批量删除
Route::post('users/deletes','UsersController@deletes');
// // 【用户】批量启用/禁用
Route::post('users/enables','UsersController@enables');
// // 【菜单】批量删除
Route::post('menus/deletes','MenusController@deletes');
// // 【菜单】批量启用/禁用
Route::post('menus/enables','MenusController@enables');
// // 【组织架构】批量删除
Route::post('structures/deletes','StructuresController@deletes');
// // 【组织架构】批量启用/禁用
Route::post('structures/enables','StructuresController@enables');
// // 【岗位】批量删除
Route::post('posts/deletes','PostsController@deletes');
// // 【岗位】批量启用/禁用
Route::post('posts/enables','PostsController@enables');



