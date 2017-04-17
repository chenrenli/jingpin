<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 15:05
 */
namespace app\admin\controller;
use app\common\model\Member;

class TopicPost extends Base{
    public function index(){
        $topicPostModel = new \app\common\model\TopicPost();
        $memberModel = new Member();
        $map = array("is_delete"=>0);
        $member_feed = $topicPostModel->where($map)->paginate(20);
        $total = $member_feed->total();
        $page = $member_feed->render();
        $list = $member_feed->toArray();
        $list = $list['data'];
        if($list){
            foreach($list as &$val){
                $m['member_id'] = $val['author_id'];
                $val['member_nickname'] = $memberModel->where($m)->value("member_nickname");

            }
        }
        $this->assign("page",$page);
        $this->assign("total",$total);
        $this->assign("list",$list);
        return $this->fetch();
    }

    /**
     * 删除
     */
    public function delete(){
        if($this->request->isAjax()){
            $id = $this->request->param("id");
            $topicPostModel = new \app\common\model\TopicPost();
            $result = $topicPostModel->save(["is_delete"=>1],array("post_id"=>$id));
            if($result){
                return output_data(array(),200,array("msg"=>"删除评论成功"));
            }else{
                return output_error("删除评论失败",-400);
            }
        }
    }
}