<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/4
 * Time: 16:48
 */
namespace app\common\model;

class Setting extends Base
{
    protected $autoWriteTimestamp = false;

    public function saveAllData($data)
    {
        foreach ($data as $key => $value) {
            $info = self::get(array("name" => $key));
            if ($info) {
                $this->save(array("value"=>$value),array("name"=>$key));
            } else {
                 $this->insert(['name'=>$key,"value"=>$value]);
            }
        }
    }
    public function getAll($map){
        $result = array();
        $query =  $this->where($map)->select();
        if($query){
            foreach($query as $val){
                $result[] = $val->toArray();
            }

        }
        return $result;
    }
}