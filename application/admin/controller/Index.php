<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/26
 * Time: 11:38
 */
namespace app\admin\controller;
use think\Config;
use think\Controller;

class Index extends Base{
    public function index(){

        $admin_menu_list = Config::get("admin_menu");
        if($admin_menu_list){
            foreach($admin_menu_list as &$val){
                if(isset($val['child'])){
                    foreach($val['child'] as $k=>&$child_menu){
                        $child_menu['url'] = url($child_menu["controller"]."/".$child_menu["action"]);
                    }
                }
            }
        }
        $this->assign("menu_list",$admin_menu_list);
        return $this->fetch();

    }
}