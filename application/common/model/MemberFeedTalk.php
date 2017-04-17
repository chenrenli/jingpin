<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/13
 * Time: 14:47
 */
namespace app\common\model;
use think\Config;
use think\Model;

class MemberFeedTalk extends Model{
    public function getList($map,$field="*",$page=1,$perpage=10){
        $list = $this->field($field)->where($map)->page($page)->limit($perpage)->select();
        $result = array();
        $memberModel = new Member();
        if($list){
            foreach($list as $talk){
                $result[] = $talk->toArray();
            }
            if($result){
                foreach($result as &$val){
                    $val['member_avatar'] = Config::get("image_cdn_url")."/".$memberModel->where(array("member_id"=>$val['member_id']))->value("member_avatar");
                }
            }
        }
        return $result;
    }
    public function getCount($map){
        return $this->where($map)->count();
    }
}