<?php
declare (strict_types = 1);
namespace app\middleware;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    protected $toast = 'public/toast';
    //由于判断管理员的相应权限，没有就不让使用
    public function handle($request, \Closure $next)
    {
        $auth = \app\model\Auth::where('name',session('admin'))->find();
        //return json($auth->role);//通过多对多关联role方法查看用户权限
        //遍历获取role表的uri字段
        foreach ($auth->role as $val){
            //拆分$val->uri(因为例如蜡笔小新的添加权限："User/create,User/save"，必须拆成User/create和User/save)
            foreach (explode(',',$val->uri) as $v){
                $roles[] = $v;
            }
        }
        //dd($roles);
        //得到用户当前要访问的地址uri
        $uri = $request->controller().'/'.$request->action();//这两个方法分别得到控制器名和方法名
        //dd($uri);
        //超管判断,如果不是超管则作权限判断，如果是超管，则不作判断直接放行
        if($roles[0]!='All'){
            //判断是否拥有该权限
            if(!in_array($uri,$roles)){//如果$uri整段字符串不在数组$roles中
                return \view($this->toast,[
                    'infos'       =>       ['你没有该操作权限！'],
                    'url_path'    =>        'javascript:history.back(-1);',
                    'url_text'    =>        '返回',
                ]);
            }
        }
        return $next($request);
    }
}
