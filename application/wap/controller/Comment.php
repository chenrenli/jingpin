<?php
namespace app\wap\controller;

use think\Validate;
use app\common\model\TopicPost;

class Comment extends Base
{
    public function comment()
    {
        if( $this->request->isPost() ) {
            $validate = new Validate([
                'id' => 'require',
                'content' => 'require',
            ]);

            $data = input('post.');

            if (!$validate->check($data)) {
                return output_error($validate->getError());
            }

            //评论入库
            $user = $this->getLoginUser();

            $data['thread_id'] = $data['id'];
            unset($data['id']);
            $data['author_id'] = !empty($user['member_id'])?$user['member_id']:1;
            $post = new TopicPost();
            $post->save($data);

            return output_data(array(),200,array("msg"=>'评论成功~'));
        }

        return output_error('无效操作');
    }

    public function thumbsup()
    {

    }

}