<?php
/**
 * Created by PhpStorm.
 * User: chenyan
 * Date: 2016/11/20
 * Time: 18:49
 */
namespace app\common\model;

use think\Model;

class User extends Model {
    public function setPasswordAttr($value){
        if( empty($value) ){
            return md5($value);
        }
    }
}