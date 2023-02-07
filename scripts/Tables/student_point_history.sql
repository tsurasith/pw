
DROP TABLE IF EXISTS `student_point_history`;

CREATE TABLE IF NOT EXISTS `student_point_history` (
  `point_id` int(11) NOT NULL AUTO_INCREMENT,
  `dis_id` INT(11) NULL,
  `student_id` varchar(5) NOT NULL,
  `point_type` varchar(1) NOT NULL COMMENT '1=ADD,0=DEDUCT',
  `point_changed` int(11) NOT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  `point_comments` varchar(255) NULL,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NOT NULL COMMENT 'keep in UUID format',
  `updated_datetime` DATETIME NULL ,
  `updated_user` varchar(36) COMMENT 'keep in UUID format',
  PRIMARY KEY (`point_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


