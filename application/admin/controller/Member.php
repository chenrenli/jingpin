<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/1
 * Time: 11:51
 */
namespace app\admin\controller;
use app\common\model\Member as User;
use think\console\Input;
use think\Validate;

class Member extends Base{
    public function index(){
        $list = User::paginate(10);
        $page = $list->render();
        $this->assign("page",$page);
        $this->assign('datas', $list);
        return $this->fetch();
    }

    public function add(){
        if( $this->request->isPost()){
            $validate = new Validate([
                'member_name' => 'require|unique:member,member_name',
                'member_password' => 'require',
            ]);

            $data = input('post.');

            if( !$validate->check($data) ) {
                return output_error( $validate->getError() );
            }

            $data['register_time'] = time();
            $user = new User();
            $user->save($data);

            return output_data($user->toArray(),200,array("msg"=>"添加会员成功"));
        }else{
            $this->assign('action', url('add'));
            return $this->fetch();
        }
    }

    public function edit(){
        $uid = input('uid');
        if( $uid < 1 ){
            return output_error('请选择要编辑的用户');
        }

        if( $this->request->isPost()){
            $validate = new Validate([
                'member_name' => 'require',
            ]);

            $data = input('post.');

            if( !$validate->check($data) ) {
                return output_error( $validate->getError() );
            }

            $userInfo = User::where(['member_id' => $data['uid']])->find();
            if( empty($userInfo )){
                return output_error('用户不存在');
            }

            //$userInfo->truename = $data['truename'];
            $userInfo->member_email    = $data['member_email'];
            unset($data['uid']);
            $userInfo->save($data);

            return output_data($userInfo->toArray(),200,array("msg"=>"编辑会员成功"));
        }else{
            $userInfo = User::where(['member_id' => $uid])->find();
            if( empty($userInfo )){
                return output_error('用户不存在');
            }

            $this->assign('user', $userInfo->toArray());
            $this->assign('action', url('edit'));
            return $this->fetch();
        }
    }

    public function delete(){
        $uid = input('id');
        if( empty($uid) || $uid === ',' ){
            return output_error('请选择要编辑的用户');
        }

        $uids = [];
        if( stristr($uid, ',') ){
            $uids = explode(',', $uid);
        }else{
            $uids[] = $uid;
        }

        $ret = User::where(["member_id" => ["in", $uids]])->delete();
        return output_data($uids);
    }

    public function stop(){
        return $this->modifyStatus( new User() );
    }
}