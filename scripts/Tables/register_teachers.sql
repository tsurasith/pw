
DROP TABLE IF EXISTS `register_teachers`;


CREATE TABLE IF NOT EXISTS `register_teachers` (
  `teacher_register_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id`          varchar(36) NOT NULL,
  `room_id`             varchar(3) NOT NULL COMMENT 'ห้องเรียน',
  `subject_level`       int(11) NOT NULL COMMENT 'ระดับชั้น 1-6',
  `SubjectCode`         varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `acadyear`            int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester`        int(11) NOT NULL COMMENT 'ภาคเรียน',
  `is_split_class`      varchar(1) NOT NULL COMMENT 'แบ่งกลุ่มเรียน',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`teacher_register_id`),
  UNIQUE(`teacher_id`,`room_id`,`subject_level`, `SubjectCode`, `acadyear`, `acadsemester`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

