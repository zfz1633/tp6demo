<?php
use app\ExceptionHandle;
use app\Request;

// 容器Provider定义文件
return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class,
    //注册自定义的分页类
    'think\Paginator'        => 'app\common\Bootstrap',
];
