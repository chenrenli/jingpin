<?php

use Phinx\Migration\AbstractMigration;

class AddTables extends AbstractMigration
{
    public function up()
    {
        $topicLike = $this->table('topic_like',[
            'comment' => '喜欢帖子表',
            'engine' => 'innodb',
        ]);
        $topicLike->addColumn('thread_id', 'integer', array('comment' => '帖子ID'));
        $topicLike->addColumn('user_id', 'integer', array('comment' => '用户ID'));
        $topicLike->addColumn('create_time', 'integer', array('comment' => '创建时间'));
        $topicLike->addColumn('update_time', 'integer', array('comment' => '更新时间'));
        $topicLike->save();
    }

    public function down()
    {
        $this->dropTable('topic_like');
    }
}
