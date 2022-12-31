
DROP TABLE IF EXISTS `user_permission_code`;
CREATE TABLE IF NOT EXISTS `user_permission_code` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_code` varchar(30) NOT NULL,
  `permission_name` varchar(200) NULL,
  `permission_details` varchar(255) NULL ,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime` DATETIME NULL ,
  `updated_user` varchar(36) COMMENT 'keep in UUID format',
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


