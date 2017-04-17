<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/24
 * Time: 15:18
 */
namespace app\common\model;

use think\Model;

class MemberFeedLike extends Model{
    public function getList($map,$field="*",$page=1,$perpage=10){
        $list = $this->field($field)->where($map)->page($page)->limit($perpage)->select();
        $result = array();
        if($list){
            foreach($list as $talk){
                $result[] = $talk->toArray();
            }
        }
        return $result;
    }
    public function getCount($map){
        return $this->where($map)->count();
    }
}