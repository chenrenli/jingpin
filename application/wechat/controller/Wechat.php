<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 17:20
 */
namespace app\wechat\controller;
use think\Request;

class Wechat{
    const appsecret = "41c6aa351719e32a62f939a229000d13";
    const appid = "wx3b156aab08496047";

    /**
     * 获取微信的access_token
     */
    public function getAccessToken(){
        $appid = self::appid;
        $appsecret = self::appsecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $content = file_get_contents($url);

        echo $content;
        exit();

    }
}