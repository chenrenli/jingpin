<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/1
 * Time: 11:39
 */
namespace app\admin\controller;


class Setting extends Base
{
    public function index()
    {
        $settingModel = new \app\common\model\Setting();
        $list = $settingModel->getAll(array());
        $setting_list = array();
        if($list){
            foreach($list as $val){
                $setting_list[$val['name']] = $val['value'];
            }
        }

        $this->assign("setting",$setting_list);
        return $this->fetch();
    }

    public function save()
    {
        if(request()->isPost())
        {
            $weixin_appid = input("weixin_appid");
            $weixin_secret = input("weixin_secret");

            $data = array();
            $data['weixin_appid'] = $weixin_appid;
            $data['weixin_sercret'] = $weixin_secret;

            $settingModel = new \app\common\model\Setting();
            $settingModel->saveAllData($data);

            $this->success("设置成功");
        }
    }
}