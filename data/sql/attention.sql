CREATE TABLE `attention` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fans_uid` int(11) unsigned NOT NULL COMMENT '粉丝id',
  `follow_uid` int(11) unsigned NOT NULL COMMENT '关注者id',
  `create_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8