

-- table: learn_grades
-- keep the grades from local school program

DROP TABLE IF EXISTS `learn_grades`;

CREATE TABLE IF NOT EXISTS `learn_grades` (
  `StudentID` varchar(17)  NOT NULL COMMENT 'รหัสนักเรียนตามเลข running ของ local school',
  `StudentCode` varchar(5)  NOT NULL COMMENT 'รหัสนักเรียนตามเลขปกติ',
  `SubjectCode` varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `Score100` varchar(3)  NOT NULL COMMENT 'คะแนนเต็ม100',
  `Grade` varchar(3)  NOT NULL COMMENT 'ผลการเรียน',
  `RegisterYear` varchar(6)  NOT NULL COMMENT 'ภาคเรียน/ปีการศึกษา',
  `RegisterClass` varchar(10)  NOT NULL,
  `ReGrade` varchar(3) NULL,
  `teacher_user_id` varchar(36) NULL,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user_id` varchar(36) NULL,
  `acadyear` varchar(4) NULL,
  `acadsemester` varchar(1) NULL,
  `level` varchar(1) NULL,
  PRIMARY KEY (`StudentID`,`SubjectCode`,`RegisterYear`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

