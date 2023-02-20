
DROP TABLE IF EXISTS `register_clubs`;


CREATE TABLE IF NOT EXISTS `register_clubs` (
  `register_club_id` int(11) NOT NULL AUTO_INCREMENT,
  `club_code`          INT(4) ZEROFILL NOT NULL COMMENT 'รหัสชุมนุม',
  `SubjectCode`         varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `acadyear`            int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester`        int(11) NOT NULL COMMENT 'ภาคเรียน',
  `teacher_id1`         varchar(36) NULL COMMENT 'รหัสครูผู้สอน 1',
  `teacher_id2`         varchar(36) NULL COMMENT 'รหัสครูผู้สอน 2',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `subject_register_id` varchar(36) NOT NULL,
  PRIMARY KEY (`register_club_id`),
  UNIQUE(`club_code`,`SubjectCode`, `acadyear`, `acadsemester`,`subject_register_id`),
  FOREIGN KEY (`subject_register_id`) REFERENCES register_subjects(`subject_register_id`) 
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



