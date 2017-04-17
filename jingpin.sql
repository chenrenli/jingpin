/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.6.17 : Database - jingpin
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`jingpin` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `jingpin`;

/*Table structure for table `jp_admin` */

DROP TABLE IF EXISTS `jp_admin`;

CREATE TABLE `jp_admin` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL DEFAULT '' COMMENT '管理员名称',
  `truename` varchar(40) NOT NULL DEFAULT '' COMMENT '真名',
  `password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(80) NOT NULL DEFAULT '' COMMENT 'email',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效:0无,1有',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `jp_admin` */

insert  into `jp_admin`(`uid`,`username`,`truename`,`password`,`email`,`create_time`,`status`) values (9,'admin','123456','0421bd8d4d862b98dfcef6e52dce1748','',1488350628,1);

/*Table structure for table `jp_attachment` */

DROP TABLE IF EXISTS `jp_attachment`;

CREATE TABLE `jp_attachment` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `thread_id` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `post_id` int(11) DEFAULT '0' COMMENT '回复ID',
  `src` varchar(255) NOT NULL COMMENT '附件路径',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jp_attachment` */

/*Table structure for table `jp_auth_group` */

DROP TABLE IF EXISTS `jp_auth_group`;

CREATE TABLE `jp_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `jp_auth_group` */

insert  into `jp_auth_group`(`id`,`title`,`status`,`rules`) values (1,'fdsafdsa',1,'1,4,5'),(6,'fsdafdsa',1,''),(10,'fsafd',1,'1,3');

/*Table structure for table `jp_auth_group_access` */

DROP TABLE IF EXISTS `jp_auth_group_access`;

CREATE TABLE `jp_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jp_auth_group_access` */

insert  into `jp_auth_group_access`(`uid`,`group_id`) values (1,10);

/*Table structure for table `jp_auth_rule` */

DROP TABLE IF EXISTS `jp_auth_rule`;

CREATE TABLE `jp_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `group_name` char(40) NOT NULL DEFAULT '' COMMENT '权限分组',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `jp_auth_rule` */

insert  into `jp_auth_rule`(`id`,`name`,`title`,`status`,`condition`,`group_name`) values (3,'测试2','测试2',1,'测试2','测试');

/*Table structure for table `jp_member` */

DROP TABLE IF EXISTS `jp_member`;

CREATE TABLE `jp_member` (
  `member_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_name` varchar(80) NOT NULL DEFAULT '' COMMENT '用户名',
  `member_nickname` char(100) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `member_password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
  `member_email` varchar(80) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `member_mobile` char(20) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `member_points` int(10) NOT NULL DEFAULT '0' COMMENT '积分',
  `member_avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '会员头像地址',
  `member_sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别:0未知,1男,2女',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '当前登录时间',
  `login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '当前登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '上次登录的IP',
  `flow_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被关注数',
  `like_num` int(10) NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `reply_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复数',
  `post_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发表数',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除:0否,1是',
  `register_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `jp_member` */

insert  into `jp_member`(`member_id`,`member_name`,`member_nickname`,`member_password`,`member_email`,`member_mobile`,`member_points`,`member_avatar`,`member_sex`,`login_time`,`login_ip`,`last_login_time`,`last_login_ip`,`flow_num`,`like_num`,`reply_num`,`post_num`,`is_delete`,`register_time`) values (2,'fdsafdsa','','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'',0,0,'0',0,'0',0,0,0,0,0,0),(4,'awafd','','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'',0,0,'0',0,'0',0,0,0,0,0,0),(7,'fdsafsa afdsa','','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'',0,1481680817,'127.0.0.1',1481680817,'127.0.0.1',0,0,0,0,0,0),(8,'','白骨精','bdf8c126d07dda8a3bf37ac37a5e6418','','13535134940',0,'upload/member/avatar/20170308/e797244d9e47417f0185fbbea770736f.jpg',0,1490247792,'192.168.0.197',1490176893,'192.168.0.39',0,0,0,0,0,1481681315),(10,'crlpwj@163.com','','743f7e6bf9af74abb66ed4b327c50473','','',0,'',0,1481684952,'127.0.0.1',1481684952,'127.0.0.1',0,0,0,0,0,1481684952),(11,'','','bdf8c126d07dda8a3bf37ac37a5e6418','crlpwj@163.com','',0,'',0,1481685232,'127.0.0.1',1481685232,'127.0.0.1',0,0,0,0,0,1481685232),(12,'13535134940','','123456','crlpwj@163.com','13535134940',0,'',2,0,'0',0,'0',0,0,0,0,0,1486347946),(13,'chenrenli','萌友1号','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'upload/member/avatar/20170308/2539eecdf62477fc9c11f18a44599fa2.png',0,1488957403,'127.0.0.1',1488957403,'127.0.0.1',0,0,0,0,0,1488957403),(14,'chenrenli2','男神1号','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'static/images/default_man_face.png',0,1488959860,'127.0.0.1',1488959860,'127.0.0.1',0,0,0,0,0,1488959860),(15,'ali','女神1号','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'static/images/default_women_face.png',0,1488960651,'127.0.0.1',1488960651,'127.0.0.1',0,0,0,0,0,1488960651),(16,'nvshen','女神1号','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'static/images/default_women_face.png',0,1488960717,'127.0.0.1',1488960717,'127.0.0.1',0,0,0,0,0,1488960717),(17,'nvshen2','女神2号','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'static/images/default_women_face.png',0,1488960966,'127.0.0.1',1488960966,'127.0.0.1',0,0,0,0,0,1488960966),(18,'crlpwj','女神3号','bdf8c126d07dda8a3bf37ac37a5e6418','','',0,'static/images/default_women_face.png',0,1488966321,'192.168.0.197',1488966321,'192.168.0.197',0,0,0,0,0,1488966321);

/*Table structure for table `jp_member_append` */

DROP TABLE IF EXISTS `jp_member_append`;

CREATE TABLE `jp_member_append` (
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `member_intro` varchar(255) NOT NULL DEFAULT '' COMMENT '个人简介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `jp_member_append` */

insert  into `jp_member_append`(`member_id`,`member_intro`) values (8,'居安思危，忧患意思');

/*Table structure for table `jp_member_feed` */

DROP TABLE IF EXISTS `jp_member_feed`;

CREATE TABLE `jp_member_feed` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `member_nickname` char(80) NOT NULL DEFAULT '' COMMENT '昵称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '代表图片',
  `thumb_image` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `like_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '喜欢数量',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏:0否,1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `jp_member_feed` */

insert  into `jp_member_feed`(`id`,`member_id`,`member_nickname`,`image`,`thumb_image`,`title`,`content`,`like_num`,`create_time`,`update_time`,`is_hidden`) values (1,8,'','','','','fdfsa',0,1484125893,0,0),(2,8,'','','','','  到爱尔兰印象最深刻的就是五彩缤纷小房子规则整齐排列在街道上， <img src=\"/static/img/a2.jpg\" alt=\"\">\r\n这样的小镇很多，爱戴尔是其中一座，每家花园盛放着姹紫嫣红的花朵，以成片的茅草屋顶与绚丽色彩墙面组成的街道吸引很多欧洲人前往。\r\n',0,1484201149,0,0),(3,8,'','','','','fdsafsa',0,1484287969,0,0),(4,8,'','','','','fdsafsa',0,1484288078,0,0),(5,8,'','','','','fffdsafdsafsa',0,1484530452,0,0),(9,8,'fdsafasfdsafdsa','/upload/feed/images/20170203/91e64c0d24515f05adfe392c22e1f5ed.jpg','/upload/feed/images/20170203/91e64c0d24515f05adfe392c22e1f5ed_380X180.jpg','','<img src=\"/upload/feed/images/20170203/91e64c0d24515f05adfe392c22e1f5ed.jpg\" alt=\"2017-02-03 10:07\">',0,1486087655,0,0),(10,8,'fdsafasfdsafdsa','/upload/feed/images/20170203/9b0341db2f49222e0e308c7664b1f486.png','/upload/feed/images/20170203/9b0341db2f49222e0e308c7664b1f486_380X180.png','','<p>产品运营方案.</p><p><br></p><p><img src=\"/upload/feed/images/20170203/9b0341db2f49222e0e308c7664b1f486.png\" alt=\"2017-02-03 11:54\"><br></p>',0,1486094099,0,0),(11,8,'fdsafasfdsafdsa','','','','<p>过年上班，提不起精神</p><p><br></p><p><img src=\"/upload/feed/images/20170203/245dec4b2756a4df2c25fef7d65aed7f.jpg\" alt=\"2017-02-03 11:56\"><br></p><p><br></p><p>不论怎么样</p><p><br></p><p><img src=\"/upload/feed/images/20170203/2f6f87aae79176a706c29c575ee8736d.png\" alt=\"2017-02-03 11:57\"><br></p>',0,1486094233,0,0),(12,8,'fdsafasfdsafdsa','','','','<p>的飞洒发</p><p><img src=\"/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b.png\" alt=\"2017-02-03 14:19\"><br></p><p>范德萨范德萨</p><p><br></p><p><img src=\"/upload/feed/images/20170203/ada156b8fd758d2ab7c54f61db5d6770.png\" alt=\"2017-02-03 14:20\"><br></p><p>法法师</p>',0,1486103748,0,0),(13,8,'fdsafasfdsafdsa','/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b.png','/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b_380X180.png','','<p>的飞洒发</p><p><img src=\"/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b.png\" alt=\"2017-02-03 14:19\"><br></p><p>范德萨范德萨</p><p><br></p><p><img src=\"/upload/feed/images/20170203/ada156b8fd758d2ab7c54f61db5d6770.png\" alt=\"2017-02-03 14:20\"><br></p><p>法法师</p>',0,1486105775,0,0),(14,8,'fdsafasfdsafdsa','/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b.png','/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b_380X180.png','','<p>的飞洒发</p><p><img src=\"/upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b.png\" alt=\"2017-02-03 14:19\"><br></p><p>范德萨范德萨</p><p><br></p><p><img src=\"/upload/feed/images/20170203/ada156b8fd758d2ab7c54f61db5d6770.png\" alt=\"2017-02-03 14:20\"><br></p><p>法法师</p>',0,1486105795,0,0),(15,8,'fdsafasfdsafdsa','/upload/feed/images/20170204/7ca68619dad874a00a8611023d379795.jpg','/upload/feed/images/20170204/7ca68619dad874a00a8611023d379795_380X180.jpg','','<p><img src=\"/upload/feed/images/20170204/7ca68619dad874a00a8611023d379795.jpg\" alt=\"2017-02-04 14:13\"></p><p>hello，不错</p>',0,1486188816,0,0),(16,8,'fdsafasfdsafdsa','/upload/feed/images/20170204/a6f1877cda690590a825b1ca8d1da5d7.jpg','/upload/feed/images/20170204/a6f1877cda690590a825b1ca8d1da5d7_380X180.jpg','','不错<img src=\"/upload/feed/images/20170204/a6f1877cda690590a825b1ca8d1da5d7.jpg\" alt=\"2017-02-04 16:10\">',0,1486195832,0,0),(17,17,'女神2号','','','','fdsafdsa',0,1488960990,0,0),(18,18,'女神3号','','','','<p>今天很不ok</p><p><img src=\"http://192.168.0.197/upload/feed/images/20170308/45e2d91ce3c061d730e2ce13dd28533b.jpg\" alt=\"2017-03-08 17:46\"><br></p>',0,1488966399,0,0);

/*Table structure for table `jp_member_feed_like` */

DROP TABLE IF EXISTS `jp_member_feed_like`;

CREATE TABLE `jp_member_feed_like` (
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `member_nickname` char(80) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `feed_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日志ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `jp_member_feed_like` */

insert  into `jp_member_feed_like`(`member_id`,`member_nickname`,`feed_id`) values (8,'fdsafasfdsafdsa',8),(8,'fdsafasfdsafdsa',3),(8,'fdsafasfdsafdsa',10),(8,'fdsafasfdsafdsa',13),(8,'fdsafasfdsafdsa',2),(8,'fdsafasfdsafdsa',14);

/*Table structure for table `jp_member_feed_tag` */

DROP TABLE IF EXISTS `jp_member_feed_tag`;

CREATE TABLE `jp_member_feed_tag` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(200) NOT NULL DEFAULT '' COMMENT '标签名称',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `jp_member_feed_tag` */

/*Table structure for table `jp_member_feed_talk` */

DROP TABLE IF EXISTS `jp_member_feed_talk`;

CREATE TABLE `jp_member_feed_talk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日志ID',
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `member_nickname` char(80) NOT NULL DEFAULT '' COMMENT '昵称',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏:0否,1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `jp_member_feed_talk` */

insert  into `jp_member_feed_talk`(`id`,`feed_id`,`member_id`,`member_nickname`,`content`,`create_time`,`update_time`,`is_hidden`) values (1,5,8,'','fdsafdsa',1484532075,0,0),(2,5,8,'','fdsafdsa',1484532599,0,0),(3,5,8,'','fdsafdsa',1484533117,0,0),(4,5,8,'','\n              到爱尔兰印象最深刻的就是五彩缤纷小房子规则整齐排列在街道上，这样的小镇很多，爱戴尔是其中一座，每家花园盛放着姹紫嫣红的花朵，以成片的茅草屋顶与绚丽色彩墙面组成的街道吸引很多欧洲人前往。\n                ',1484533320,0,0),(7,12,8,'fdsafasfdsafdsa','不错，挺好的，哈哈',1486187787,0,0),(8,12,8,'fdsafasfdsafdsa','不错，挺好的，哈哈',1486187803,0,0),(9,12,8,'fdsafasfdsafdsa','工作顺利',1486188142,0,0),(10,12,8,'fdsafasfdsafdsa','你好',1486188166,0,0),(11,14,8,'fdsafasfdsafdsa','fdsafdsa',1486191370,0,0),(12,14,8,'fdsafasfdsafdsa','fsdafdsa',1486191825,0,0),(13,16,8,'fdsafasfdsafdsa','不错',1486196174,0,0),(14,14,8,'fdsafasfdsafdsa','空间',1486197795,0,0);

/*Table structure for table `jp_member_subscribe` */

DROP TABLE IF EXISTS `jp_member_subscribe`;

CREATE TABLE `jp_member_subscribe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cate_name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `member_nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '会员昵称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='用户订阅分类表';

/*Data for the table `jp_member_subscribe` */

insert  into `jp_member_subscribe`(`id`,`cate_id`,`cate_name`,`member_id`,`member_nickname`) values (3,15,'产品设计',8,'fdsafasfdsafdsa'),(7,19,'数据库',8,'fdsafasfdsafdsa'),(8,20,'股票',8,'fdsafasfdsafdsa'),(13,13,'产品策划',8,'fdsafasfdsafdsa'),(18,14,'产品运营',8,'白骨精'),(19,16,'产品分析',8,'白骨精');

/*Table structure for table `jp_member_token` */

DROP TABLE IF EXISTS `jp_member_token`;

CREATE TABLE `jp_member_token` (
  `token_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `token` char(50) NOT NULL DEFAULT '' COMMENT '令牌',
  `client_type` char(50) DEFAULT '' COMMENT '客户端类型',
  `login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `jp_member_token` */

insert  into `jp_member_token`(`token_id`,`member_id`,`token`,`client_type`,`login_time`) values (1,8,'fc460fdc0a530d2305b3bbf8df5c8feb','wap',1481867847),(2,8,'e7b2ec195c6f29712cc1e3d6ab4753d5','wap',1484029373),(3,8,'f2a933b9ea71459744f0a8ac12e7fa13','wap',1484125184),(4,8,'179d90cd09f494a5a210171a2fd613ee','wap',1484125568),(5,8,'2faf8af7f5ca083d14c27cc6f1d95cd0','wap',1484125638),(6,8,'8d26333eacc5108f543b28e329fd95dd','wap',1484125774),(7,8,'27f9bfc39eaf1e29d7e766c258fd69b1','wap',1486195751),(9,8,'a3f2f8ef1cf043b64ce2609cd25c72af','wap',1487140870),(10,8,'20f423840ffbb950b1d52d2c8238d82d','wap',1487143863),(11,8,'61d01ac0c73dd708d92b859998061e17','wap',1487927849),(12,13,'3bbadd9b5486624204f63a6fac93d7d6','wap',1488957403),(13,14,'9cb7037c30f7e50f5ba7d89fb9609bb9','wap',1488959860),(14,15,'68e3d78bc18139e4a56efd2018c694bf','wap',1488960651),(15,16,'d9a819a07334e37ccbcb0ea34eadfbd8','wap',1488960717),(16,17,'78df843899f46bbd0b09476db794ab36','wap',1488960966),(17,8,'6ee0e144f7a064e662ebadefda33f878','wap',1488964566),(19,8,'eeec9155e6cd92cf09d9608b146fb676','wap',1488965238),(20,8,'9b13e95a482818871c27684408377a67','wap',1488965333),(21,8,'3fe174d931e457752ed5619dee3a04ad','wap',1488965737),(22,8,'28077760c9526fb1b30995df6226a00c','wap',1488966281),(23,18,'3b6b02034c4638c88e7a9c9ea68be17b','wap',1488966321),(24,8,'42aa32d6c1643b447282099c8ed4a16b','wap',1489042189),(25,8,'414ed3b8f68912ea1d3601a29c6737fd','wap',1489111875),(26,8,'fa5917696e7c0d812faad1886a2679d4','wap',1489112083),(27,8,'e1a49d70cc69225219af566610c0a551','wap',1489653353),(28,8,'b1aad40d1e55045a8525247fac45d915','wap',1489653650),(29,8,'8e1966ebc5f394e61742791ba2c325cd','wap',1490176893),(30,8,'8019c5972cd7acd1e85b6cee4ceef9f3','wap',1490247792);

/*Table structure for table `jp_setting` */

DROP TABLE IF EXISTS `jp_setting`;

CREATE TABLE `jp_setting` (
  `name` varchar(50) NOT NULL COMMENT '名称',
  `value` text COMMENT '值',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统设置表';

/*Data for the table `jp_setting` */

insert  into `jp_setting`(`name`,`value`) values ('webslider_index','a:3:{i:0;a:3:{s:5:\"title\";s:6:\"天工\";s:5:\"image\";s:62:\"upload\\webslider/20170218\\eddd819062710cd6e010bf0254b4fbfc.png\";s:3:\"url\";s:20:\"http://www.baidu.com\";}i:1;a:3:{s:5:\"title\";s:4:\"fdas\";s:5:\"image\";s:62:\"upload\\webslider/20170218\\908efee83027f754b54cc86cff92ba28.png\";s:3:\"url\";s:20:\"http://www.baidu.com\";}i:2;a:3:{s:5:\"title\";s:6:\"天工\";s:5:\"image\";s:62:\"upload\\webslider/20170218\\557fae736a5431f2b1a92afc6e667e29.png\";s:3:\"url\";s:0:\"\";}}');

/*Table structure for table `jp_temp_attachment` */

DROP TABLE IF EXISTS `jp_temp_attachment`;

CREATE TABLE `jp_temp_attachment` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '临时附件ID',
  `src` varchar(255) DEFAULT NULL COMMENT '临时附件路径',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `jp_temp_attachment` */

insert  into `jp_temp_attachment`(`file_id`,`src`,`create_time`,`update_time`) values (1,'upload/feed/images/20170120/23a6e0c22ffe7a5bdae1d2ee8195bede.jpg',1484881132,1484881132),(2,'upload/feed/images/20170120/6eb6cac37077d69ddcb8b4c030737cec.png',1484881456,1484881456),(3,'upload/feed/images/20170120/251e81dd395d9789294f8024ec29c396.jpg',1484882286,1484882286),(4,'upload/feed/images/20170120/f50871b9dea997a7949316090403843f.png',1484882309,1484882309),(5,'upload/feed/images/20170120/214141717562b3352166100e665cb70d.png',1484882368,1484882368),(6,'upload/feed/images/20170120/c07a5a3745c80afff5b3c99f1fb63b92.png',1484882587,1484882587),(7,'upload/feed/images/20170120/352083ef65d205a7878389014122c24d.png',1484882678,1484882678),(8,'upload/feed/images/20170203/91e64c0d24515f05adfe392c22e1f5ed.jpg',1486087652,1486087652),(9,'upload/feed/images/20170203/9b0341db2f49222e0e308c7664b1f486.png',1486094086,1486094086),(10,'upload/feed/images/20170203/245dec4b2756a4df2c25fef7d65aed7f.jpg',1486094205,1486094205),(11,'upload/feed/images/20170203/2f6f87aae79176a706c29c575ee8736d.png',1486094230,1486094230),(12,'upload/feed/images/20170203/2bb1e040fd565636b0be8397727fa06b.png',1486102787,1486102787),(13,'upload/feed/images/20170203/1def3e37ef94bd0036faab968a5b05f7.png',1486102833,1486102833),(14,'upload/feed/images/20170203/ada156b8fd758d2ab7c54f61db5d6770.png',1486102848,1486102848),(15,'upload/feed/images/20170204/7ca68619dad874a00a8611023d379795.jpg',1486188804,1486188804),(16,'upload/feed/images/20170204/a6f1877cda690590a825b1ca8d1da5d7.jpg',1486195824,1486195824),(17,'upload/topic_cate/20170215/6020c5095e773749fc307f217b0dec5d.jpg',1487129918,1487129918),(18,'upload/topic_cate/20170215/342bf050dbb869c17baeabeb8d040a3d.png',1487130004,1487130004);

/*Table structure for table `jp_topic` */

DROP TABLE IF EXISTS `jp_topic`;

CREATE TABLE `jp_topic` (
  `thread_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '帖子ID',
  `author_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作者ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) NOT NULL COMMENT '帖子标题',
  `content` text NOT NULL COMMENT '帖子内容',
  `is_hot` tinyint(1) unsigned DEFAULT '0' COMMENT '是否为热帖，0-否 1-是',
  `is_essence` tinyint(1) unsigned DEFAULT '0' COMMENT '是否为精华，0-否 1-是',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '是否置顶，0-否 1-是',
  `reply_total` mediumint(8) unsigned DEFAULT '0' COMMENT '回复总数',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '是否删除，0-否 1-是',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `jp_topic` */

insert  into `jp_topic`(`thread_id`,`author_id`,`cate_id`,`title`,`content`,`is_hot`,`is_essence`,`is_top`,`reply_total`,`is_delete`,`create_time`,`update_time`) values (2,0,1,'fdsafdsa','fdsafdsa',1,0,0,0,0,1483674716,1483674716),(3,0,10,'hello ,你好','不错，搞质量社区',0,0,0,0,0,1486345777,1486345777),(4,0,14,'fsadfdas','fdsafdsa',1,0,0,0,0,1486350634,1486350634),(11,14,13,'产品策划','创意产品',0,0,0,0,0,1488959930,1488959930),(7,8,19,'MySQL分布式事务','分布式事务',0,0,0,0,0,1486725646,1486725646),(8,8,13,'产品流量运营','<p>当你对流量运营的认识，对流量工具的认识，对互联网流量相关名词的认识达到和我一致水平的时候，恭喜你，你达到了入门标准。</p><p>我跟一些创业团队分享也是这么说，如果你觉得，你听了我的分享，有收获，感觉学到了很多，对不起，其实你可能还没入门。</p><p>这话很残酷，但是是事实。</p><p>我们说，创业是否一定要懂流量运营，实话说，你要较真来讲，真不一定。</p><p>我们说，不懂流量运营是否就做不出爆款产品，实话说，也真不一定。</p><p>但我所见到的创业者，相当高比例的大公司背景创业者，高学历创业者，名校海归创业者，最大的困境是不懂流量运营。</p><p>由于幸存者偏差，我们看到不少成功者告诉你，做好产品，流量运营并不是必须的，但是有太多太多创业团队死在这个环节上，而他们，对不起，一点动静你都听不到。</p><p>那么怎样才算是深入，以及精通？</p><p>这就是为什么我要强调《没有捷径！没有捷径！没有捷径！》这篇。</p><p>我告诉了你一些脉络，一些工具，你花多少时间去尝试验证，实践呢？</p><p>如果你不去自己做验证，做实践，针对线上真实的业务数据做分析，你读再多的所谓干货有蛋用啊！！！</p><p>网上的文章，有些是不错的，有些关于流量运营的文章确实也有价值，但问题在哪里呢？如果你实践操作中，遇到一些真实的困扰，或者你分析过很多数据，总觉得还有一些关键点没有想明白，突然网上有篇文章，让你一下子对这个问题豁然开朗，这个确实价值极大；（比如说，我写过好几次，点击提权，有很多读者说看不懂，但有过SEO和ASO实战经验的一些读者，可能一看就恍然大悟。当然，真正的高手看完后会觉得，这不都是基础常识么）但问题是，你连一点实践基础都没有，一点操作经验都没有，光看一堆所谓干货，你真能理解其中的意义么？真不能，反正我做不到。</p><p>那么说具体点</p><p>比如SEO，如何深入：</p><ol><li>你对百度热榜的数据是否持续关注，你对不同领域热词，常见词根，搜索指数是否持续有分析。</li><li>你遇到一些搜索结果中不认识的网站是否有通过第三方工具去分析其来路的习惯，你每天分析几个网站的访问来路。</li><li>你对自己网站的搜索来路是否了如指掌，对搜索引擎更新频率和收录数字是否了如指掌，是否经常去搜索引擎的站长平台查看相关的建议和提示。你每天看几遍自己的网站统计报告。都关心过哪些指标。</li><li>你是否对竞品网站或你认为搜索引擎特别优待的网站做过深入的分析，比如外链，比如关键词密度，比如收录数，收录频次。</li></ol><p>简单说，我介绍过爱站网，介绍过semrush，你平均一天花多少时间在类似的工具上，都分析过多少关键词，多少网站，如果一年算下来，平均一天对这些数据的分析和研究少于一小时，我实话告诉你，你在这个领域肯定深入不了。</p><p>确实有很多细节我都没展开，没法展开，我在线下分享会的时候，为了给大家一些概念，随便搜几个关键词，看到 有权值链接出售的广告，很多人就看不懂了，什么叫网站搜索权值，权值链接的意义在哪里，百度是怎么对待权值链接的，Google是怎么对待权值链接的。随便一个搜索优化的细节，都能有一条灰色产业链在支持，想想你总共要花多少时间去研究。</p><p>同理，如果你想在社交流量方面深入下去：</p><p>1、你是否分析过新榜的榜单，对排名靠前的所有公众账号是否有基本认识，对上升较快的账号是否有持续的关注和分析。</p><p>2、你加过多少好友，加入多少微信群和QQ群，对容易传播的内容有怎样的认识。 甚至包括你都使用了哪些表情工具，对不同表情的流传人群和热度有怎样的认识？是否认真思考过这方面的东西？</p><p>3、在知乎回答过多少问题，主持过几次live，其他知识分享平台呢？是否在豆瓣主持过小组，在贴吧做过吧主，有多少用户的吧主？在百度知道，百科中有多少参与，对这里的流量是否有概念？ 是否尝试在任意一个平台经营大号，以及效果如何？</p><p>如果是做海外，instgram，youtube，facebook，twitter分析和观察过多少大号，以及是否研究过他们的用户构成，推广途径和吸引用户转发分享的关键要素。</p><p>我猜有些人可能根本没意识到其实在facebook, twitter, youtube上，一样有很多草根大号。</p><p>4、是否对AB站，典型视频网站的热门原创视频，直播网站的热门网红，以及快手 有跟踪了解，分析其热门内容的用户构成，传播途径，以及内容的传播要点。</p><p>一样的，需要大量时间的分析，整理，对比测试，才能有正确的认识。</p><p>我们知道厦门有很多自媒体大号，有非常出色的草根大号运营团队，那么，我有幸听过相关创业者的分享，你以为人家拍脑袋一个好主意就做成了？其实是一开始做了多个不同名称的账号，多种不同的内容形态，然后选择不同的策略去发布，不断的对比测试，根据发布后的转发率，反馈率，吸粉率做反思，做总结，最后整理出行之有效的一套传播方法，没错，就是套路，但这也是花很多时间精力观察，测试整理总结出来的。</p><p>聪明人灵机一动就有了好主意，好办法，那都是脑残电视剧的梗，现实都是不断摸爬滚打摔练出来的。</p><p>再同理，如果想做电商搜索优化，玩电商流量。</p><p>对淘宝的搜索排名机制是否认真分析过，你说你又不是程序员怎么分析。</p><p>把所有可能影响排名的要素清单列出来，比如销量，单品好评率，店铺等级，店铺整体评分，关键词密度，关键词位置，价格区间，上架更新时间，然后搜索热门词，看搜索结果，一条条要素往上靠，去整理前三十条结果，每天分析几十个词，几百条结果，然后总结，反推排名规律。</p><p><img src=\"/upload/topic/20170215/2a359694c8391549b5fae4b29413c1d9.jpg\" alt=\"2017-02-15 15:32\"><br></p><p>如果做海外，可以分析amazon，分析ebay，如果做东南亚，分析lazada，分析qoo10。</p><p>我说过，我知道有高手做淘宝搜索优化，也知道有高手做ebay搜索优化，都是有实战成功案例的，有些读者觉得不可能，哪有那么容易。就说个最简单的，为什么刷单成为一个非常大的灰色产业，不就是为了刷排名么，按销量是个常见用户的排序手段，那好，你知道如何通过数据分析出谁在刷单不。 但为了弄排名是否只有刷单？或者说难听点，刷单了是否就一定有好排名？</p><p>流量里的学问非常多，其实细分很多领域，其中任何一个领域能做到精通，都是非常厉害的角色。</p><p>比如说，流量采购，光是单独能把Facebook投放做的精通的，就已经是行业内争抢的人才了，那么，这里的规则和逻辑是什么，精通的人才为什么稀缺？都是在实际真实投放里各种交学费喂出来的。现在，不说你有多精通于投放吧，就说一点，为facebook投放做优化支持服务的公司和产品，最好的都有谁，都擅长什么领域，其产品特性和价值在哪里？ 你觉得看一篇公众号就都能学到么？就算今天我给你个当前的列表，六个月后，会怎样？也许有新的入局者做的更好，也许有的已经效果变差，也许FB出台了新策略大家重新洗牌，不自己随时去跟踪，随时去了解，随时去研究，谁能教你？</p><p><img src=\"/upload/topic/20170215/f2ce5def0163ec9c711c230b20e5b639.png\" alt=\"2017-02-15 15:32\"><br></p><p>做好facebook投放就需要深入分析研究很长时间，那做Google Adwords投放呢？还不用说Adsense 的策略又不一样。长尾词的挖掘工具是什么，自动定价策略怎么设计，回报率的分析工具用什么，这又是一套路数，最好的SEM第三方公司和产品有哪些，如何分析竞品的投放策略，还记得我推荐过的semrush.com么？</p><p>Chinaz，admin5，im286是中国草根互联网创业者的三大社区，当然，时代不同了，实话说，大体来说，草根站长的时代已经过去了。但我认为，如果你想了解流量运营的一些常识，一些基本的东西，花点时间去扒一扒这里的东西，会让你对互联网的流量玩法和认识有彻底的改变。 很久很久以前有从传统领域转型到互联网的大佬，人家最初对互联网的玩法也是一无所知，结果就是在某个站长社区里，从头到尾把所有热门帖子看了一遍，把不懂的东西都看明白了，然后拉团队实操，结果就是做起来了，而且很多流量的玩法，咳咳，说实话，放现在来说，我个人觉得也少有人可以匹敌。</p><p>这里多说一句，admin5的交易区，你会发现，几万粉的公众号，日活几万ip的网站，经常白菜价卖，这个估值体系跟你经常从新闻媒体里看到的所谓创业故事完全是两个世界，为什么呢？人家搞几万粉，几万ip是成本很低的事情。当然，你可以说他们流量价值不高，但问题是，很多精英创业者，硬是搞不定这事，连价值不高的用户，都要花重金去砸，最后还留不住。</p><p>最后回来，还是那句话，没有捷径，没有捷径，没有捷径，流量运营分很多领域，每个领域想要深入一点都需要投入大量的时间和精力，去分析成功案例，去做信息的采集和验证，去实操，甚至去交学费。</p><p>那么，到底流量运营在企业和市场中有多重要呢？</p><p>越简单同质化的生意，越依赖于流量运营。</p><p>或者换过来说，你如果擅长流量运营，你做最简单的生意就能赚钱，比如极端一点，连产品都不需要的流量采购套利。（能做成这事的，我确实认识几个，但整体来说，万里挑一吧。）</p><p>如果你拥有极为牛逼的技术，产品和商业模式，也许流量运营真的没那么重要。</p><p>不幸的是，不少精英创业者只是自以为拥有最牛逼的技术，产品和商业模式，却极为瞧不起流量运营。</p><p>我不认为我的文章能够教会你真正的知识和能力，我只是提供一些我认为有帮助的提示，方向，剩下的，希望读者能自己多努力，谁能走的更远，变得更强，还是需要自己下足够的功夫。</p>',0,0,0,0,0,1487143969,1487143969),(9,8,14,'产品运营方案','<p>布线，最重要的是“整体思维”，学会理解和运用事物的关联。</p><p>就像搭建一个电路，要让灯亮起来，那“导线连接”的布置就必须要正确，能够将线路上的“元器件”有效地组织和串联起来。</p><p>新用户转化的流程就是这样一个“线性”体验，是一个完整而相关的路径，我们不能将转化路径上的各个设计点孤立分割。</p><p>包括前文讲到的“关键环节”的提升方法、把握心理的细节技巧，如果我们只看到了这些“点”，而没有思路、没有章法地运用，或许对单点来讲看似有效，但实际上没有利用点之间的相关性，将转化效果发挥到最大，甚至对整体来讲起到负作用。</p><h2>1 源头</h2><p>转化这根线的“线头”应该是哪个环节，从哪里开始抓起？其实前文有反复提及，应该是用户知道我们的产品，并且有下载/使用意愿的时候。是在产品之外。</p><p>但是很多产品经理经常犯的错误是“本位主义”，只研究“产品范围”之内的事情，“用户还没进到产品里面呢，我可管不着”。然后从用户进到产品开始，有的甚至只从登录或者浏览某个详情页面开始看。</p><p>这样做效果可能会有，但是非常有限，还有很多可能性没有充分释放出来。我们不应把“产品”的边界定义得如此狭隘，它不应只是个冷冰冰的功能聚合体摆在那里。</p><p>当我们产品为用户所知的时候，我们的服务其实就已经开始。产品提供什么样的价值，用户能得到什么，这些信息的输出就是我们在向用户伸手，把他们迎接进来（这个画风好像哪里不对…）</p><p>用户进入后的转化路径怎么设计，便取决于用户的来源，也就是用户选择我们的因由。</p><h3><span>1.1 来源</span></h3><p>这个来源从推广营销的角度出发，可以大致分为：</p><ul><li><span>自然用户：</span>浏览、搜索、推荐等不直接通过明确的营销，自然流入的用户，也就是没有占推广费用。</li><li><span>营销用户：</span>由外部市场或合作中投放的营销资源，而直接带来的新增用户，通常是受到活动营销，或者是服务价值的宣传。</li><li><span>导流用户：</span>通常是出于一些战略目的，通过站内运营，从一个产品形态导入到另一个。如从PC站或触屏站，导入到移动端。对于全站来讲不是严格意义上的新用户，但对被导入的产品端来讲也属于新进用户。</li></ul><p>做转化，先搞清楚新用户的构成。是某一个类型独大，还是各个类型都不少。这决定了后续这个转化“线”该如何布置，我的重点应该放在哪？我需要做些什么来支撑这样的架构？</p><p><img src=\"http://jinpin/upload/topic/20170216/0da94303e17aa255b38f3c35a598e927.jpg\" alt=\"2017-02-16 11:06\"><br></p><h3><span>1.2 目的</span></h3><p>此外，从目的角度，可以大致分为：</p><ul><li><span>纯需求：</span>多见于自然用户，商品或服务本身就击中了需求点。</li><li><span>附加值：</span>就是受到形形色色的活动、优惠等等额外利益的吸引，同时又有潜在需求。特别是同质化严重的行业，会大幅依靠这个来揽用户。</li></ul><p>这个也很好理解，还是“用户要什么就第一时间给什么”。</p><h3><span>1.3 判断</span></h3><p>你说的我都懂，但我怎么知道这个用户就是xx来源冲着xx目的来的呢？</p><p>只能说，尽所能地做好这些基础的准备工作。就像我们对于功能要做数据统计埋点，以便做分析评估一样。我们要在一开始就能建立起这样的体系：用户进入时就带着标记，依据这个参数来决定提供怎样的转化路径。</p><p>采用“渠道”标记是最常见的方法，给程序包打标记；或者采用更先进一些的方式，比如某些大数据的手段，可能做得更精细。在此不做展开。</p><h2>2 贯穿</h2><p>明确了源头，接下来其实思路会非常清晰，围绕着这个源头需求点来选择给用户呈现的东西，和设计整个转化流程。</p><p>比如最简化的模型是：</p><ul><li><span>自然用户-纯需求：</span>呈现核心商品或服务、适当探索，快速的交易流程，按需辅以附加值刺激</li><li><span>营销用户-附加值：</span>呈现该特定的活动或优惠，快速的参与和交易流程（通常融为一个流程）</li></ul><p>等等。实际操作可以做更精细的分析和调整。</p><h3><span>2.1 环节</span></h3><p>“线路”贯穿中，在流程环节上需注意的点是：</p><p><span>① 承接环节</span></p><p>可能是最为重要的点，就是用户第一次进入产品时，这也是最容易产生落差或障碍的时候。除非是有特别超预期的东西，否则没有这个“承接”，就是在刷新用户的认知或者漠视用户的目的。</p><p><img src=\"http://jinpin/upload/topic/20170216/aa0212bfe9e5b901ace1455352b32d03.jpg\" alt=\"2017-02-16 11:07\"><br></p><p><span>② 门槛消解</span></p><p>也是前文有提及的，像是登录、支付这些硬门槛的环节，是可以配合着转化思路来实现不同程度的消解的。</p><p>比如不同的营销活动是有不同的特点，以及引发用户不同的心理活动的。可以在领取活动的优惠福利时趁着意愿强烈，优先把登录问题解决掉；也可以把登录扔到最后面非做不可的时候，优先保证活动参与深度。</p><p>这取决于你的活动性质和设计，然后通过数据效果等加以验证。</p>',0,0,0,0,0,1487214441,1487214441),(10,8,15,'产品交互设计','<p>在 Asana 里，勾选完成列表中的一项任务后，会有一只动物从屏幕中间划过的动画（原谅我手残截不到图……）</p><p><br></p><p><img src=\"http://jinpin/upload/topic/20170216/86e08eaf993ae940e439c925313744c5.png\" alt=\"2017-02-16 11:53\"><br></p><p>过去半年里，我做出的一个较大改变是在设计中加强了感性思考的比重，改进传统的偏理性的设计分析流程，在前期更多地关注和思考产品给用户的感知印象应该是怎样的，如何通过设计来营造这样的印象等，这也带来了更多有意思的灵感启发（通过机械地推理分析得到类似想法则要困难得多），后续又可以通过理性思维来有逻辑地把故事说完整，使之有更强的说服力。</p><h2>02&nbsp;追求极致 VS 关注全局</h2><p>追求极致的用户体验本身是对的，但是给产品体验挑刺容易，推动所有细节都实现得尽善尽美却不怎么现实，总是过于纠结细节设计也会让我们更容易陷入丢失全局视野的风险。</p><p>在推动体验细节打磨的时候，我们也要认真思考这次打磨的意义和价值，评估优先级，合理分配时间资源，而不是事无巨细地投入到所有事情中去。在产品迭代节奏普遍偏快的情况下，对于一些用户使用频率低、业务价值不明显的场景（比如大多数产品的「设置」页面），完全可以先上一个实现成本低的方案，后续有一定用户反馈基础再考虑迭代优化。</p><p>另一个极端则是过于为他人着想，过于关注设计方案的实现成本、对于商业价值的促进作用等，甚至直接站在他人的角度来想方案（我犯过这样的错误，结果导致事情复杂化了很多，逐渐背离了最早的设计目标），而失去了一些设计师必要的专注与坚持。</p><h2>03&nbsp;发散创新 VS 克制专注</h2><p>一个真正出色的设计想法往往并非一蹴而就，而是建立在大量想法的基础之上，设计师们都很享受灵感迸发的瞬间，当一个人的力量不足以支撑时，也会有头脑风暴一类的方法来帮我们收集更多灵感。</p><p>当积累了一些不错的好点子之后，我犯过不止一次的错误是会忍不住将它们一股脑全塞进自己的新设计方案，但同时设计产出过多创新想法也有其明显的弊端，一方面噱头过多失去专注，另一方面也更难通过数据来量化分析设计好坏，因为相关的影响因素太多了，后续迭代也会受到更多阻碍。</p><p><img src=\"http://jinpin/upload/topic/20170216/9fce065c319475a89a77959bb5e79e14.png\" alt=\"2017-02-16 11:53\"><br></p><p>在「双钻模型」这一设计方法中，「发散」与「集中」都是其中的重要环节，尽可能开脑洞地发散完创意设想之后，需要评估和选择出最喜欢的方案，再进行后续建模和测试。</p>',0,0,0,0,0,1487217205,1487217205),(12,8,15,'搞搞话题','哦哦OK',0,0,0,0,0,1488965911,1488965911),(13,8,15,'搞搞话题','哦哦OK',0,0,0,0,0,1488965912,1488965912),(14,8,15,'lol咯','跟你同',0,0,0,0,0,1488966002,1488966002),(15,8,15,'辣鸡','呵呵<img src=\"http://192.168.0.197/upload/topic/20170308/a0d25c869177693823b70a482b694f3a.jpg\" alt=\"2017-03-08 17:42\">',0,0,0,0,0,1488966180,1488966180),(16,8,15,'捅','空间',0,0,0,0,0,1489042560,1489042560),(17,8,16,'乐迷','来咯哦哦弄',0,0,0,0,0,1489654223,1489654223),(18,8,16,'咯嘛','Monmouth',0,0,0,0,0,1490247809,1490247809);

/*Table structure for table `jp_topic_cate` */

DROP TABLE IF EXISTS `jp_topic_cate`;

CREATE TABLE `jp_topic_cate` (
  `cate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父类ID',
  `topic_image` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图标',
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `jp_topic_cate` */

insert  into `jp_topic_cate`(`cate_id`,`cate_name`,`parent_id`,`topic_image`) values (10,'互联网产品',0,'upload/topic_cate/20170215/09095d5e402ad0f877da3270fc3de1c9.jpg'),(11,'技术',0,''),(12,'金融',0,''),(13,'产品策划',10,'upload/topic_cate/20170215/21cd80b01e2a29cf45b53fcd46a9d8af.png'),(14,'产品运营',10,''),(15,'产品设计',10,'upload/topic_cate/20170322/3829d989d1c734b60b0b24515d0195d3.png'),(16,'产品分析',10,''),(17,'java',11,''),(18,'nginx调优',11,''),(19,'数据库',11,''),(20,'股票',12,''),(21,'投资',12,''),(22,'fsdafds',12,''),(23,'fsdafdsa',12,''),(24,'fsad',0,''),(25,'fsad',0,''),(30,'互联网产品',0,'upload/topic_cate/20170215/9b43b63f2191d5b2b654d36ab940f254.jpg');

/*Table structure for table `jp_topic_like` */

DROP TABLE IF EXISTS `jp_topic_like`;

CREATE TABLE `jp_topic_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL COMMENT '帖子ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='喜欢帖子表';

/*Data for the table `jp_topic_like` */

insert  into `jp_topic_like`(`id`,`thread_id`,`user_id`,`create_time`,`update_time`) values (6,3,0,1488525113,1488525113),(7,11,14,1488959953,1488959953),(8,15,8,1489653439,1489653439),(9,8,0,1490247683,1490247683);

/*Table structure for table `jp_topic_post` */

DROP TABLE IF EXISTS `jp_topic_post`;

CREATE TABLE `jp_topic_post` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '回复ID',
  `thread_id` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `author_id` int(11) NOT NULL DEFAULT '0' COMMENT '作者ID',
  `reply_post_id` int(11) DEFAULT '0' COMMENT '某楼层回复ID',
  `content` text NOT NULL COMMENT '回复内容',
  `thumbsup_total` mediumint(9) DEFAULT '0' COMMENT '点赞数',
  `is_delete` tinyint(4) DEFAULT '0' COMMENT '是否被删除，0-否 1-是',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `jp_topic_post` */

insert  into `jp_topic_post`(`post_id`,`thread_id`,`author_id`,`reply_post_id`,`content`,`thumbsup_total`,`is_delete`,`create_time`,`update_time`) values (1,2,8,0,'fdsafdsa',0,0,1484030436,1484030436),(2,2,8,0,'看',0,0,1486197219,1486197219),(3,2,8,0,'看',0,0,1486197221,1486197221),(4,2,8,0,'看',0,0,1486197221,1486197221),(5,2,8,0,'看',0,0,1486197222,1486197222),(6,2,8,0,'看',0,0,1486197222,1486197222),(7,2,8,0,'看',0,0,1486197222,1486197222),(8,8,8,0,'fsafdsa',0,1,1487325177,1488528250),(9,10,8,0,'fda',0,0,1487400033,1487400033),(10,10,8,0,'不错，挺好的',0,0,1487400170,1487400170),(11,11,1,0,'不错',0,0,1488964543,1488964543),(12,15,8,0,'不错',0,0,1489653435,1489653435),(13,13,8,0,'邮箱',0,0,1490176942,1490176942);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `migrations` */

insert  into `migrations`(`version`,`migration_name`,`start_time`,`end_time`) values (20170206025313,'AddTable','2017-02-20 09:21:17','2017-02-20 09:21:17'),(20170218134511,'AddTables','2017-02-20 09:21:17','2017-02-20 09:21:17');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
