<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/15
 * Time: 14:31
 */
namespace app\common\model;
class MemberToken extends Base{
    protected $autoWriteTimestamp = false;
    public function saveData($map,$data){
        return $this->update($data,$map);
    }
}