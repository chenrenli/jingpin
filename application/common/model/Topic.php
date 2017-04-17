<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9
 * Time: 15:02
 */
namespace app\common\model;

class Topic extends Base {

    public function getIsHotNameAttr($value, $data){
        return $this->getStatus( $data['is_hot'] );
    }

    public function getIsEssenceNameAttr($value, $data){
        return $this->getStatus( $data['is_essence'] );
    }

    public function getIsTopNameAttr($value, $data){
        return $this->getStatus( $data['is_top'] );
    }

    public function getIsDeleteNameAttr($value, $data){
        return $this->getStatus( $data['is_delete'] );
    }

    private function getStatus($value){
        $status = '否';
        if($value > 0) {
            $status = '是';
        }
        return $status;
    }

    public function getTopicList($map,$field="*",$page=1,$perpage){
        $topic = $this->field($field)->where($map)->page($page,$perpage)->select();
        $list = array();
        if($topic){
            foreach($topic as $val){
                $list[] = $val->toArray();
            }
        }
        return $list;
    }


}