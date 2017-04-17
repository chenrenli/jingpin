<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2017-2-2
 * Time: 14:06
 */
namespace app\admin\controller;

class MemberFeedTalk extends Base{
    public function index(){
        $memberFeedModel = new \app\common\model\MemberFeedTalk();
        $map = array();
        $member_feed = $memberFeedModel->where($map)->paginate(20);
        $total = $member_feed->total();
        $page = $member_feed->render();
        $list = $member_feed->toArray();
        $list = $list['data'];
        if($list){
            foreach($list as &$val){
                $img_pattern = '/<img\s+src="(.*)"\s+(.*)>/';
                if(preg_match('//',$val['content'])){
                    $val['content'] = preg_replace($img_pattern,"",$val['content']);
                    $val['content'] = mb_substr($val['content'],0,100,"utf-8");
                }
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
            $map['id'] = array("in",$id);
            $result = \app\common\model\MemberFeedTalk::destroy($map);
            if($result){
                return output_data(array(),200,array("msg"=>"删除数据成功"));
            }else{
                return output_error("删除数据失败",-400);
            }
        }
    }
}