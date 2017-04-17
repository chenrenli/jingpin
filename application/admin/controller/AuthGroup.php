<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/27
 * Time: 17:57
 */
namespace app\admin\controller;
use app\admin\controller\Base;

class AuthGroup extends Base{


    public function index(){
        $map = array();
        $list = \app\common\model\AuthGroup::where($map)->paginate(10);
        $page = $list->render();

        $list = $list->toArray();
        $total = $list['total'];
        $list = $list['data'];
        $this->assign("total",$total);
        $this->assign("page",$page);
        $this->assign("list",$list);

        return $this->fetch("admin-role");
    }
    public function add(){
        if ($this->request->isPost()){
            $params = $this->request->param();
            $title = $params['title'];
            $rule_ids = $params['rule_ids'];
            $rules = "";
            if($rule_ids){
                $rules = implode(",",$rule_ids);
            }
            if(!$title){
                return output_error("角色名称不能为空",-10);
            }
            $data['title'] = $title;
            $data['rules'] = $rules ? $rules:"";
            $result = (new \app\common\model\AuthGroup())->addData($data);
            if($result){
                $return['id'] = $result;
                return output_data($return,200,array("msg"=>"添加角色成功"));
            }else{
                return output_error("添加角色失败",-20);
            }
        }else{
            $list = (new \app\common\model\AuthRule())->getList(array());
            $group_list = array();
            if($list){
                foreach($list as $rule){
                    $group_list[$rule['group_name']][] = $rule;
                }
            }
            $this->assign("group_list",$group_list);
            $this->assign("id",0);
            return $this->fetch("admin-role-add");
        }

    }

    public function edit(){
        if ($this->request->isPost()){
            $id = $this->request->param("id");
            $params = $this->request->param();
            $title = $params['title'];
            $rule_ids = $params['rule_ids'];
            $rules = "";
            if($rule_ids){
                $rules = implode(",",$rule_ids);
            }
            if(!$title){
                return output_error("角色名称不能为空",-10);
            }
            $data['title'] = $title;
            $data['rules'] = $rules ? $rules:"";
            $map['id'] = $id;
            $result = (new \app\common\model\AuthGroup())->saveData($map,$data);
            if($result){
                $return['id'] = $result;
                return output_data($return,200,array("msg"=>"修改角色成功"));
            }else{
                return output_error("修改角色失败",-20);
            }
        }else{
            $id = $this->request->param("id");
            $info =  \app\common\model\AuthGroup::get($id);
            $info = $info->toArray();
            $list = (new \app\common\model\AuthRule())->getList(array());
            $group_list = array();
            if($list){
                foreach($list as $rule){
                    $data = array();
                    if(stripos($info['rules'],(string)$rule['id'])!==false){
                        $data['checked'] = "checked";
                    }else{
                        $data['checked'] = "";
                    }
                    $data['id'] = $rule['id'];
                    $data['name'] = $rule['name'];
                    $data['title'] = $rule['title'];
                    $group_list[$rule['group_name']][] = $data;
                }
            }
            $this->assign("group_list",$group_list);
            $this->assign("info",$info);
            $this->assign("id",$id);
            return $this->fetch("admin-role-edit");
        }
    }

    /**
     * 删除
     */
    public function delete(){
        if($this->request->isAjax()){
            $id = $this->request->param("id");
            $result = \app\common\model\AuthGroup::destroy($id);
            if($result){
                return output_data(array(),200,array("msg"=>"删除角色成功"));
            }else{
                return output_error("删除数据失败",-400);
            }
        }
    }

}