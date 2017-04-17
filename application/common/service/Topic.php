<?php
/**
 * Created by PhpStorm.
 * User: chenyan
 * Date: 2016/11/23
 * Time: 11:24
 */
namespace app\common\service;

use app\common\model\TopicLike;
use think\Config;
use think\Image;
use think\Model;
use app\common\model\Topic as TopicModel;
use app\common\model\TopicCate;
use app\common\model\Member;
use app\common\model\TopicPost;
use util\Utils;

class Topic extends Model {

    public function getTopicsByCate($cateId){

    }

    public function getTopicsByAuthorId($authorId){

    }

    public function getTopicsByTime($beginTime, $endTime){

    }
    
    public static function like($uid = 0, $topicId = 0)
    {
        $like = self::getLike($uid, $topicId);
        
        //喜欢操作
        if(empty($like)){
            $likeSave = new TopicLike();
            $likeSave->user_id = $uid;
            $likeSave->thread_id = $topicId;
            $ret = $likeSave->save();
        }else{
            //不喜欢，即是删除操作
            $ret = $like->delete();
        }
        
        return $ret;
    }
    
    public static function getLike($uid = 0, $topicId = 0)
    {
        return TopicLike::where(['user_id' => $uid, 'thread_id' => $topicId])->find();
    }
    
    public static function getLikeLists($topicId = 0, $num = 3)
    {
        $query = TopicLike::where(['thread_id' => $topicId]);
        $likeCount = $query->count();
        $likeLists = $query->limit($num)->select();
        
        if(!empty($likeLists)){
            $uids = array_unique(Utils::getColumn($likeLists, 'user_id'));
            $users = UserService::getUserByUid($uids, true);
            foreach ($likeLists as &$likeList) {
                if(!empty($users[$likeList['user_id']])){
                    $likeList['member_avatar'] = config('image_cdn_url')."/".$users[$likeList['user_id']]['member_avatar'];
                }
                if(empty($likeList['member_avatar'])){
                    $likeList['member_avatar'] = config('default_avatar');
                }
            }
        }
        
        return [$likeLists, $likeCount];
    }
    
    public static function getComments($topicId = 0, $page = 1)
    {
        $comments = TopicPost::where(['thread_id' => $topicId])->page($page)->order('post_id desc')->select();

        $uids = array_unique(Utils::getColumn($comments, 'author_id'));

        $users = UserService::getUserByUid($uids);

        $users = Utils::index($users, 'member_id');

        foreach ($comments as &$comment){
            $comment['author'] = 'admin';
            if(!empty($users[$comment['author_id']])){
                $comment['author'] = $users[$comment['author_id']]['member_nickname'];
            }
        }

        return $comments;
    }

    /**
     * @param int $pageSize
     * @param int $cateId
     * @param int $authorId
     * @param null $isHot
     * @param null $isEssence
     * @param null $isTop
     * @param null $isDelete
     * @return false
     */
    public function getTopics(
        $pageSize   = 10,
        $cateId     = 0,
        $authorId   = 0,
        $isHot      = null,
        $isEssence  = null,
        $isTop      = null,
        $isDelete   = null
    ){
        $where = [];
        if( $cateId){
            if (is_int($cateId))
                $where['cate_id'] = $cateId;
            elseif((is_array($cateId)||is_string($cateId))&&!empty($cateId)){
                $where['cate_id'] = array("in",$cateId);
            }
        }
        if( $authorId > 0 ){
            $where['author_id'] = $authorId;
        }
        if( isset($isHot) ){
            $where['is_hot'] = $isHot;
        }
        if( isset($isEssence) ){
            $where['is_essence'] = $isEssence;
        }
        if( isset($isTop) ){
            $where['is_top'] = $isTop;
        }
        if( isset($isDelete) ){
            $where['is_delete'] = $isDelete;
        }

        $builder = TopicModel::where($where);

        if( input('page') > 0 || $pageSize > 0 ) {
            if(empty($pageSize)) {
                $pageSize = 10;
            }
            $lists = $builder->order("create_time","desc")->paginate($pageSize);
        }else{
            $lists = $builder->select();
        }

        if($lists->total() > 0){
            $authorIds = $cateIds = [];
            foreach ($lists as &$list) {
                $authorIds[] = $list->author_id;
                $cateIds[] = $list->cate_id;
            }

            $lists = $this->getAuthors($authorIds, $lists);
            $lists = $this->getCategorys($cateIds, $lists);
        }
        $lists = $this->filterContentImage($lists);

        return $lists;
    }

    /**
     * 处理话题内容中的图片
     * @param $list
     * @return mixed
     */
    private function filterContentImage($list){
        if(!$list)return $list;
        foreach($list as &$val){
            $content = $val->content;
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
                $val['image_list'] = $image_list;
            }
            //过滤html标签
            $pattarn = '/<\/?([^>]+)>/';
            $content = preg_replace($pattarn,"",$content);
            $content = mb_substr($content,0,100,"utf-8");
            $val->content = $content;
        }
        return $list;
    }

    /**
     * @param $ids
     * @param array $datas
     */
    public function getAuthors($ids, $datas = [])
    {
        $authors = Member::where(['member_id' => ['in',array_unique($ids)]])->select();
        $avatars = array();
        if($authors){
            $avatars = Utils::lists($authors,'member_id','member_avatar');
        }
        if($authors){
            $authors = Utils::lists($authors, 'member_id', 'member_nickname');
        }

        foreach ($datas as $key => &$data){
            $data->author = "";
            if(isset($authors[$data->author_id])){
                $data->author = $authors[$data->author_id];
                $data->member_avatar = Config::get("image_cdn_url")."/".$avatars[$data->author_id];
            }else{
                $data->member_avatar = '';
            }
        }
        return $datas;
    }

    /**
     * @param $ids
     * @param array $datas
     */
    public function getCategorys($ids, $datas = [])
    {
        $cates = TopicCate::where(['cate_id' => ['in', array_unique($ids)]])->select();
        if($cates){
            $cates = Utils::lists($cates, 'cate_id', 'cate_name');
        }

        foreach ($datas as $key => $data){
            $data->cate_name = "";
            if(isset($cates[$data->cate_id])){
                $data->cate_name = $cates[$data->cate_id];
            }
        }

        return $datas;
    }

    public function getTopic($threadId){
        $topic = TopicModel::where(['thread_id' => $threadId])->find();
        if($topic){
            $author = Member::where(['member_id' => $topic['author_id']])->find();
            $topic['author'] = !empty($author['member_nickname']) ? $author['member_nickname'] :'admin' ;
            $topic['member_avatar'] = !empty($author['member_avatar'])?Config::get("image_cdn_url")."/".$author['member_avatar']:"";
        }
        return $topic;
    }

    public static function getTopicById($topicId = 0)
    {
        $topic = TopicModel::where(['thread_id' => $topicId])->find();
        if($topic){
            $author = Member::where(['member_id' => $topic['author_id']])->find();
            $topic['author'] = !empty($author['member_nickname']) ? $author['member_nickname'] :'admin' ;
            $topic['member_avatar'] = !empty($author['member_avatar'])?Config::get("image_cdn_url")."/".$author['member_avatar']:"";
        }
        return $topic;
    }
}