
-- table: learn_subjects
-- keep the subjects from local school program

DROP TABLE IF EXISTS `learn_subjects`;

CREATE TABLE IF NOT EXISTS `learn_subjects` (
  `SubjectCode` varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `SubjectName` varchar(255)  NOT NULL COMMENT 'ชื่อวิชา',
  `SubjectUnit` varchar(3)  NOT NULL COMMENT 'หน่วยกิต',
  `SubjectHour` varchar(3)  NOT NULL COMMENT 'จำนวนชั่วโมง',
  `SubjectType` varchar(15)  NOT NULL COMMENT 'ประเภทวิชา พื้นฐาน/เพิ่มเติม',
  `FirstGroup` varchar(255)  NOT NULL COMMENT 'สาระหลัก',
  `SecondGroup` varchar(255)  NOT NULL COMMENT 'สาระย่อย',
  `SubjectClass` varchar(3)  NOT NULL COMMENT 'ชั้นปีที่เปิดสอน',
  `SubjectYear` varchar(6)  NOT NULL COMMENT 'ปีการศึกษา',
  `acadyear` varchar(4) NULL,
  `acadsemester` varchar(1) NULL,
  `level` varchar(1) NULL,
  `teacher_user_id_list` varchar(500) NULL,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user_id` varchar(36) NULL,
  PRIMARY KEY (`SubjectCode`,`SubjectClass`,`SubjectYear`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;