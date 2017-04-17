<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/8
 * Time: 15:59
 */
namespace app\admin\controller;

use app\common\model\TempAttachment;
use Phinx\Util;
use util\Tree;

class TopicCate extends Base{
    public function index(){
        $map = array();
        $list = \app\common\model\TopicCate::where($map)->paginate(10);
        $page = $list->render();
        $arr = $list->toArray();
        $cate_list = $arr['data'];

        /*
        $tree = new tree();
        $cate_list = $tree->buildTree($cate_list);
        $cate_list = $tree->getTree($cate_list);
        */

        $this->assign("total",$arr['total']);
        $this->assign("page",$page);
        $this->assign("cate_list",$cate_list);
        return $this->fetch();
    }
    public function add(){
        if ($this->request->isPost()){
             $cate_name = $this->request->param("cate_name");
             $parent_id = $this->request->param("parent_id");
             if(!$cate_name){
                return output_error("分类名称不能为空",-10);
             }
            $file_id = input("file_id",0,"intval");
            $tempAttachmentModel = new TempAttachment();
            $data['cate_name'] = $cate_name;
            $data['parent_id'] = $parent_id;
            if($file_id){
                $attachment = $tempAttachmentModel->get(array("file_id"=>$file_id));
                $data['topic_image'] = $attachment->src;
            }
             $result = (new \app\common\model\TopicCate())->addData($data);
             if($result){
                 if($file_id)
                    $tempAttachmentModel->destroy(array("file_id"=>$file_id));
                 $return['cate_id'] = $result;
                 return output_data($return,200,array("msg"=>"添加分类成功"));
             }else{
                 return output_error("添加分类失败",-20);
             }
        }else{
            $cate_list = (new \app\common\model\TopicCate())->getList(array());

            $tree = new tree();
            $cate_list = $tree->buildTree($cate_list);
             $cate_list = $tree->getTree($cate_list);
            $this->assign("cate_list",$cate_list);
            $this->assign("id",0);
            return $this->fetch();
        }

    }
    public function uploadTopicCateImage(){
        if($this->request->isPost()){
            $file = $this->request->file("topic_image");

            $path = "upload/topic_cate";
            $info = $file->move($path);
            if($info){
                //获取图片信息生成缩率图
                $pathName = $info->getPathname();
                $filePath = ROOT_PATH."public/".$pathName;
                // $filePath = str_replace($filePath,"\\","/");
                $image = \think\Image::open($filePath);
                $image->thumb(50,50,$image::THUMB_CENTER)->save($filePath);
                $fileName = str_replace("\\","/",$pathName);
                $tempAttachmentModel = new TempAttachment();
                $data['src'] = $fileName;
                $tempAttachmentModel->save($data);
                $file_id = $tempAttachmentModel->getLastInsID();
                $result = array("file_id"=>$file_id,"src"=>$fileName);
                $res =  output_data($result,200,array("msg"=>"上传图片成功"));
                return  json_encode($res);

            }else{
                return json_encode(output_error("上传图片失败",-40));
            }
        }
    }

    public function edit(){
        if ($this->request->isPost()){
            $cate_name = $this->request->param("cate_name");
            $parent_id = $this->request->param("parent_id");
            $id = $this->request->param("id");
            if(!$cate_name){
                return output_error("分类名称不能为空",-10);
            }
            $file_id = input("file_id",0,"intval");
            $tempAttachmentModel = new TempAttachment();
            if($file_id){
                $attachment = $tempAttachmentModel->get(array("file_id"=>$file_id));
                $data['topic_image'] = $attachment->src;
            }

            $data['cate_name'] = $cate_name;
            $data['parent_id'] = $parent_id;
            $map['cate_id'] = $id;
            $result = (new \app\common\model\TopicCate())->saveData($map,$data);
            if($result){
                if($file_id){
                    $tempAttachmentModel->destroy(array("file_id"=>$file_id));
                }
                $return['cate_id'] = $result;
                return output_data($return,200,array("msg"=>"修改分类成功"));
            }else{
                return output_error("修改分类失败",-20);
            }
        }else{
            $id = $this->request->param("id");
            $info = \app\common\model\TopicCate::get($id);
            $cate_list = (new \app\common\model\TopicCate())->getList(array());
            $tree = new tree();
            $cate_list = $tree->buildTree($cate_list);
            $cate_list = $tree->getTree($cate_list);

            $this->assign("info",$info);
            $this->assign("cate_list",$cate_list);
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
            $result = \app\common\model\TopicCate::destroy($id);
            if($result){
                return output_data(array(),200,array("msg"=>"删除数据成功"));
            }else{
                return output_error("删除数据失败",-400);
            }
        }
    }

}