CREATE TABLE `message` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id。',
  `type` tinyint(2) unsigned NOT NULL COMMENT '消息类型',
  `sender_id` int(11) unsigned NOT NULL COMMENT '发送者id',
  `sender_name` char(64) NOT NULL COMMENT '发送者昵称',
  `receiver_id` int(11) unsigned NOT NULL COMMENT '接收者id',
  `is_read` tinyint(1) unsigned NOT NULL COMMENT '是否阅读',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `mod_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `diary_id` int(11) DEFAULT NULL COMMENT '日记id。',
  `diary_title` char(64) DEFAULT NULL COMMENT '日记标题',
  `count` int(11) unsigned NOT NULL COMMENT '计数',
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `unique_index` (`receiver_id`,`is_read`,`diary_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
