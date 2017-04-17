<?php

use Phinx\Migration\AbstractMigration;

class AddTable extends AbstractMigration
{
 /*   public function up()
    {
        $jp_setting = $this->table('setting', [
            'id' => false,
            'primary_key' => 'name',
            'comment' => '系统设置表',
            'engine' => 'innodb',
        ]);
        $jp_setting->addColumn('name', 'string', array('limit' => 50, 'comment' => '名称'));
        $jp_setting->addColumn('value', 'text', array('default'=>'','limit' => 20, 'comment' => '值'));
        $jp_setting->save();

        $member_feed_tag = $this->table('member_feed_tag', [
            'id' => false,
            'primary_key' => 'tag_id',
            'comment' => '心情标签表',
            'engine' => 'innodb',
        ]);
        $member_feed_tag->addColumn('tag_id', 'integer', array('comment' => '序号'));
        $member_feed_tag->addColumn('tag_name', 'string', array('default'=>'','limit' => 200, 'comment' => '标签名称'));
        $member_feed_tag->addColumn('create_time', 'integer', array('comment' => '创建时间'));
        $member_feed_tag->addColumn('update_time', 'integer', array('comment' => '更新时间'));
        $member_feed_tag->save();

        $member_feed = $this->table('member_feed', [
            'comment' => '白领日志表',
            'engine' => 'innodb',
        ]);
        $member_feed->addColumn('member_id', 'integer', array('comment' => '会员ID'));
        $member_feed->addColumn('member_nickname', 'string', array('default'=>'','limit' => 80, 'comment' => '昵称'));
        $member_feed->addColumn('image', 'string', array('default'=>'','limit' => 255, 'comment' => '代表图片'));
        $member_feed->addColumn('title', 'string', array('default'=>'','limit' => 255, 'comment' => '标题'));
        $member_feed->addColumn('content', 'text', array('comment' => '内容'));
        $member_feed->addColumn('is_hidden', 'integer', array(
            'limit' => 1,
            'default' => 0,
            'comment' => '是否隐藏:0否,1是'
        ));
        $member_feed->addColumn('create_time', 'integer', array('comment' => '创建时间'));
        $member_feed->addColumn('update_time', 'integer', array('comment' => '更新时间'));
        $member_feed->save();
    }

    public function down()
    {
        $this->dropTable('setting');
        $this->dropTable('member_feed_tag');
        $this->dropTable('member_feed');
    }*/
}
