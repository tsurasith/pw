
-- table: learn_tables_template
-- use for display teacher teaching schedule

DROP TABLE IF EXISTS `teaching_schedule_display`;

CREATE TABLE IF NOT EXISTS `teaching_schedule_display` (
  `day_name` varchar(36) NOT NULL,
  `weekday` varchar(1) NOT NULL,
  `period1` varchar(100) DEFAULT NULL,
  `period2` varchar(100) DEFAULT NULL,
  `period3` varchar(100) DEFAULT NULL,
  `period4` varchar(100) DEFAULT NULL,
  `period5` varchar(100) DEFAULT NULL,
  `period6` varchar(100) DEFAULT NULL,
  `period7` varchar(100) DEFAULT NULL,
  `period8` varchar(100) DEFAULT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  `teacher_id` varchar(36) NOT NULL,
  PRIMARY KEY (`weekday`,`teacher_id`,`acadyear`,`acadsemester`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
