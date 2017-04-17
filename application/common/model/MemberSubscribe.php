<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:51
 */
namespace app\common\model;

class MemberSubscribe extends Base{
    protected $autoWriteTimestamp = false;
    /**
     * 获取
     */
   public function getAllList($map){
       $list = self::all($map);
       foreach($list as $val){

       }
       return $list;
   }
}