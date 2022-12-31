
DROP TABLE IF EXISTS `user_permission_mapping`;
CREATE TABLE IF NOT EXISTS `user_permission_mapping` (
  `permission_code` varchar(30) NOT NULL,
  `permission_group_code` varchar(30) NOT NULL,
  `created_user` varchar(36) NULL COMMENT 'keep in UUID format',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`permission_code`,`permission_group_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
