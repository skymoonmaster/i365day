CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id。',
  `app_id` tinyint(2) unsigned NOT NULL COMMENT 'app_id',
  `app_uid` int(11) unsigned NOT NULL COMMENT '用户在第三方平台的唯一id',
  `head_portrait` char(225) NOT NULL COMMENT '头像',
  `email` char(255) NOT NULL COMMENT '邮箱',
  `nick_name` char(64) NOT NULL COMMENT '昵称',
  `country` char(64) NOT NULL COMMENT '国家',
  `city` char(64) NOT NULL COMMENT '城市',
  `intro` char(64) NOT NULL COMMENT '简介',
  `camera_brand` char(64) NOT NULL COMMENT '相机品牌',
  `camera_model` char(64) NOT NULL COMMENT '相机型号',
  `fans` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `follows` int(11) NOT NULL DEFAULT '0' COMMENT '关注数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8