<?php
declare (strict_types = 1);
namespace app\controller;
use app\model\User as UserModel;
use think\exception\ValidateException;
use think\facade\View;
use think\Request;
use app\validate\User as UserValidate;
use app\middleware\Auth as AuthMiddleware;

class User
{
    //protected $middleware = [AuthMiddleware::class];
    //启用中间件，支持做限制
    protected $middleware = [
        //这有包含在数组里的方法可以用
        //'Auth' => ['only' =>['index', 'test']],
        //除了数组里的这些方法之外都可以用
        AuthMiddleware::class => ['except' =>['test']],
    ];
    /*提示模板的路径*/
    protected $toast = 'public/toast';
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
//        if (empty(\session('admin'))){
//            echo 'session("admin")：没有值';
//        }else{
//            echo 'session("admin")：'.\session('admin');
//        }
        //echo \request()->domain();//返回根目录(http://127.0.0.1:8000)
        //http://localhost:8000/static/css/style.css 因为开了8000端口，所以没有public
        //排序时order写在paginate之前就是在搜索数据库时排序，也就是全部数据排序完再分页（全局排序，可使用搜索器实现），
        //写在paginate之后则是数据分页后再排序（局部排序）
        return \view('index',[
            'list' => UserModel::withSearch(['gender','username','email','create_time'/*不写到这里面不会触发搜索器！*/],[
                'gender'        =>  \request()->param('gender'),
                'username'      =>  \request()->param('username'),
                'email'         =>  \request()->param('email'),
                'create_time'   =>  request()->param('create_time'),
            ])->paginate([
                'list_rows' => 5,
                'query' => \request()->param()
            ]),//配置 query 参数来保存搜索条件，否则点击其他页面时地址栏链接问号？后面的参数会丢失
            'orderTime' => request()->param('create_time') == 'desc' ? 'asc' : 'desc',//正倒叙切换
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //跳转到添加（注册）用户界面
        return \view();//不写模板默认跳转到方法名.html，即create.html
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收create.html模板的数据写入数据库完成新增
        //return dd($request->param());
        $data = $request->param();
        try{
            validate(UserValidate::class)->batch(true)->check($data);
        }catch (ValidateException $exception){
            return \view($this->toast,[
                'infos'       =>        $exception->getError(),
                //'url_path'    =>        'javascript:history.back(-1);',
                'url_path'    =>        url('/user/create'),//因为要刷新令牌，所以不能用JS返回方法！
                'url_text'    =>        '返回',
            ]);
        }
        $data['password'] = sha1($data['password']);
        //写入数据库
        $id = UserModel::create($data)->getData('id');//写入成功返回用户ID
        //关联保存，喜好
        UserModel::find($id)->hobby()->save([//添加爱好而非修改爱好，hobby加上()变成方法实现新增
            'content' => $data['content']
        ]);
        return $id ? view($this->toast, [
            'infos' => ['恭喜，注册成功！'],
            'url_text' => '去首页',
            'url_path' => url('/')
        ]) : '注册失败！';
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
        //跳转用，跳转到编辑页面
        return \view('',[
            'user'   =>   UserModel::field('id,username,gender,email,status')->find($id),
        ]);
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
        //接收编辑模板提交过来的资源保存到数据库
        //return '修改更新：'.$id;
        //dd($request->param());
        $data = $request->param();
        try{
            validate(UserValidate::class)->scene('edit')->batch(true)->check($data);
        }catch (ValidateException $exception){
            return \view($this->toast,[
                'infos'       =>        $exception->getError(),
                //'url_path'    =>        'javascript:history.back(-1);',
                'url_path'    =>        url('/user/'.$id.'/edit'),//因为要刷新令牌，所以不能用JS返回方法！
                'url_text'    =>        '返回',
            ]);
        }
        //如果有修改密码，则写入
        if (!empty($data['newpassword'])) {
            $data['password'] = sha1($data['newpassword']);
        }
        return UserModel::update($data) ? view($this->toast, [
            'infos' => ['恭喜，修改成功！'],
            'url_text' => '去首页',
            'url_path' => url('/')
        ]) : '修改失败！';
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        return UserModel::destroy($id) ? view($this->toast, [
            'infos' => ['恭喜，删除成功~'],
            'url_text' => '去首页',
            'url_path' => url('/'),
        ]) : '删除失败';
    }
}
