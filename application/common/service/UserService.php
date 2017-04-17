<?php
namespace app\common\service;

use app\common\model\Member;
use util\Utils;

class UserService
{
    public static function getUserByUid($uids = 0, $changeIndex = false)
    {
        $lists = Member::where(['member_id' => ['in', $uids]])->select();
        if($lists && $changeIndex){
            return Utils::index($lists, 'member_id');
        }
        return $lists;
    }
}