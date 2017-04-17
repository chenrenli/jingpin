<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/1
 * Time: 11:39
 */
namespace app\admin\controller;


class Setting extends Base{
    public function index(){
        return $this->fetch();
    }
}