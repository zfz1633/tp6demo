<?php
namespace app\model;
use think\Model;

/*管理员表*/
class Auth extends Model
{
    //多对多关联
    function role(){
        return $this->belongsToMany('Role','Access');
    }
    //name 搜索器
    public function searchNameAttr($query, $value)
    {
        return $value ? $query->where('name', 'like', '%'.$value.'%') : '';
    }
}