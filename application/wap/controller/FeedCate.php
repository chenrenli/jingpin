<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 16:55
 */
namespace app\wap\controller;
class FeedCate extends Base{
     public function index(){
         return $this->fetch();
     }
}