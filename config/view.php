<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型使用Think
    'type'          => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'     => 1,
    // 模板目录名
    'view_dir_name' => 'view',
    // 模板后缀
    'view_suffix'   => 'html',
    // 模板文件名分隔符
    'view_depr'     => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'     => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'       => '}',
    // 标签库标签开始标记
    'taglib_begin'  => '{',
    // 标签库标签结束标记
    'taglib_end'    => '}',

    // 模版替换输出
    //\request()->domain()指向根目录,开8000根目录就是D:\phpstudy_pro\WWW\tp6demo\public
    //开8000端口就是http://127.0.0.1:8000或者http://localhost:8000（域名你输入什么就是什么）
    'tpl_replace_string' => [
        '__JS__' => \request()->domain().'/static/js',//不开8000端口需要加上/tp6demo/public
        '__CSS__' => \request()->domain().'/static/css',
    ],
];
