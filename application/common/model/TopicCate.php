<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 15:02
 */
namespace app\common\model;

use think\Config;

class TopicCate  extends \think\Model{
     public function addData($data){
         return $this->data($data)->save();
     }
     public function saveData($map,$data){
         return $this->update($data,$map);
     }
     public function getList($map){
        $list =  self::all($map);
        $cate_list = array();
        foreach($list as $key=>$val){
            $cate_list[$key]['cate_id'] = $val['cate_id'];
            $cate_list[$key]['parent_id'] = $val['parent_id'];
            $cate_list[$key]['cate_name'] = $val['cate_name'];
            $cate_list[$key]['topic_image'] = Config::get("image_cdn_url")."/".$val['topic_image'];
        }
        return $cate_list;
     }

}