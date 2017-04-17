<?php
/**
 * Created by PhpStorm.
 * User: chenyan
 * Date: 2016/11/20
 * Time: 18:49
 */
namespace app\common\model;

use think\Model;

class Member extends Model {
    public function setPasswordAttr($value){
        if( empty($value) ){
            return md5($value);
        }
    }

    public function getAllList($map,$field="*"){
        $member = $this->field($field)->where($map)->select();
        $list = array();
        foreach($member as $val){
            $list[] = $val->toArray();
        }
        return $list;

    }
}