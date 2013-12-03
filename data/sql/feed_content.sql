CREATE TABLE `feed_content` (
  `feed_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'feed id',
  `user_id` int(11) unsigned NOT NULL COMMENT 'feed发布者id',
  `type` tinyint(2) unsigned NOT NULL COMMENT 'feed类型',
  `content` text NOT NULL COMMENT 'feed内容',
  `status` tinyint(2) unsigned NOT NULL COMMENT 'feed状态，1为正常，2为用户删除，3为系统审核删除',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

