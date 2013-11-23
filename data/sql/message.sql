CREATE TABLE `message` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id。',
  `type` tinyint(2) unsigned NOT NULL COMMENT '消息类型',
  `data` varchar(512) NOT NULL COMMENT '消息数据',
  `receiver_id` int(11) unsigned NOT NULL COMMENT '接收者id',
  `is_read` tinyint(1) unsigned NOT NULL COMMENT '是否阅读',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `mod_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `diary_id` int(11) DEFAULT NULL COMMENT '对于“喜欢”消息类型，需要该字段来存储日记id。',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;