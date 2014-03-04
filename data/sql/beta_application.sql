CREATE TABLE `beta_application` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `has_already_started` tinyint(1) unsigned NOT NULL COMMENT '是否已开始记录365',
  `existed_address` char(255) DEFAULT NULL COMMENT '已有地址',
  `reason` text NOT NULL COMMENT '申请理由',
  `email` char(64) NOT NULL COMMENT '申请email',
  `audit_status` tinyint(1) NOT NULL COMMENT '审核状态。1：待审；2：通过；3：拒绝；',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
