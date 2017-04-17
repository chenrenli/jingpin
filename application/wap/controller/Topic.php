<?php
namespace app\wap\controller;

use app\admin\controller\MemberFeed;
use app\common\model\Member;
use app\common\model\MemberSubscribe;
use app\common\model\TopicCate;
use app\common\model\TopicPost;
use think\Config;
use think\Image;
use think\Request;
use app\common\service\Topic as TopicService;
use think\Validate;

class Topic extends Base
{
    public function article()
    {

    }

    public function detail()
    {
        $id = input("id", 0, 'intval');
        if($id < 1) {
            return $this->error('缺少ID参数');
        }

        $article = (new TopicService)->getTopic($id);

        $user = $this->getLoginUser();

        list($likeList, $likeCount) = TopicService::getLikeLists($id);
        $this->assign('like_list', $likeList);
        $this->assign('like_count', $likeCount);
        $this->assign('like_check', TopicService::getLike($user['member_id']+0, $id));

        $this->assign("thread_id",$id);
        $this->assign('article', $article);
        return $this->fetch();
    }
    
    public function like()
    {
        $id = input("id", 0, 'intval');
        if($id < 1) {
            return output_error('缺少ID参数');
        }

        $topic = TopicService::getTopicById($id);
        if(empty($topic)){
            return output_error('帖子不存在');
        }
        
        $user = $this->getLoginUser();
        TopicService::like($user['member_id']+0, $id);

        return output_data('', 200, ['msg' => '操作成功']);
    }

    /**
     * 获取评论的列表
     */
    public function getTopicPostList(){
        if($this->request->isGet()){
            $thread_id = input("thread_id",0,"intval");
            if(!$thread_id)output_error("参数不正确",-20);
            $topicPostModel = new TopicPost();
            $map['thread_id'] = $thread_id;
            $map['is_delete'] = 0;
            $perpage = 10;
            $list = $topicPostModel->where($map)->paginate($perpage);
            $total = $list->total();
            $topic_post_list = array();
            $member_ids = array();
            foreach($list as $val){
                $member_ids[] = $val->author_id;
                $topic_post_list[] = $val->toArray();
            }
            $member_list = array();
            if($member_ids){
                $mmap['member_id'] = array("in",$member_ids);
                $memberModel = new Member();
                $member_list = $memberModel->getAllList($mmap,"member_id,member_avatar,member_nickname");
            }

            $members = array();
            foreach($member_list as $val){
                $members[$val['member_id']] = $val;
            }

            foreach($topic_post_list as &$val){
                $val['member_avatar'] = $val['member_nickname'] = '';
                if(!empty($members[$val['author_id']])) {
                    $val['member_avatar'] = Config::get("image_cdn_url") . "/" . $members[$val['author_id']]['member_avatar'];
                    $val['member_nickname'] = $members[$val['author_id']]['member_nickname'];
                }
            }

            $this->assign("topic_post_list",$topic_post_list);
            $html = $this->fetch("post_list");
            $extend = array("msg"=>"获取列表成功","pages"=>ceil($total/$perpage),"totalRows"=>$total);
            $result['html'] = $html;
            return output_data($result,200,$extend);
        }
    }

    public function lists()
    {
        $cate_id = input("cate_id",0,"intval");
        $topicCateModel = new TopicCate();
        $topicModel = new \app\common\model\Topic();
        $memberSubscribe = new MemberSubscribe();
        $map['cate_id'] = $cate_id;
        $topic_cate = $topicCateModel::get($map);
        $total = $topicModel->where($map)->count();
        $subscribe_count = $memberSubscribe->where($map)->count();
        $this->assign("subscribe_count",$subscribe_count);
        $this->assign("total",$total);
        $this->assign("topic_cate",$topic_cate);
        $this->assign("cate_id",$cate_id);
        return $this->fetch();
    }

    /**
     * 获取列表数据
     */
    public function getList(){
        $perpage = 10;
        $cate_id = input("cate_id",0,"intval");

        $map['cate_id'] = $cate_id;
        $topicModel = new \app\common\model\Topic();
        $memberModel = new Member();
        $topicCateModel = new TopicCate();
        $topic = $topicModel->where($map)->paginate($perpage);
        $total = $topic->total();
        $list = $topic->toArray();
        $topic_list = $list['data'];
        $member_ids = array();
        if($topic_list){
            foreach($topic_list as $topic){
                if(!in_array($topic['author_id'],$member_ids)){
                    $member_ids[] = $topic['author_id'];
                }
            }
            $mmap['member_id'] = array("in",$member_ids);
            $member_list = $memberModel->getAllList($mmap,"member_id,member_avatar,member_nickname");
            $m_avatar = array();
            foreach($member_list as $val){
                $val['member_avatar'] = Config::get("image_cdn_url")."/".$val['member_avatar'];
                $m_avatar[$val['member_id']] = array($val['member_avatar'],$val['member_nickname']);
            }
            foreach($topic_list as &$val){
                $val['member_avatar'] = isset($m_avatar[$val['author_id']][0])?$m_avatar[$val['author_id']][0]:"";
                $val['member_nickname'] = isset($m_avatar[$val['author_id']][1])?$m_avatar[$val['author_id']][1]:"";
                $val['cate_name'] = $topicCateModel->where(array("cate_id"=>$val['cate_id']))->value("cate_name");

            }
            $topic_list = $this->filterContentImage($topic_list);
        }
        $this->assign("topic_list",$topic_list);
        $this->assign("cate_id",$cate_id);
        $html = $this->fetch("item");
        $extend = array("msg"=>"获取列表成功","pages"=>ceil($total/$perpage));
        $result['html'] = $html;
        return output_data($result,200,$extend);
    }

    /**
     * 处理话题内容中的图片
     * @param $list
     * @return mixed
     */
    private function filterContentImage($list){
        if(!$list)return $list;
        foreach($list as &$val){
            $content = $val['content'];
            $img_pattern = '/<img\s+src="([\w\d\/\.:]+)"[^<>]+>/';
            if(preg_match_all($img_pattern,$content,$maths)){
                $image_list = $maths[1];
                foreach($image_list as &$image){
                    //生成缩率图
                    $filePath = ROOT_PATH."public".str_replace(Config::get("image_cdn_url"),"",$image);
                    if(file_exists($filePath)){
                        $fileName = substr($filePath,0,-4)."_119x91".substr($filePath,-4);
                        if(!file_exists($fileName)){
                            $imageObj = Image::open($filePath);
                            $imageObj->thumb(119,91)->save($fileName);
                        }
                    }
                    $image = substr($image,0,-4)."_119x91".substr($image,-4);
                }
                foreach($image_list as &$image){
                    if(stripos($image,"http")===false){
                        $image = Config::get("image_cdn_url").$image;
                    }
                }
                $val['image_list'] = $image_list;
            }
            //过滤html标签
            $pattarn = '/<\/?([^>]+)>/';
            $content = preg_replace($pattarn,"",$content);
            $content = mb_substr($content,0,100,"utf-8");
            $val['content'] = $content;
        }
        return $list;
    }

    /**
     * 发表话题
     */
    public function post(){
        //if($this->isLogin()){

        //}else{
            $cate_id = input("cate_id",0,"intval");
            $this->assign("cate_id",$cate_id);
            return $this->fetch();
        //}
    }

    /**
     * 发表话题
     */
    public function postTopic(){
        if($this->request->isPost()){
            if(!$this->isLogin()){
                return output_error("您还没有登录,请先登录",-400);
            }
            $title = input("title","","trim");
            $content = input("content","","trim");
            $cate_id = input("cate_id",0,"intval");
            if(!$title){
               return output_error("标题不能为空",-10);
            }
            if(!$content){
               return  output_error("内容不能为空",-20);
            }
            if(!$cate_id){
                return  output_error("分类ID不能为空",-30);
            }
            $member = $this->getLoginUser();
            $data['title'] = $title;
            $data['content'] = $content;
            $data['cate_id'] = $cate_id;
            $data['create_time'] = time();
            $data['author_id'] = $member['member_id'];
            $topic = new \app\common\model\Topic();
            $res = $topic->save($data);
            if($res){
                return output_data(array("cate_id"=>$cate_id),200,array("msg"=>"发表话题成功"));
            }else{
                return output_error("发表话题失败",-40);
            }
        }
    }

    /**
     * 上传图片
     */
    public function postTopicImg(){
        if($this->request->isPost()){
            $file = $this->request->file("file");
            $path = "upload/topic";
            $info = $file->move($path);
            if($info){
                //获取图片信息生成缩率图
                $pathName = $info->getPathname();
                $filePath = ROOT_PATH."public/".$pathName;
                $filePath = str_replace($filePath,"\\","/");
                // $image = \think\Image::open($filePath);
                // $image->thumb(286,188,$image::THUMB_CENTER)->save($filePath);
                $fileName = str_replace("\\","/",$pathName);
                $result['src'] = Config::get("image_cdn_url")."/".$fileName;
                $result['title'] = date("Y-m-d H:i"); //图片名称暂时使用时间
                $res =  output_data($result,0,array("msg"=>"上传图片成功"));
                return  json_encode($res);

            }else{
                return json_encode(output_error("上传图片失败",-40));
            }
        }
    }
}