CREATE TABLE `diary_ext` (
  `diary_ext_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增id。',
  `diary_id` int(11) unsigned NOT NULL COMMENT '日记id',
  `pic` char(255)  NOT NULL COMMENT '图片',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`diary_ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;