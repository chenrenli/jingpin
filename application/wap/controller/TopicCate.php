<?php
/**
 * Created by PhpStorm.
 * User: chenrenli
 * Date: 17/10/12
 * Time: 16:30
 */
namespace app\wap\controller;
class TopicCate extends Base{
    public function index(){
        $topicateModel = new \app\common\model\TopicCate();
        $list = $topicateModel->getList(array());

        $this->assign("list",$list);
        return $this->fetch();
    }
}