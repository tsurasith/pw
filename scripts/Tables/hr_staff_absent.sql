


DROP TABLE IF EXISTS `hr_staff_absent`;


CREATE TABLE IF NOT EXISTS `hr_staff_absent` (
  `absent_id` varchar(36) NOT NULL,
  `staff_id` varchar(36) DEFAULT NULL COMMENT 'รหัสบุคลากร',
  `start_absent_date` date DEFAULT NULL COMMENT 'วันเริ่มลา',
  `start_absent_time` time DEFAULT NULL COMMENT 'เวลาเริ่มลา',
  `end_absent_date` date DEFAULT NULL COMMENT 'วันสุดท้ายที่ลา',
  `end_absent_time` time DEFAULT NULL COMMENT 'เวลาสิ้นสุดการลา',
  `total_absent` decimal(4,1) DEFAULT NULL COMMENT 'รวมระยะเวลาลา (วัน)',
  `absent_type` varchar(100) DEFAULT NULL COMMENT 'ลาป่วย,ลากิจ,ลาคลอด,ไปราชการ',
  `absent_subtype` varchar(200) DEFAULT NULL COMMENT 'ประเภทย่อยการไปราชการ',
  `absent_details` varchar(500) DEFAULT NULL COMMENT 'เหตุผลที่ลา',
  `contact_information` varchar(500) DEFAULT NULL COMMENT 'ข้อมูลการติดต่อระหว่างลา',
  `request_datetime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'เวลาที่ยืนคำขอ',
  `request_status` varchar(100) DEFAULT NULL COMMENT 'สถานะคำขอ',
  `acadyear` int(4) DEFAULT NULL,
  `acadsemester` int(1) DEFAULT NULL,
  `file_attached_ext1` varchar(200) DEFAULT NULL,
  `file_attached_ext2` varchar(200) DEFAULT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`absent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


