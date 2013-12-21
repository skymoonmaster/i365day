CREATE TABLE `user_relation` (
  `user_relation_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id。',
  `user_id_one` int(11)  NOT NULL COMMENT '用户id',
  `user_id_two` int(11)  NOT NULL COMMENT '用户id',
  `relation` tinyint(3) NOT NULL COMMENT '关系',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` char(64) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;