<?php
/**
* 通用的树型类，可以生成任何树型结构
*/

namespace util;

class Tree {
    private $tree = array();
    private $divide = "&nbsp;&nbsp;&nbsp;&nbsp;";
    private $icon = "|--";
    private $parent_id = "parent_id";
    private $id = "cate_id";

   public function __construct($id="cate_id",$parent_id="parent_id"){
       $this->id = $id;
       $this->parent_id = $parent_id;
   }
    public function buildTree($arr){
        foreach($arr as &$val){
            foreach($arr as &$v){
                if($val[$this->id]==$v[$this->parent_id]){
                    $val['child'][] = &$v;
                }
            }
        }
        foreach($arr as $key=>&$val){
            if($val[$this->parent_id]!=0){
                unset($arr[$key]);
            }
        }
        return $arr;
    }
    public function getTree($cate_list){
         $this->toTree($cate_list,$this->tree);
         return $this->getTreeDeep($this->tree);
    }
    /**
     * 输出tree结构
     */
    public function toTree($cate_list,&$tree){
        foreach($cate_list as &$cate){
            if(isset($cate['child'])){
                $children = $cate['child'];
                unset($cate['child']);
                $tree[] = $cate;
                $this->toTree($children,$tree);
            }else{
                $tree[] = $cate;
            }
        }
    }

    private function getTreeDeep($tree){
        $parent = array();
        foreach($tree as $key=>&$val){
            if($val[$this->parent_id]==0){
                $val['deep'] = 0;
                $parent = $val;
            }
            if(isset($tree[$key-1])&&($val[$this->parent_id]==$tree[$key-1][$this->id])){
                $val['deep'] = $tree[$key-1]['deep']+1;

            }
            if($val[$this->parent_id]==$parent[$this->id]){
                $val['deep'] = $parent['deep']+1;
            }
            $val['icon'] = str_repeat($this->divide,$val['deep']).$this->icon;

        }
        return $tree;
    }
}