<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/7
 * Time: 11:43
 */

namespace app\admin\controller;

use app\common\model\Topic as TopicModel;
use html\Form;
use app\common\model\TopicCate;
use think\Loader;
use util\Utils;
use think\Validate;

class Topic extends Base{

    public function index()
    {
        $map     = array();
        $keyword = input('get.q', '', 'trim');

        /*拼装模糊搜索*/
        if( $keyword ){
            $map['title'] = ['like', '%'.$keyword.'%'];
        }

        /*取数据*/
        $topicService = Loader::model('Topic','service');

        $list = $topicService->getTopics(10);

        $this->assign("page_html", $list->render());
        $this->assign("datas",     $list);

        return $this->fetch();
    }

    //添加帖子
    public function add(){
        if( $this->request->isPost() ){
            $validate = new Validate([
                'title'   => 'require',
                'content' => 'require',
                'cate_id' => 'require|integer',
            ]);

            $data = input('post.');

            if( !$validate->check($data) ) {
                return output_error( $validate->getError() );
            }

            $topic = new TopicModel();
            $topic->save($data);
            return output_data($topic->toArray(), 200, array("msg"=>"添加帖子成功"));
        }else{
            $categorys  = [];
            $topicCates = TopicCate::field("cate_id,cate_name")->select();
            if( $topicCates ){
                $categorys = Utils::lists($topicCates, 'cate_id', 'cate_name');
            }

            $form = new Form();
            $form->required(true)->placeholder('请输入标题')->text('title', '标题');
            $form->required(true)->options($categorys)->select('cate_id', '帖子分类');
            $form->defaultValue(1)->checked()->checkbox('is_hot', '热门');
            $form->defaultValue(1)->checked()->checkbox('is_essence', '精华');
            $form->defaultValue(1)->checked()->checkbox('is_top', '置顶');
            $form->textarea('content', '帖子内容');

            $this->assign('form_html', $form->getFormHtml());
            return $this->fetch("public:form_layout");
        }
    }

    //编辑
    public function edit()
    {
        $id = input('id', 0, 'intval');

        if($id < 1) {
            return output_error('缺少ID参数');
        }

        $topic = TopicModel::where(['thread_id' => $id])->find();

        if(empty($topic)) {
            return output_error('找不到相关记录');
        }

        if( $this->request->isPost() ){
            $validate = new Validate([
                'title'   => 'require',
                'content' => 'require',
                'cate_id' => 'require|integer',
            ]);

            $data = input('post.');

            if( !$validate->check($data) ) {
                return output_error( $validate->getError() );
            }

            $topic->title   = $data['title'];
            $topic->content = $data['content'];
            $topic->cate_id = $data['cate_id'];

            $topic->is_hot = input('is_hot', 0);
            $topic->is_essence = input('is_essence', 0);
            $topic->is_top = input('is_top', 0);

            $topic->save();
            return output_data($topic->toArray(), 200, array("msg"=>"编辑帖子成功"));
        }else{
            $categorys  = [];
            $topicCates = TopicCate::field("cate_id,cate_name")->select();

            if( $topicCates ){
                $categorys = Utils::lists($topicCates, 'cate_id', 'cate_name');
            }

            $form = new Form();
            $form->required(true)->defaultValue($topic->title)->text('title', '标题');
            $form->required(true)->options($categorys)->defaultValue($topic->cate_id)->select('cate_id', '帖子分类');
            $form->required(true)->defaultValue($id)->hidden('id');

            $form->checked(($topic->is_hot > 0? true:false))->defaultValue(1)->checkbox('is_hot', '热门');
            $form->checked(($topic->is_essence > 0? true:false))->defaultValue(1)->checkbox('is_essence', '精华');
            $form->checked(($topic->is_top > 0? true:false))->defaultValue(1)->checkbox('is_top', '热门');
            $form->required(true)->defaultValue($topic->content)->textarea('content', '帖子内容');

            $this->assign('form_html', $form->getFormHtml());
            return $this->fetch("public:form_layout");
        }
    }

    /**
     * 删除
     */
    public function delete(){
        if($this->request->isAjax()){
            $id = $this->request->param("id");
            $map['thread_id'] = array("in",$id);
            $result = \app\common\model\Topic::destroy($map);
            if($result){
                return output_data(array(),200,array("msg"=>"删除数据成功"));
            }else{
                return output_error("删除数据失败",-400);
            }
        }
    }
}