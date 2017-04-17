<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/4
 * Time: 16:48
 */
namespace app\common\model;

class Setting extends Base{
    protected $autoWriteTimestamp = false;

    public function saveData($map,$data){
        $info = $this->where($map)->find();
        if($info){
            return $this->save($data,$map);
        }else{
            return $this->save($data);
        }
    }
}