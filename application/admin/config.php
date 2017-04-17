<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/31
 * Time: 13:36
 */

$menu = array(
    'admin_menu'=>array(
        array(
            'name' => "setting",
            'title' => "系统管理",
            'child' => array(
                array(
                    'name' => "setting",
                    'title' => "系统设置",
                    'controller' => 'setting',
                    'action' => 'index',
                ),


            ),
        ),
        array(
            'name' => "setting",
            'title' => "首页管理",
            'child' => array(
                array(
                    'name' => "setting",
                    'title' => "焦点图管理",
                    'controller' => 'WebSlider',
                    'action' => 'index',
                ),


            ),
        ),
        array(
            'name' => "topic",
            'title' => "话题管理",
            'child' => array(
                array(
                    'name' => "topic",
                    'title' => "话题管理",
                    'controller' => 'topic',
                    'action' => 'index',
                ),
                array(
                    'name' => "topicCate",
                    'title' => "话题分类",
                    'controller' => 'TopicCate',
                    'action' => 'index',
                ),

                array(
                    'name' => "TopicPost",
                    'title' => "评论管理",
                    'controller' => 'TopicPost',
                    'action' => 'index',
                ),

            ),
        ),
        array(
            'name' => "topic",
            'title' => "白领日志",
            'child' => array(
                array(
                    'name' => "MemberFeed",
                    'title' => "日志管理",
                    'controller' => 'MemberFeed',
                    'action' => 'index',
                ),
                /*
                array(
                    'name' => "MemberFeedTag",
                    'title' => "心情标签",
                    'controller' => 'MemberFeedTag',
                    'action' => 'index',
                ),*/

                array(
                    'name' => "MemberFeedTalk",
                    'title' => "评论管理",
                    'controller' => 'MemberFeedTalk',
                    'action' => 'index',
                ),

            ),
        ),

        array(
            'name' => "member",
            'title' => "会员管理",
            'child' => array(
                array(
                    'name' => "setting",
                    'title' => "会员管理",
                    'controller' => 'member',
                    'action' => 'index',
                ),


            ),
        ),
        array(
            'name' => "admin",
            'title' => "管理员",
            'child' => array(
                array(
                    'name' => "topic",
                    'title' => "管理员管理",
                    'controller' => 'admin',
                    'action' => 'index',
                ),
                array(
                    'name' => "topic",
                    'title' => "角色管理",
                    'controller' => 'AuthGroup',
                    'action' => 'index',
                ),
                array(
                    'name' => "topic",
                    'title' => "权限管理",
                    'controller' => 'AuthRule',
                    'action' => 'index',
                ),

            ),
        ),
    )
);

return $menu;