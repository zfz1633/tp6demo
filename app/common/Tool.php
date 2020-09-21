<?php
namespace app\common;


class Tool
{/*
 替换原先url地址，解决点击create_time排序时地址栏出现多个create_time参数的情况
 $urlFull  url完整地址
*/
    static function url($queryString){
        //获取完整URL
        $urlFull = request()->url(true);
        //dd(parse_url($urlFull));//分解url：parse_url()

        //判断没有传参数时，如果没有传参，则返回，有则进行下一步
        if(!isset(parse_url($urlFull)['query'])){
            return $urlFull.'?';
        }

        //截取url的get传参(即网址？问号后面的参数)：parse_url()
        $query = parse_url($urlFull)['query'];

        //转成数组，以数组的方式将所有url的问号后面的get传值都分开来,这是你会发现遇到同名索引时会覆盖前面的值，就不会出现累加情况
        //例如？id=1&name=张三&page=2，使用该分解函数后悔变成关联数组，键和值会变成数组的键值对
        parse_str($query,$urlArr);//$urlArr即由$query转成的数组
        //dd($urlArr);

        //删除$urlArr数组里的的create_time，地址栏里就不会显示两个create_time（因为原url已经有一个create_time了）
        //原URL：{:app\\common\\Tool::url('create_time')}create_time={$orderTime}我们可以看出如果不删除数组里的create_time，
        //则加上url('create_time')函数后面的create_time就会有两个，所以要删除$urlArr数组里的的create_time。
        unset($urlArr[$queryString]);//模板端写法：{:app\\common\\Tool::url('create_time')......}所以$queryString = create_time

        //再将数组转回字符串类型
        //dd(http_build_query($urlArr));
        $queryAll = http_build_query($urlArr);//http_build_query()函数会自动加入“&”符号

        //最后再合并成完整URL
        //echo request()->domain();//网站当前根目录
        //echo request()->baseUrl();//当前文件名
        echo request()->domain().request()->baseUrl().'?'.$queryAll.'&';

    }
}