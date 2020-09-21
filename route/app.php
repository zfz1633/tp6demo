<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


//后台组
Route::group(function () {
//用户模块资源路由
    Route::resource('/user', 'User');
//权限模块资源路由
    Route::resource('/auth', 'Auth');
})->middleware(function ($request, \Closure $next){//路由中间件闭包方式，如果$_SESSION['name']不存在，则跳转到login页面
    if (!session('?admin')) {// ?admin ：判断admin是否存在
        return redirect('/login');
    }
    return $next($request);
});


//登录模块路由
Route::group(function (){
   Route::get('/login','Login/index')
       ->middleware(function ($request, \Closure $next){
           if (session('?admin')) {
               return redirect('/user');//如果session已存在，直接跳转到首页
           }
           return $next($request);
       });
   Route::post('/login_check','Login/check');
   Route::get('logout','Login/logout');
});

Route::get('test','User/test');