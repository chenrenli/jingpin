<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/4
 * Time: 15:31
 */
namespace app\admin\controller;
use app\common\model\Setting;

class WebSlider extends Base{
    public function index(){
        if($this->request->isPost()){
            $files = $this->request->file("file");
            $param = $this->request->param();
            $titles = $param['title'];
            $urls = $param['url'];
            $upload_url = $param['upload_url'];

            foreach($titles as $title){
                if(!$title){
                    //return output_error("标题不能为空",-10);
                    $this->error("标题不能为空");
                }
            }
            $image_list = array();
            foreach($files as $file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'upload'.DS.'webslider');
                if($info){
                    // 成功上传后 获取上传信息
                    $pathName = $info->getPathname();
                    $filePath = $pathName;
                    // $filePath = str_replace($filePath,"\\","/");
                    $image = \think\Image::open($filePath);
                    $image->thumb(800,375,$image::THUMB_CENTER)->save($filePath);
                    $fileName = 'upload'.DS.'webslider'."/".$info->getSaveName();
                    $image_list[] = $fileName;
                }else{
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            //标题
            $data = array();
            if($titles){
                foreach($titles as $key=>$title){
                    $data[$key]['title'] = $title;
                    $data[$key]['image'] = isset($image_list[$key])?$image_list[$key]:(isset($upload_url[$key])?$upload_url[$key]:"");
                    $data[$key]['url'] = isset($urls[$key])?$urls[$key]:"";
                }
            }
            $settingModel = new Setting();
            $insert['name'] = "webslider_index";
            $insert['value'] = serialize($data);
            $map['name'] = "webslider_index";
            $res = $settingModel->saveData($map,$insert);
            if($res){
                //return output_data(array(),200,array("msg"=>"添加焦点图成功"));
                $this->success("添加焦点图成功");
            }else{
                //return output_error("添加焦点图失败",-50);
                $this->error("添加焦点图失败");
            }

        }else{
            $settingModel = new Setting();
            $map['name'] = "webslider_index";
            $info = $settingModel::get($map);
            $web_slider = $info->toArray();
            //$web_slider = $web_slider['data'];
            $web_slider_list = isset($web_slider['value'])?unserialize($web_slider['value']):array();
            $this->assign("web_slider_list",$web_slider_list);
            return $this->fetch();
        }
    }
}