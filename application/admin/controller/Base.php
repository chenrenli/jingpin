<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/1
 * Time: 11:40
 */
namespace app\admin\controller;

use app\common\model\TopicCate;
use think\Controller;
use think\Paginator;
use think\Route;

class Base extends Controller{

    public function _initialize(){
       $this->checkLogin();
        $url = url($this->request->controller()."/".$this->request->action());
        $this->assign("url",$url);
    }
    /**
     * 检查权限
     */
    protected  function checkAccess(){
        $uid = $_SESSION['uid'];
        $auth = new \util\Auth();
        $name = strtolower($this->request->controller()."_".$this->request->action());
        $access = $auth->check($name,$uid);
        if(!$access && $uid!=1){
            $url = url("Error/index");
            redirect($url,array("msg"=>"您没有权限操作","type"=>"error"));
        }
    }

    protected function modifyStatus( $model, $field = 'id' ){
        $id = input('id');

        $row = $model->where([$field => $id])->find();

        if( empty($row) ) {
            return output_error('记录不存在');
        }

        if( $row->status > 0 ){
            $row->status = 0;
        }else{
            $row->status = 1;
        }
        $row->save();

        return output_data($row);
    }
    /*
    protected function error($msg){
        $this->assign("msg",$msg);
        return $this->fetch("error/index");
    }*/

    public function checkLogin(){
        if(!session("admin_user")){
            $this->redirect(url("admin/login/index"));
        }
    }

}