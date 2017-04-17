<?php
/**
 * 白领日志
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/14
 * Time: 11:22
 */
namespace app\wap\controller;

use app\common\model\Member;
use app\common\model\MemberFeed;
use app\common\model\MemberFeedLike;
use app\common\model\MemberFeedTalk;
use app\common\model\TempAttachment;
use think\Config;

class WhiteCollar extends Base{
    public function index(){
        $this->assign("title","日志");

        return $this->fetch();
    }

    public function getFeedList(){
        if($this->request->isGet()){
            $perpage = 10;
            $memberFeedModel = new MemberFeed();
            $memberFeedTalkModel = new MemberFeedTalk();
            $memberFeedLikeModel = new MemberFeedLike();
            $map = array();
            $memberFeed = $memberFeedModel->where($map)->order("id","desc")->paginate($perpage);
            $list = $memberFeed->toArray();
            $total = $memberFeed->total();
            $feed_list = $list['data'];
            $member_ids = array();
            $members = array();
            if($feed_list){
                foreach($feed_list as $feed){
                    if(!in_array($feed['member_id'],$member_ids))
                        $member_ids[] = $feed['member_id'];
                }
                $memberModel = new Member();
                $member_list = $memberModel::all($member_ids);
                foreach($member_list as $val){
                    $members[$val['member_id']] = Config::get("image_cdn_url")."/".$val['member_avatar'];
                }
            }
            foreach($feed_list as &$val){
                $feed_id = $val['id'];
                $tmap['feed_id'] = $feed_id;
                $feed_talk_list = $memberFeedTalkModel->getList($tmap,"*",1,2);
                $val['feed_talk_list'] = $feed_talk_list;
                $content = $val['content'];
                $pattern = '/<\/?[^>]+>/';

                $content = preg_replace($pattern,"",$content);
                $val['content'] = mb_substr($content,0,200);
                $val['member_avatar'] = $members[$val['member_id']];

                //获取点赞列表
                $lmap['feed_id'] = $val['id'];
                $feed_like_list = $memberFeedLikeModel->getList($lmap,"*",1,2);
                $feed_like_count = 0;
                $feed_like_count = $memberFeedLikeModel->getCount($lmap);
                $feedlikestr = '';
                if($feed_like_list){
                    foreach($feed_like_list as $feed_like){
                        $feedlikestr .= $feed_like['member_nickname']."、";
                    }
                    $feedlikestr = trim($feedlikestr,"、");
                    $feedlikestr = $feedlikestr."等".$feed_like_count."人喜欢";
                }
                $val['feedlikestr'] = $feedlikestr;

            }
            $this->assign("feed_list",$feed_list);
            $html = $this->fetch("list_content");
            $extend = array("msg"=>"获取列表成功","pages"=>ceil($total/$perpage));
            $result['html'] = $html;
            return output_data($result,200,$extend);
        }
    }

    /**
     * 发表日志页面
     */
    public function postFeed(){
        if($this->isLogin()){
            if($this->request->isPost()){
                $member = session("login_member");
                $param = $this->request->param();
                $data['content'] = $param['content'];
                $data['member_id'] = $member['member_id'];
                $data['member_nickname'] = isset($member['member_nickname'])?$member['member_nickname']:$member['member_name'];
                $data['create_time'] = time();
                $img_pattern = '/<img\s+src="([\w\d\/\.]+)"[^<>]+>/';
                if(preg_match($img_pattern,$data['content'],$machs)){
                    //只需要匹配一张图片可以，不需要匹配所有图片
                   $image = $machs[1];
                    $filePath = ROOT_PATH."public/".$image;
                    if(file_exists($filePath)){
                        $ext = substr($filePath,-4);
                        $thumb_file = str_replace($ext,"",$image)."_380X180".$ext;
                        $img = \think\Image::open($filePath);
                        $img->thumb(380,180,$img::THUMB_CENTER)->save(ROOT_PATH."public/".$thumb_file);
                        $data['thumb_image'] = $thumb_file;
                        $data['image'] = $image;
                    }
                }
                $memberFeedModel = new MemberFeed();
                $result = $memberFeedModel->save($data);
                if($result){
                    return output_data(array("id"=>$memberFeedModel->id),200,array("msg"=>"发表日志成功"));
                }else{
                    return output_error("发表日志失败",-30);
                }
            }else{
                return $this->fetch("post_feed");
            }
        }else{
            $this->redirect(url("wap/member/login"));
        }

    }

    /**
     * 上传图片
     */
    public function postFeedImg(){
        if($this->request->isPost()){
            $file = $this->request->file("file");
            $path = "upload/feed/images";
            $info = $file->move($path);
            if($info){
                //获取图片信息生成缩率图
                $pathName = $info->getPathname();
                $filePath = ROOT_PATH."public/".$pathName;
                 $filePath = str_replace($filePath,"\\","/");
                // $image = \think\Image::open($filePath);
                // $image->thumb(286,188,$image::THUMB_CENTER)->save($filePath);
                $fileName = str_replace("\\","/",$pathName);
                /*
                $tempattachment = new TempAttachment();
                $data['src'] = $fileName;
                $tempattachment->save($data);
                */
                $result['src'] = Config::get("image_cdn_url")."/".$fileName;
                $result['title'] = date("Y-m-d H:i"); //图片名称暂时使用时间
                $res =  output_data($result,0,array("msg"=>"上传图片成功"));
                return  json_encode($res);

            }else{
                return json_encode(output_error("上传图片失败",-40));
            }
        }
    }

    /**
     * 日志详情
     */
    public function feed(){
        $params = $this->request->param();
        $id = isset($params['id'])?$params['id']:0;
        $memberFeedModel = new MemberFeed();
        $member_feed = $memberFeedModel::get(array("id"=>$id));
        $feed = array();
        if($member_feed)
            $feed = $member_feed->toArray();

        //过滤feed的图片内容

        $member_id = $feed['member_id'];
        $memberModel = new Member();
        $member = $memberModel::get(array("member_id"=>$member_id));
        //获取feed_talk 列表
        $memberFeedTalkModel = new MemberFeedTalk();
        $feed_talk = $memberFeedTalkModel->getList(array("feed_id"=>$id),"*",1,10);
        if($feed_talk){
            foreach($feed_talk as &$talk){
                $talk['member_avatar'] = Config::get("image_cdn_url")."/".$memberModel::where("member_id",$talk['member_id'])->value("member_avatar");
               // $talk['create_time'] = date("Y-m-d H:i",$talk['create_time']);
            }
        }
        //过滤content内容
        $content = $feed['content'];
        $img_pattern = '/<img\s+src="([\w\d\/\.]+)"[^<>]+>/';
        if(preg_match_all($img_pattern,$content,$maths)){
            $feed['image_list'] = $maths[1];
        }
        $feed['content'] = preg_replace("/<\/?[^>]+>/","",$content);

        $this->assign("feed_talk",$feed_talk);
        $this->assign("feed",$feed);
        $this->assign("memberInfo",$member->toArray());
        return $this->fetch("feed");
    }

    /**
     * 获取讨论列表
     */
    public function getTalkList(){
        if($this->request->isGet()){
            $feed_id = $this->request->param("feed_id");
            $page = $this->request->param("page");
            $perpage = 10;
            //获取feed_talk 列表
            $memberModel = new Member();
            $memberFeedTalkModel = new MemberFeedTalk();
            $map['feed_id'] = $feed_id;
            $feed_talk = $memberFeedTalkModel->getList($map,"*",$page,$perpage);
            $total = $memberFeedTalkModel->getCount($map);
            if($feed_talk){
                foreach($feed_talk as &$talk){
                    $talk['member_avatar'] = Config::get("image_cdn_url")."/".$memberModel::where("member_id",$talk['member_id'])->value("member_avatar");
                    //$talk['create_time'] = date("Y-m-d H:i",$talk['create_time']);
                }
            }
            $this->assign("feed_talk",$feed_talk);
            $html = $this->fetch("talk_list");
            $result['html'] = $html;
            $extend = array("msg"=>"加载评论列表成功","pages"=>ceil($total/$perpage));
            return  output_data($result,200,$extend);
        }
    }

    /**
     * 讨论
     */
    public function feedTalk(){
        if($this->request->isPost()){
            $content = $this->request->param("content");
            $feed_id = $this->request->param("feed_id");
            if(!$content){
                return output_error("内容不能为空",-10);
            }
            if(!$feed_id){
                return output_error("feed_id不能为空",-20);
            }
            $member = session("login_member");
            if(!$member){
                return output_error("您尚未登录,请先登录",-30);
            }
            $data['feed_id'] = $feed_id;
            $data['content'] = $content;
            $data['create_time'] = time();
            $data['member_id'] = $member['member_id'];
            $data['member_nickname'] = isset($member['member_nickname']) ? $member['member_nickname']:"";

            $memberFeedTalkModel = new MemberFeedTalk();
            $result = $memberFeedTalkModel->save($data);
            if($result){
                $memberModel = new Member();
                $member = $memberModel->get(array("member_id"=>$member['member_id']));
                $return = array();
                $return['content'] = $content;
                $return['member_nickname'] = $data['member_nickname'];
                $return['member_avatar'] = Config::get("image_cdn_url")."/".$member->member_avatar;
                $return['create_time'] = date("Y-m-d H:i");
                return output_data($return,200,array("msg"=>"添加评论成功"));
            }else{
                return output_error("添加评论失败",-50);
            }
        }

    }

    public function feedLike(){
        if($this->request->isPost()){
           $feed_id = $this->request->param("feed_id");
            $member = session("login_member");
            if(!$member){
                return output_error("您尚未登录,请先登录",-30);
            }
            $data['feed_id'] = $feed_id;
            $data['member_id'] = $member['member_id'];
            $data['member_nickname'] = $member['member_nickname'];
            $memberFeedLikeModel = new MemberFeedLike();
            $map['member_id'] = $member['member_id'];
            $map['feed_id'] = $feed_id;
            $info  = $memberFeedLikeModel::get($map);
            if($info){
                return output_error("已点赞",-50);
            }
            $res = $memberFeedLikeModel->save($data);
            if($res){
                return output_data(array(),200,array("msg"=>"点赞成功"));
            }else{
                return output_error("点赞失败",-40);
            }

        }
    }

}