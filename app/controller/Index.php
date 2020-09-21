<?php
namespace app\controller;
use app\BaseController;
use think\facade\View;
use app\model\User as UserModel;

class Index extends BaseController
{
    public function index()
    {
        //return \request()->domain();
        return redirect(url('/login'));
    }
    public function test(){
        return 'Index test';
    }
}

