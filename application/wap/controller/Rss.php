<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/17
 * Time: 17:07
 */
namespace app\wap\controller;

use app\common\model\MemberSubscribe;
use app\common\model\TopicCate;

class Rss extends Base{
    /**
     * 订阅用户关注分类话题
     */
    public function index(){
        $member = $this->getLoginUser();
        if($member){
            $memberSubscribeModel = new MemberSubscribe();
            $map['member_id'] = $member['member_id'];
            $list = $memberSubscribeModel::all($map);
            $this->assign("rss_list",$list);
        }
        return $this->fetch();
    }

    /**
     * 订阅分类
     */
    public function doRss(){
        if($this->request->isPost()){
            $cate_id = input("cate_id",0,"intval");
            if(!$cate_id){
                return output_error("分类ID不能为空",-10);
            }
            $member = $this->getLoginUser();
            if(!$member){
                return output_error("您尚未登录,请先登录",-20);
            }
            $topicCateModel = new TopicCate();
            $memberSubscribeModel = new MemberSubscribe();
            $map['cate_id'] = $cate_id;
            $map['member_id'] = $member['member_id'];
            $info = $memberSubscribeModel::get($map);
            if($info){
                //存在则删除
                $res = $memberSubscribeModel->destroy($map);
                if($res){
                    return output_data(array("is_delete"=>1),200,array("msg"=>"订阅分类已移除"));
                }else{
                    return output_error("订阅分类移除失败",-50);
                }
            }else{
                $data['member_id'] = $member['member_id'];
                $data['member_nickname'] = $member['member_nickname'];
                $data['cate_id'] = $cate_id;
                $data['cate_name'] = $topicCateModel->where(array("cate_id"=>$cate_id))->value("cate_name");

                $res = $memberSubscribeModel->save($data);
                if($res){
                    return output_data(array("is_delete"=>0),200,array("msg"=>"订阅分类成功"));
                }else{
                    return output_error("订阅分类失败",-40);
                }
            }

        }
    }
}