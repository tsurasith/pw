
DROP TABLE IF EXISTS `curriculums`;
CREATE TABLE IF NOT EXISTS `curriculums` (
  `curriculum_id`       int(11) NOT NULL AUTO_INCREMENT,
  `curriculum_code`     varchar(30) UNIQUE NOT NULL,
  `curriculum_start_year` varchar(4) NOT NULL,
  `curriculum_level`    varchar(1) NOT NULL COMMENT '3=ม.ต้น,4=ม.ปลาย',
  `curriculum_name`     varchar(200) NULL,
  `curriculum_description` TEXT NULL ,
  `curriculum_status`   varchar(20) NULL DEFAULT 'ACTIVE' ,
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`curriculum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


