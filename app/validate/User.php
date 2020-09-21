<?php
declare (strict_types = 1);
namespace app\validate;
use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'username|用户名' => 'require|min:2|max:10|chsDash|unique:user',
        '__token__' => 'token',
        'password|密码' => 'require|min:6',
        //confirm:password：与password密码框值相关联，必须值相同
        'passwordnot|密码确认' => 'require|confirm:password',
        'email|邮箱' => 'require|email|unique:user',
        'agree|协议' => 'require|accepted',

        //requireWith:newpassword：当填写新密码时，新密码确认不能为空，即与新密码框关联
        'newpasswordnot|新密码确认'=> 'requireWith:newpassword|min:6|confirm:newpassword',
    ];
	//引入场景验证
    protected $scene = [//这里的insert和edit只是取别名而已可以随便取，验证器引用时需要用到该别名
        'insert' => ['username', 'email', 'password', 'passwordnot',
            'agree', '__token__'],
        'edit' => ['__token__', 'newpasswordnot']
    ];
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        /*
        'username.require' => '用户名不得为空~',
        'username.min' => '用户名不得小于 2 位~',
        'username.max' => '用户名不得大于 10 位~',
        'username.chsDash' => '用户名只能是汉字、字母、数字或下划线以及破折号~',
        'username.unique' => '用户名已存在~',
        'password.require' => '密码不得为空~',
        'password.min' => '密码不得小于 6 位~',*/
        'passwordnot.require' => '密码确认不得为空~',
        'passwordnot.confirm' => '密码确认和密码不一致~',
        'newpasswordnot.requireWith' => '新密码确认不得为空~',
        'newpasswordnot.confirm' => '新密码确认和新密码不一致~',
        /*'email.require' => '电子邮件不得为空~',
        'email.email' => '电子邮件格式不正确~',
        'email.unique' => '电子邮件已存在~',
        'agree.require' => '必须确认协议~',
        'agree.accepted' => '必须认同协议~'*/
    ];
}
