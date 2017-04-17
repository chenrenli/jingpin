<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/31
 * Time: 10:04
 */
namespace app\admin\controller;

use think\Controller;

class Welcome extends Base{
    public function index(){

        return $this->fetch();
    }
}