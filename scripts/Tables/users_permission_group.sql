

DROP TABLE IF EXISTS `user_permission_group`;
CREATE TABLE IF NOT EXISTS `user_permission_group` (
  `permission_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_group_code` varchar(30) NOT NULL,
  `permission_group_name` varchar(200) NULL,
  `permission_group_details` varchar(255) NULL ,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime` DATETIME NULL ,
  `updated_user` varchar(36) COMMENT 'keep in UUID format',
  PRIMARY KEY (`permission_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


