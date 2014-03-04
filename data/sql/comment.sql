CREATE TABLE `comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id',
  `diary_id` int(11) unsigned NOT NULL COMMENT '日记编号',
  `vistor_id` int(11) NOT NULL COMMENT '用户id',
  `vistor_name` char(64) NOT NULL COMMENT '昵称',
  `follow_id` int(11)  NOT NULL COMMENT '回复留言编号',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` char(11) NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`comment_id`),
  KEY `diary_id_status` (`diary_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;