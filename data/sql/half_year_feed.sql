CREATE TABLE `half_year_feed` (
  `feed_id` int(11) unsigned NOT NULL COMMENT 'feed id',
  `user_id` int(11) unsigned NOT NULL COMMENT 'feed发布者id',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

