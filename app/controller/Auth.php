<?php
declare (strict_types = 1);
namespace app\controller;
use app\model\Auth as AuthModel;
use app\model\Role as RoleModel;
use think\Request;
use app\middleware\Auth as AuthMiddleware;


/*管理员权限设置*/
class Auth
{
    protected $middleware = [AuthMiddleware::class];
    protected $toast = 'public/toast';
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //关联预载入：with()把附表也加进来作为主表的一个字段(类型为数组)，withSearch()是用于触发模型搜索器用的（详见Auth模型）
        $list = AuthModel::with(['role'])->withSearch(['name'],[
            'name'      =>  \request()->param('name'),//name搜索器方法在Auth模型里
        ])->paginate([
            'list_rows' => 5,
            //配置 paginate方法的query 参数来保存搜索条件，否则点击其他页面时地址栏链接问号？后面的参数会丢失
            'query' => \request()->param()
        ]);
        //return json($list);
        //给list对象分配一个roles字段用来存储用户的权限角色
        //因为权限role数组在$list数组里面，所以要遍历，下面的$r->type就是role的type字段
        foreach ($list as $key=>$obj) {//每个$obj就是一个用户，每个用户的role字段又是一个二维数组，
        //第二层遍历
            foreach ($obj->role as $r) {//遍历role数组，每个$r就是role数组中的第二维数组，$r->type就是该数组里面的type字段
                $obj->roles .= $r->type.' | ';//$obj->roles是自己创建的一个新字段,再把拥有的权限拼接起来
            }
        //去除尾部的竖线 ‘|’ ：substr(数据，0，-1)
            $obj->roles = trim(substr(trim($obj->roles), 0, -1));
        }
        //此时输出json($list)会发现多了一个字段roles
        //return json($list);
        return \view('index',[
            'list' => $list,
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('',[
            'list'   =>  RoleModel::select(),
        ]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //添加新的管理员以及设置他的权限
        $data = $request->param();
        $data['password'] = sha1($data['password']);
        //新增数据写入Auth管理员表和Access中间表，并返回新增的ID
        $id = AuthModel::create($data)->getData('id');
        //多对多关联新增
        //这里的$id是新增的Auth管理员表的id，$data['role']是前端传过来的，值是role角色表的id，例如$data['role'] = array(0 => "3",1 => "4")
        //通过多对多关联role()方法将auth_id和roel_id写入到Access中间表中
        AuthModel::find($id)->role()->saveAll($data['role']);//role加 () 表示新增

        return $id ? view($this->toast, [
            'infos' => ['恭喜，添加管理员成功！'],
            'url_text' => '去管理员首页',
            'url_path' => url('/auth')
        ]) : '添加失败！';
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
