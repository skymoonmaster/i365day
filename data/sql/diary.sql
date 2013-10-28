CREATE TABLE `diary` (
  `diary_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id。',
  `title` char(64)  NOT NULL COMMENT '标题',
  `tags` char(128) NOT NULL COMMENT '标签',
  `pic_desc` char(128) NOT NULL COMMENT '图片描述',
  `thumbnail` char(225) NOT NULL COMMENT '缩略图',
  `visibility` tinyint(3) NOT NULL COMMENT '可见性',
  `date` int(11) NOT NULL COMMENT '日期',
  `date_ts` int(11) NOT NULL COMMENT '日期时间戳',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `fav_num` int(11) NOT NULL DEFAULT '0'  COMMENT '喜欢数',
  `comment_num` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` char(11) NOT NULL COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`diary_id`),
  KEY `create_time_status` (`create_time`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;