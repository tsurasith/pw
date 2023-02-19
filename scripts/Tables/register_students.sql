
DROP TABLE IF EXISTS `register_students`;


CREATE TABLE IF NOT EXISTS `register_students` (
  `register_student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id`          varchar(5) NOT NULL,
  `SubjectCode`         varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `acadyear`            int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester`        int(11) NOT NULL COMMENT 'ภาคเรียน',
  `point_1`             decimal(5,2) NULL COMMENT 'คะแนนก่อนกลางภาค',
  `point_mid`           decimal(5,2) NULL COMMENT 'คะแนนกลางภาค',
  `point_2`             decimal(5,2) NULL COMMENT 'คะแนนหลังกลางภาค',
  `point_final`         decimal(5,2) NULL COMMENT 'คะแนนสอบปลายภาค',
  `point_100`           decimal(5,2) NULL COMMENT 'คะแนนรวม',
  `grade`               varchar(3) NULL COMMENT 'ผลการเรียน',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `subject_register_id` varchar(36) NOT NULL,
  PRIMARY KEY (`register_student_id`),
  UNIQUE(`student_id`,`SubjectCode`, `acadyear`, `acadsemester`,`subject_register_id`),
  FOREIGN KEY (`subject_register_id`) REFERENCES register_subjects(`subject_register_id`) 
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

