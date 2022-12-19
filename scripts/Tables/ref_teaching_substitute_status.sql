
--
-- Table structure for table `ref_teaching_substitute_status`
--

DROP TABLE IF EXISTS `ref_teaching_substitute_status`;
CREATE TABLE IF NOT EXISTS `ref_teaching_substitute_status` (
  `teaching_substitute_status_id` varchar(1) NOT NULL,
  `teaching_substitute_status_description` varchar(255) NOT NULL,
  PRIMARY KEY (`teaching_substitute_status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ref_teaching_substitute_status`
--

INSERT INTO `ref_teaching_substitute_status` (`teaching_substitute_status_id`, `teaching_substitute_status_description`) VALUES
('0', 'สร้างรายการบันทึกการสอนแทนและแจ้งครูผู้สอนแทนทราบ'),
('1', 'ครูผู้สอนยืนยันรับทราบแล้ว'),
('2', 'บันทึกการเข้าสอนแทนแล้ว'),
('3', 'เสร็จสิ้น(วิชาการตรวจสอบแล้ว)');
COMMIT;

