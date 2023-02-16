
DROP TABLE IF EXISTS `curriculum_register`;


CREATE TABLE IF NOT EXISTS `curriculum_register` (
  `register_id`         int(11) NOT NULL AUTO_INCREMENT,
  `curriculum_id`       int(11) comment 'รหัสหลักสูตร' ,
  `acadyear`            int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester`        int(11) NOT NULL COMMENT 'ภาคเรียน',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`register_id`),
  UNIQUE(`curriculum_id`, `acadyear`, `acadsemester`),
  FOREIGN KEY (`curriculum_id`) REFERENCES curriculums(`curriculum_id`) 
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


