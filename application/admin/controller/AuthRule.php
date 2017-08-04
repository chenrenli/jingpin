<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/27
 * Time: 17:57
 */
namespace app\admin\controller;

class AuthRule extends Base{

    public function index(){
        $map = array();
        $list = \app\common\model\AuthRule::where($map)->paginate(10);
        $page = $list->render();

        $list = $list->toArray();
        $total = $list['total'];
        $list = $list['data'];
        $this->assign("total",$total);
        $this->assign("page",$page);
        $this->assign("list",$list);

        return $this->fetch();
    }
    public function add(){
        if ($this->request->isPost()){
            $name = $this->request->param("name");
            $title = $this->request->param("title");
            $condition = $this->request->param("condition");
            $group_name = $this->request->param("group_name");
            $group = $this->request->param("group");
            if(!$name){
                return output_error("权限名称不能为空",-10);
            }
            if(!$title){
                return output_error("权限中文名称不能为空",-20);
            }
            if (!$group_name && !$group){
                return output_error("权限分组不能为空",-30);
            }
            $data['group_name'] = $group?$group:$group_name;
            $data['name'] = $name;
            $data['title'] = $title;
            $data['condition'] = $condition;
            $result = (new \app\common\model\AuthRule())->addData($data);
            if($result){
                return output_data($data,200,array("msg"=>"添加权限成功"));
            }else{
                return output_error("添加权限失败",-40);
            }

        }else{
            $list = (new \app\common\model\AuthRule())->getGroupNameList(array());
            // $tree = new \extend\util\tree($cate_list);
            $this->assign("group_list",$list);
            $this->assign("id",0);
            return $this->fetch();
        }

    }

    public function edit(){
        if ($this->request->isPost()){
            $id = $this->request->param("id");
            $name = $this->request->param("name");
            $title = $this->request->param("title");
            $condition = $this->request->param("condition");
            $group_name = $this->request->param("group_name");
            $group = $this->request->param("group");
            if(!$name){
                return output_error("权限名称不能为空",-10);
            }
            if(!$title){
                return output_error("权限中文名称不能为空",-20);
            }
            if (!$group_name && !$group){
                return output_error("权限分组不能为空",-30);
            }

            $data['group_name'] = $group?$group:$group_name;
            $data['name'] = $name;
            $data['title'] = $title;
            $data['condition'] = $condition;
            $map['id'] = $id;
            $result = (new \app\common\model\AuthRule())->saveData($map,$data);
            if($result){
                return output_data($data,200,array("msg"=>"编辑权限成功"));
            }else{
                return output_error("编辑权限失败",-40);
            }
        }else{
            $id = $this->request->param("id");
            $info = \app\common\model\AuthRule::get($id);
            $group_list = (new \app\common\model\AuthRule())->getGroupNameList(array());
            $this->assign("info",$info);
            $this->assign("group_list",$group_list);
            $this->assign("id",$id);
            return $this->fetch("edit");
        }
    }

    /**
     * 删除
     */
    public function delete(){
        if($this->request->isAjax()){
            $id = $this->request->param("id");
            $result = \app\common\model\AuthRule::destroy($id);
            if($result){
                return output_data(array(),200,array("msg"=>"删除角色成功"));
            }else{
                return output_error("删除数据失败",-400);
            }
        }
    }

    public function stop(){
        if($this->request->isAjax()){
            $id = $this->request->param("id");
            $status = $this->request->param("status");
            $data['status'] = $status;
            $map['id'] = $id;
            $result = (new \app\common\model\AuthRule())->saveData($map,$data);
            $msg = "";
            $status==1?$msg="启用数据":$msg="停用数据";
            if($result){
                return output_data(array(),200,array("msg"=>$msg."成功"));
            }else{
                return output_error($msg."失败",-400);
            }
        }
    }

}