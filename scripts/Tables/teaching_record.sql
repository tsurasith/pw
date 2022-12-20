
--
-- Table structure for table `teaching_record`
--

DROP TABLE IF EXISTS `teaching_record`;
CREATE TABLE IF NOT EXISTS `teaching_record` (
  `teacher_id` varchar(36) NOT NULL,
  `teaching_date` varchar(10) NOT NULL,
  `SubjectCode` varchar(6) NOT NULL COMMENT 'รหัสวิชา',
  `weekday` varchar(4) NOT NULL,
  `period` varchar(1) NOT NULL,
  `location` varchar(20) DEFAULT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  `acadslevel` varchar(1) DEFAULT NULL COMMENT 'อนุบาล,ประถมต้น,ประถมปลาย,ม.ต้น,ม.ปลาย',
  `level` varchar(1) DEFAULT NULL COMMENT 'ม.1,2,3,4,...',
  `room` int(1) DEFAULT NULL,
  `room_id` varchar(3) NOT NULL DEFAULT '000',
  `approved_user_id` varchar(36) DEFAULT NULL,
  `approved_datetime` datetime DEFAULT NULL,
  `has_assignment` varchar(1) DEFAULT NULL COMMENT '1 = มีการสั่งงานไว้\r\n0 = ไม่การใบงาน',
  `teaching_details` text,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`teacher_id`,`teaching_date`,`period`,`acadyear`,`acadsemester`,`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;
