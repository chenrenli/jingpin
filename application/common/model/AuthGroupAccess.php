<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/8
 * Time: 16:43
 */
namespace app\common\model;
use think\Model;

class AuthGroupAccess extends Model{
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