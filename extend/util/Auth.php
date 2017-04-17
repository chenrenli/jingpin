<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/9
 * Time: 10:30
 */
namespace util;
use think\Db;

class Auth{
    /**
     * 获取管理员的权限列表
     */
    public function getAdminRules($uid){
        $sql = "select * from jp_auth_group_access as a
                left join jp_auth_group  as b
                on a.group_id=b.id where a.uid='$uid'";
        $rows = Db::query($sql);
        $rules = '';
        if($rows){
            foreach($rows as $row){
                $rules .=$row['rules'];
            }
        }
        return $rules;
    }

    /**
     * 判断某个权限规则
     */
    public function check($name,$uid){
       $auth_list = $this->getAdminRules($uid);
       if($auth_list){
            $name = strtolower($name);
            if(is_string($name)){

            }
       }
       return false;
    }
}