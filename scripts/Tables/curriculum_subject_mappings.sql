
DROP TABLE IF EXISTS `curriculum_subject_mappings`;
CREATE TABLE IF NOT EXISTS `curriculum_subject_mappings` (
  `curriculum_id`       int(11) NOT NULL AUTO_INCREMENT,
  `curriculum_code`     varchar(30) NOT NULL,
  `SubjectCode`         varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`curriculum_id`,`SubjectCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
