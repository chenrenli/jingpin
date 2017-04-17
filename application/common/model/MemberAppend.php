<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/28
 * Time: 14:26
 */
namespace app\common\model;
class MemberAppend extends Base{
    protected $autoWriteTimestamp = false;

    public function saveData($map,$data){
        $info = $this->get($map);
        if($info){
            return $this->save($data,$map);
        }else{
            $data = array_merge($map,$data);
            return $this->save($data);
        }
    }
}