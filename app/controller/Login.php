<?php
namespace app\controller;

use app\model\Auth as AuthMode;
use think\facade\Validate;
use think\Request;
use think\Session;
use think\View;

class Login
{
    protected $toast = 'public/toast';
    function index(){
        return view();
    }
    function check(Request $request){
        $data = $request->param();
        //初始化，错误集合
        $errors = [];
        //定义验证规则
        $validata = Validate::rule([
            'name|管理员名' => 'unique:auth,name^password',//表示name和password相互绑定上，看做一个整体，
            //当数据库里拥有与前端输入的相同的账户与相匹配的密码，就没有达到唯一性要求，返回false
            /*举例，如果输入都正确，因为数据库里的一条记录的用户名和密码与前端输入的用户名与密码匹配上，就构不成唯一，返回false
              如果密码输错，虽然与数据库里某条记录拥有相同的用户名但是密码匹配不上所以达到唯一要求，返回true
            */
        ]);
        //验证
        $res = $validata->check([
            'name'     =>  $data['name'],
            'password' =>  sha1($data['password']),
        ]);
        //dd($res);
        //只有用户名和密码互相匹配上时达不到唯一性，$res才为false，所以其他情况（true情况）均为验证不通过
        if($res){
            $errors[] = '用户名或密码错误！';
        }
        //验证码助手函数验证
        if(!captcha_check($data['code'])){
            $errors[] = '验证码错误！';
        }
        if(!empty($errors)){
            return \view($this->toast,[
                'infos'       =>        $errors,
                //'url_path'    =>        'javascript:history.back(-1);',
                'url_path'    =>        url('/login'),//因为要刷新令牌，所以不能用JS返回方法！
                'url_text'    =>        '返回',
            ]);
        }else{
            //通过session助手函数写入session中
            \session('admin',$data['name']);
            return redirect('/user');
        }
    }
    //登出
    public function logout(){
        session('admin', null);//清除session
        return redirect('/login');
    }

}