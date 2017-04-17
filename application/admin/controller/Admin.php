<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/27
 * Time: 17:57
 */
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Config;

//use Think\Page;

class Admin extends Base{
    public function index(){
        $map = array();
        $list = \app\common\model\Admin::where($map)->paginate(10);
        $page = $list->render();
        $admin_list = $list->toArray();
        $admin_list = $admin_list['data'];
        $this->assign("total",$list->total());
        $this->assign("page",$page);
        $this->assign("list",$admin_list);

        return $this->fetch();
    }
    public function add(){
        if ($this->request->isPost()){
            $username = $this->request->param("username");
            $truename = $this->request->param("truename");
            $password = $this->request->param("password");
            $confirm_password = $this->request->param("confirm_password");
            $group_id = $this->request->param("group_id");
            $email = $this->request->param("email");
            if(!$username){
                return output_error("用户名不能为空",-10);
            }
            if(!$password){
                return output_error("密码不能为空",-10);
            }
            if($confirm_password!==$password){
                return output_error("两次密码不一致",-20);
            }
            $data['username'] = $username;
            $data['truename'] = $truename;
            $data['password'] = md5(md5(Config::get("pwd_key").$password.Config::get("pwd_key")));
            $data['email'] = $email;
            $data['create_time'] = time();
            $result = (new \app\common\model\Admin())->addData($data);
            if($result){
                $adata['uid'] = $result;
                $adata['group_id'] = $group_id;
                (new \app\common\model\AuthGroupAccess())->addData($adata);
                $return['uid'] = $result;
                return output_data($return,200,array("msg"=>"添加管理员成功"));
            }else{
                return output_error("添加管理员失败",-20);
            }
        }else{
            $auth_group_list = (new \app\common\model\AuthGroup())->getList(array());
            $this->assign("auth_group_list",$auth_group_list);
            return $this->fetch();
        }

    }

    public function edit(){
        if ($this->request->isPost()){
            $id = $this->request->param("id");
            $username = $this->request->param("username");
            $truename = $this->request->param("truename");
            $password = $this->request->param("password");
            $confirm_password = $this->request->param("confirm_password");
            $email = $this->request->param("email");
            if(!$username){
                return output_error("用户名不能为空",-10);
            }
            if(!$password){
                return output_error("密码不能为空",-10);
            }
            if($confirm_password!==$password){
                return output_error("两次密码不一致",-20);
            }
            $map['uid'] = $id;
            $info = \app\common\model\Admin::get($map);
            if(!$info){
                return output_error("该管理员不存在",-30);
            }
            $data['username'] = $username;
            $data['truename'] = $truename;
            $data['password'] = md5(md5(Config::get("pwd_key").$password.Config::get("pwd_key")));
            $data['email'] = $email;
            $result = (new \app\common\model\Admin())->saveData($map,$data);
            if($result){
                $return['uid'] = $result;
                return output_data($return,200,array("msg"=>"修改管理员成功"));
            }else{
                return output_error("修改管理员失败",-20);
            }
        }else{
            $id = $this->request->param("id");
            $info = \app\common\model\Admin::get($id);

            $this->assign("info",$info);
            $this->assign("id",$id);
            return $this->fetch("edit");
        }
    }

    /**
     * 删除
     */
    public function delete(){
        if($this->request->isAjax()){
            $id = $this->request->param("id");
            $map['uid']  = array("in",$id);
            $result = \app\common\model\Admin::destroy($map);
            if($result){
                return output_data(array(),200,array("msg"=>"删除数据成功"));
            }else{
                return output_error("删除数据失败",-400);
            }
        }
    }
}