
DROP TABLE IF EXISTS `register_subjects`;


CREATE TABLE IF NOT EXISTS `register_subjects` (
  `subject_register_id` varchar(36) NOT NULL,
  `subject_level`       int(11) NOT NULL COMMENT 'ระดับชั้น 1-6',
  `SubjectCode`         varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `acadyear`            int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester`        int(11) NOT NULL COMMENT 'ภาคเรียน',
  `is_split_class`  varchar(1) NOT NULL COMMENT 'แบ่งกลุ่มเรียน',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`subject_register_id`),
  UNIQUE(`subject_level`, `SubjectCode`, `acadyear`, `acadsemester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



