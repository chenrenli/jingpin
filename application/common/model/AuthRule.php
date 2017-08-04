<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 15:02
 */
namespace app\common\model;

class AuthRule  extends \think\Model{
     public function addData($data){
         return $this->data($data)->save();
     }
     public function saveData($map,$data){
         return $this->update($data,$map);
     }
     public function getList($map){
        return self::all($map);
     }
     public function getGroupNameList($map){

        $list = $this->where($map)->group("group_name")->select();
        $result = array();
        foreach($list as $val){
            $result[] = $val['group_name'];
        }
         return $result;
     }
}