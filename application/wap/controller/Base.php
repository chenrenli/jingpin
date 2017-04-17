<?php
namespace app\wap\controller;

use app\common\model\Member;
use app\common\model\MemberToken;
use think\Controller;
use think\Response;

class Base extends Controller{

    protected $user_session_key = "login_member";

     public function _initialize(){
         $is_login  = $this->isLogin();
         $this->getTopicCateList();
     }

     public function isLogin(){
         $token = cookie("login_member");
         if(cookie("login_member")){
             $memberToken = new MemberToken();
             $map['token'] = $token;
             $info = $memberToken->get($map);
             if($info){
                 if(!session("login_member")){
                     $member = new Member();
                     $query = $member->get(array("member_id"=>$info['member_id']));
                     $info = $query->toArray();
                     //将信息存入session
                     $data = array();
                     $data['member_id'] = $info['member_id'];
                     $data['member_name'] = $info['member_name'];
                     $data['member_email'] = $info['member_email'];
                     $data['member_mobile'] = $info['member_mobile'];
                     $data['member_nickname'] = $info['member_nickname'];
                     $data['last_login_time'] = $info['last_login_time'];
                     $data['last_login_ip'] = $info['last_login_ip'];
                     $data['login_time'] = $info['login_time'];
                     $data['login_ip'] = $info['login_ip'];
                     session($this->user_session_key, $data);
                 }
                 return true;
             }else{
                 return false;
             }
         }else{
             return false;
         }
     }

     protected function getLoginUser()
     {
         return session($this->user_session_key);
     }

    protected function getTopicCateList(){
        $topicCateModel = new \app\common\model\TopicCate();
        $cate_list = $topicCateModel->getList(array());
        //组合成一二级分类
        $tree = new \util\Tree();
        $cate_list = $tree->buildTree($cate_list);

        $this->assign("cate_list",$cate_list);
    }
}