
-- table for temporary import

DROP TABLE IF EXISTS `register_students_grades_import`;


CREATE TABLE IF NOT EXISTS `register_students_grades_import` (
  `import_id` int(11) NOT NULL AUTO_INCREMENT,
  `import_key` varchar(45) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `SubjectCode` varchar(6) NOT NULL COMMENT 'รหัสวิชา',
  `acadyear` int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester` int(11) NOT NULL COMMENT 'ภาคเรียน',
  `prefix`          varchar(40) NOT NULL,
  `firstname`       varchar(40) NOT NULL,
  `lastname`        varchar(40) NOT NULL,
  `point_1` decimal(5,2) DEFAULT NULL COMMENT 'คะแนนก่อนกลางภาค',
  `point_mid` decimal(5,2) DEFAULT NULL COMMENT 'คะแนนกลางภาค',
  `point_2` decimal(5,2) DEFAULT NULL COMMENT 'คะแนนหลังกลางภาค',
  `point_final` decimal(5,2) DEFAULT NULL COMMENT 'คะแนนสอบปลายภาค',
  `point_100` decimal(5,2) DEFAULT NULL COMMENT 'คะแนนรวม',
  `grade` varchar(3) DEFAULT NULL COMMENT 'ผลการเรียน',
  `club_code` int(4) UNSIGNED ZEROFILL DEFAULT '0000' COMMENT 'รหัสชุมนุม',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL COMMENT 'keep in UUID format',
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL COMMENT 'keep in UUID format',
  `subject_register_id` varchar(36) NOT NULL,
  PRIMARY KEY (`import_id`),
  UNIQUE KEY `student_id` (`student_id`,`SubjectCode`,`acadyear`,`acadsemester`,`subject_register_id`),
  KEY `import_key` (`import_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

