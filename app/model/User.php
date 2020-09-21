<?php
namespace app\model;
use think\Model;

class User extends Model
{
    //gender 搜索器,重新封装SQL语句
    //前端提交表单后,接收到gender后会触发搜索器，将原sql语句替换重写
    //注意这里搜索器的$value是前端get传值的值，比如？gender=男，$value值就是男
    public function searchGenderAttr($query, $value)
    {
        return $value ? $query->where('gender', $value) : '';
    }
    //username 搜索器
    public function searchUsernameAttr($query, $value) {
        return $value ? $query->where('username', 'like', '%'.$value.'%') : '';
    }
    //email 搜索器
    public function searchEmailAttr($query, $value) {
        return $value ? $query->where('email', 'like', '%'.$value.'%') : '';
    }
    //create_time 搜索器
    public function searchCreateTimeAttr($query, $value) {//这里的$value 由前端点击表头‘创建时间’提供，有desc和‘’空字符串两种情况
        return $value ? $query->order('create_time', $value) : '';
    }

    //status 获取器
    //将从数据库里提取出来的数据集中的status字段替换成我们定义好的内容
    //此时页面status字段输出的就是正常和待审核而不是1和0了
    //所以注意模板做status判断的时候不是判断0或1了！
    public function getStatusAttr($value)
    {
        $status = [1=>'正常', 0=>'待审核'];
        return $status[$value];
    }

    //badge 获取器（虚拟的不存在的字段，会加入user表成为它的字段，第二个参数的data可以获取到user表的所有数据）
    //根据审核状态的不同显示不同的字体颜色小技巧，具体看讲义07
    //这个badge输出在：class="badge badge-{$obj.badge}
    public function getBadgeAttr($value, $data)//$data可以获取到user表的所有数据！
    {
        //$data就是查询语句的数据集，即$data['id'],$data['username']可以获取到id和username相应的值
        return $data['status'] ? 'success' : 'warning';
    }

    //一对一Hobby关联
    function hobby(){
        return $this->hasOne('Hobby');
    }
}