<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 15:02
 */
namespace app\common\model;

class AuthGroup  extends \think\Model{
     public function addData($data){
         return $this->data($data)->save();
     }
     public function saveData($map,$data){
         return $this->update($data,$map);
     }
     public function getList($map){
        return self::all($map);
     }
}