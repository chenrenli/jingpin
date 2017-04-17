<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/9
 * Time: 18:00
 */
namespace app\wap\controller;

use app\common\model\MemberAppend;
use app\common\model\MemberToken;
use app\common\service\Topic;
use think\Config;
use think\Validate;
use app\common\model\Member as MemberModel;

class Member extends Base{

    private $member_id = 0;

    public  function _initialize(){
        parent::_initialize();
        $member = session("login_member");
        if($member){
            $this->member_id = $member['member_id'];
        }
    }
    /**
     * 会员首页
     * @return mixed
     */
    public function index(){
        $member_id = input("member_id",0,"intval");
        if(!$member_id){
            //return output_error("参数不合法",-10);
            $this->error("参数不合法");
        }
        $memberObj = new MemberModel();
        $info = $memberObj::get(array("member_id"=>$member_id));

        $memberInfo = $info->toArray();
        $memberInfo['member_avatar'] = Config::get("image_cdn_url")."/".$memberInfo['member_avatar'];
        //member_append
        $memberAppendModel = new MemberAppend();
        $memberAppend = $memberAppendModel::get(array("member_id"=>$member_id));
        if($memberAppend){
            $member_append = $memberAppend->toArray();
            $memberInfo['member_intro'] = $member_append['member_intro'];
        }else{
            $memberInfo['member_intro'] = "";
        }
        $this->assign("memberInfo",$memberInfo);
        return $this->fetch();
    }


     public function login(){
         $url = input("url","","trim");
         $this->assign("url",$url);
         return $this->fetch("login");
     }

     public function dologin(){
         if($this->request->isPost()){
             $member_name = $this->request->param("member_name");
             $member_password = $this->request->param("member_password");
             $url = $this->request->param("url");
             $url = urldecode($url);
             if(!$member_name){
                 $this->error("用户名不能为空");
             }
             if(!$member_password){
                 $this->error("密码不能为空");
             }
             if(preg_match("/^1(3|5|7|8|9)\d{9}$/",$member_name)){
                 $map['member_mobile'] = $member_name;
             }elseif(preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[\w]{2,3}/",$member_name)){
                 $map['member_email'] = $member_name;
             }else{
                 $map['member_name'] = $member_name;
             }
             $map['member_password'] = md5(md5(Config::get("key").$member_password.Config::get("key")));
             $member = new MemberModel();
             $info = $member->get($map);
             if($info){
                    $update = array();
                    $update['last_login_ip'] = $info['login_ip'];
                    $update['last_login_time'] = $info['login_time'];
                    $update['login_time'] = time();
                    $update['login_ip'] = $this->request->ip(0,true);
                    $res = $member->save($update,['member_id'=>$info['member_id']]);

                    $data['member_id'] = $info['member_id'];
                    $data['member_name'] = $info['member_name'];
                    $data['member_mobile'] = $info['member_mobile'];
                    $data['member_email'] = $info['member_email'];
                    $data['member_nickname'] = $info['member_nickname'];
                    //session("login_member",$data);
                    $result = $this->setMemberLoginToken($data);
                    if(!$result){
                        return output_error("登录失败,登录token设置失败",-401);
                    }
                    if(!$url) $url = url("wap/index/index");
                    $data['url'] = $url;

                    return output_data($data,200,array("msg"=>"登录成功"));
             }else{
                  return output_error("用户名,密码不正确",-400);
             }
         }
     }

    /**
     * 设置用户登录的token
     */
    private function setMemberLoginToken($data){
        $str = $data['member_id'].$data['member_name'].$data['member_mobile'].$data['member_email'].time();
        $token = md5(md5(Config::get("key").$str.Config::get("key")));
        $memberToken = new MemberToken();
        $memberToken::destroy($data['member_id']);  //删除之前的token
        $tdata = array();
        $tdata['token'] = $token;
        $tdata['member_id'] = $data['member_id'];
        $tdata['login_time'] = time();
        $tdata['client_type'] = "wap";
        $result = $memberToken->data($tdata)->save();
        if($result){
            cookie("login_member",$token,3600*24*30); //cookie保存三十天
        }
        return $result;
    }

    public function register(){
        if($this->request->isPost()){
            $params = $this->request->param();
            $member_name = $params['member_name'];
            $member_password = $params['member_password'];
            $confirm_password = $params['confirm_password'];
            $sex = input("sex",0,"intval");
            $url = isset($params['url'])?$params['url']:"";
            $url = urldecode($url);
            $validate = new Validate([
                'member_name' => 'require',
                'member_password' => 'require|min:6',
                'confirm_password' => 'require|min:6',
            ]);
            $data = [
               "member_name" => $member_name,
               "member_password" => $member_password,
               "confirm_password" => $confirm_password,
            ];
            if(!$validate->check($data)){
                return output_error($validate->getError(),-20);
            }
            if($member_password!==$confirm_password){
                return output_error("两次密码输入不一致",-40);
            }
            $map = array();
            if(preg_match('/^1(3|5|7|8|9)\d{9}$/',$member_name)){
                  $map['member_mobile'] = $member_name;
                  unset($data['member_name']);
                  $data['member_mobile'] = $member_name;
            }elseif(preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[\w]{2,3}/",$member_name)){
                  unset($data['member_name']);
                  $data['member_email'] = $member_name;
                  $map['member_email'] = $member_name;
            }else{
                  $map['member_name'] = $member_name;
            }
            $member = new MemberModel();
            $info = $member::get($map);
            if($info){
                return output_error("会员已经存在",-30);
            }
            $data['member_password'] = md5(md5(Config::get("key").$data['member_password'].Config::get("key")));
            unset($data['confirm_password']);
            //登录时间
            $data['login_time'] = time();
            $data['login_ip'] = $this->request->ip(0,true);
            $data['last_login_time'] = time();
            $data['last_login_ip'] = $this->request->ip(0,true);
            $data['register_time'] = time();
            //随机用户的昵称
            $last_one_member = $member->where(array())->order("member_id","desc")->find();
            $num = 0;
            if($last_one_member){
                $last_one_member = $last_one_member->toArray();
                $last_member_nickname = $last_one_member['member_nickname'];
                if(preg_match("/(\d+)/",$last_member_nickname,$matchs)){
                    $num = $matchs[1];
                    $num++;
                }else{
                    $num = 1;
                }
            }
            if($sex == 1){
                $data['member_nickname'] = "男神".$num."号";
                $data['member_avatar'] = "static/images/default_man_face.png";
            }elseif($sex==2){
                $data['member_nickname'] = "女神".$num."号";
                $data['member_avatar'] = "static/images/default_women_face.png";
            }else{
                $data['member_nickname'] = "萌友".$num."号";
            }


            $result = $member->data($data)->save();
            if($result){
                $info = $member->get($member->member_id);
                $data = array();
                $data['member_id'] = $info['member_id'];
                $data['member_name'] = $info['member_name'];
                $data['member_mobile'] = $info['member_mobile'];
                $data['member_email'] = $info['member_email'];
                //主动登录
                //session("login_member",$data);
                $result = $this->setMemberLoginToken($data);
                if(!$result){
                    return output_error("登录失败,登录token设置失败",-401);
                }
                if(!$url) $url = url("wap/index/index");

                $data['url'] = $url;
                //redirect($url);
                return output_data($data,200,array("msg"=>"注册成功"));
            }else{
                return output_error("注册会员失败",-400);
            }
        }else{
            return $this->fetch("register");
        }
    }

    /**
     * 我的页面
     */
    public function my(){
          if($this->isLogin()){
              $member = session("login_member");
              $memberInfo['member_id'] = $member['member_id'];
              $memberInfo['member_name'] = $member['member_name']?$member['member_name']:$member['member_email'];

              $memberObj = new MemberModel();
              $info = $memberObj::get(array("member_id"=>$member['member_id']));
              $arr = $info->toArray();
              $memberInfo = array_merge($memberInfo,$arr);
              $memberInfo['member_avatar'] = Config::get("image_cdn_url")."/".$memberInfo['member_avatar'];
              //member_append
              $memberAppendModel = new MemberAppend();
              $memberAppend = $memberAppendModel::get(array("member_id"=>$member['member_id']));
              if($memberAppend){
                  $member_append = $memberAppend->toArray();
                  $memberInfo['member_intro'] = $member_append['member_intro'];
              }else{
                  $memberInfo['member_intro'] = "";
              }
              $this->assign("memberInfo",$memberInfo);
              return $this->fetch();
          }else{
              $rec_url = url("wap/member/my");
              //$rec_url = urlencode($rec_url);
              $this->redirect(url("/wap/member/login",$rec_url));
          }
    }

    public function  getMyPostTopicList(){
        if($this->request->isGet()){
            $memberId = input("member_id",0,"intval");
            $member_id = 0;
            if(!$memberId){
                $member = session("login_member");
                $member_id = $member['member_id'];
            }else{
                $member_id = $memberId;
            }
            $topicService = new Topic();
            $perpage = 10;
            $topic_list = $topicService->getTopics($perpage,0,$member_id);
            $total = $topic_list->total();
            $this->assign("topic_list",$topic_list);
            $html = $this->fetch("my_topic_list");
            $extend = array("msg"=>"获取列表成功","pages"=>ceil($total/$perpage));
            $result['html'] = $html;
            return output_data($result,200,$extend);
        }
    }

    /**
     * 设置页面
     */
    public function setting(){
        if($this->request->isPost()){
             $param = $this->request->param();
             $memberObj = new MemberModel();
             $data['member_nickname'] = $param['member_nickname'];
             if($param['member_password'])
                  $data['member_password'] = md5(md5(Config::get("key").$param['member_password'].Config::get("key")));

             $res = $memberObj->save($data,array("member_id"=>$this->member_id));
             $memberappend = new MemberAppend();
             $res2 = $memberappend->saveData(["member_id"=>$this->member_id],["member_intro"=>$param['member_intro']]);
             if($res || $res2){
                 return output_data(array(),200,array("msg"=>"修改信息成功"));
             }else{
                 return output_error("修改信息失败",-10);
             }
        }else{

            $memberObj = new MemberModel();
            $info = $memberObj::get(array("member_id"=>$this->member_id));
            $arr = $info->toArray();
            $memberInfo = $arr;
            $memberAppendModel = new MemberAppend();
            $member_append = $memberAppendModel::get(array("member_id"=>$this->member_id));
            $this->assign("memberInfo",$memberInfo);
            $this->assign("member_append",$member_append);
            return $this->fetch();
        }
    }

    /**
     * 上传用户头像
     */
    public function uploadAvatar(){
          if($this->request->isPost()){
              $file = $this->request->file("avatar");
              $path = "upload/member/avatar";
              $info = $file->move($path);
              if($info){
                  //获取图片信息生成缩率图
                  $pathName = $info->getPathname();
                  $filePath = ROOT_PATH."public/".$pathName;
                 // $filePath = str_replace($filePath,"\\","/");
                  $image = \think\Image::open($filePath);
                  $image->thumb(200,200,$image::THUMB_CENTER)->save($filePath);
                  $fileName = str_replace("\\","/",$pathName);
                  $data['member_avatar'] = $fileName;
                  $member = new MemberModel();
                  $member->save($data,array("member_id"=>$this->member_id));
                  $result = array();
                  $result['member_avatar'] = $fileName;
                  $res =  output_data($result,200,array("msg"=>"上传头像成功"));
                  return  json_encode($res);

              }else{
                 return json_encode(output_error("上传头像失败",-40));
              }
          }
    }

    /**
     * 退出登录
     */
    public function logout(){
        cookie("login_member",null);
        session("login_member",null);
        return output_data(array(),200,array("msg"=>"退出登录成功"));
    }
}