<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 11:47
 */
namespace app\admin\controller;
use app\common\model\Admin;
use think\Config;
use think\Validate;

class Login extends Base{
    public function _initialize(){

    }

    public function index(){
        return $this->fetch("login");
    }

    /**
     * 登录验证
     */
    public function dologin(){
        if($this->request->isPost()){
            $username = input("username","","trim");
            $password = input("password","","trim");
            $captcha = input("captcha","","trim");
            $validate = new Validate([
                'username'=>'require',
                'password'=>'require',
            ]);
            $data['username'] = $username;
            $data['password'] = $password;
            if(!$validate->check($data)){
                $err = $validate->getError();
                return output_error($err,-500);
            }
            if(!captcha_check($captcha)){
                return output_error("验证码不正确",-500);
            }

            $adminModel = new Admin();
            $map['username'] = $username;
            $map['password'] = md5(md5(Config::get("pwd_key").$password.Config::get("pwd_key")));
            $info = $adminModel::get($map);
            if($info){
                $result['uid'] = $info->uid;
                $result['username'] = $info->username;
                $result['truename'] = $info->truename;
                session("admin_user",$result);
                return output_data(array(),200,array("msg"=>"登录成功"));
            }else{
                return output_error("密码不正确或者用户名不存在",-500);
            }
        }
    }

    /**
     * 退出登录
     */
    public function logout(){
        session("admin_user",null);
        session_destroy();
        $this->redirect(url("admin/login/index"));
    }
}