<?php
namespace app\wap\controller;

use app\common\model\MemberSubscribe;
use app\common\model\Setting;
use app\common\model\TopicCate;
use app\common\service\Topic as TopicService;
use think\Config;
use think\Request;

class Index extends Base{
    public function index(){
        //获取分类
        $this->getsubscibeCate();
        //获取焦点图
        $this->getWebSlider();
        return $this->fetch();
    }
    public function getList(){
        $topicService = new TopicService();
        $perpage = 10;
        $member = $this->getLoginUser();
        $cate_ids = array();
        if($member){
            //登录用户
            $memberSubscribeModel = new MemberSubscribe();
            $condition['member_id'] = $member['member_id'];
            $result = $memberSubscribeModel->where($condition)->select();
            if($result) {
                //已经订阅的分类
                foreach ($result as $val) {
                    $cate_ids[] = $val->cate_id;
                }
            }
        }

        $tplVar['list'] = $topicService->getTopics($perpage,$cate_ids);
        $total = $tplVar['list']->total();
        //dump($tplVar);
        $this->assign($tplVar);
        $html = $this->fetch("list_content");
        $extend = array("msg"=>"获取列表成功","pages"=>ceil($total/$perpage));
        $result['html'] = $html;
        return output_data($result,200,$extend);
    }

    /**
     * 获取焦点图
     */
    private function getWebSlider(){
        $settingModel = new Setting();
        $map['name'] = "webslider_index";
        $info = $settingModel->where($map)->find();
        $web_slider_list = array();
        if($info){
            $web_slider_list = unserialize($info['value']);
            if($web_slider_list){
                foreach($web_slider_list as &$val){
                    $val['image'] = Config::get("image_cdn_url")."/".$val['image'];
                }
            }
        }
        $this->assign("web_slider_list",$web_slider_list);
    }

    /**
     * 获取用户订阅的分类
     */
    private function getsubscibeCate(){
        $topicCateModel = new TopicCate();
        $topic_cate_list = array();
        if($this->isLogin()){
            //登录用户
            $member = $this->getLoginUser();
            $member_id = $member['member_id'];
            $memberSubscribeModel = new MemberSubscribe();
            $condition['member_id'] = $member_id;
            $result = $memberSubscribeModel->where($condition)->select();
            if($result){
                 //已经订阅的分类
                foreach($result as $val){
                    $topic_cate_list[] = $val->toArray();
                }
            }else{
                //没有订阅的
                $map['parent_id'] = array("gt",0);
                $topic_cate = $topicCateModel->where($map)->limit(0,7)->select();
                if($topic_cate){
                    foreach($topic_cate as $topic){
                        $topic_cate_list[] = $topic->toArray();
                    }
                }
            }
        }else{
            //没有登录
            $map['parent_id'] = array("gt",0);
            $topic_cate = $topicCateModel->where($map)->limit(0,7)->select();
            if($topic_cate){
                foreach($topic_cate as $topic){
                        $topic_cate_list[] = $topic->toArray();
                }
            }
        }
        if($topic_cate_list){
            foreach($topic_cate_list as &$val){
                if($val['cate_id']){
                    $val['topic_image'] = $topicCateModel->where(array("cate_id"=>$val['cate_id']))->value("topic_image");
                }
                $val['topic_image'] = !empty($val['topic_image'])?Config::get("image_cdn_url")."/".$val['topic_image']:"";
            }
        }
        $this->assign("topic_cate_list",$topic_cate_list);
    }
}