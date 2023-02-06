

DROP TABLE IF EXISTS `hr_staff_absent_approval`;

CREATE TABLE IF NOT EXISTS `hr_staff_absent_approval` (
  `approve_id` int(11) NOT NULL AUTO_INCREMENT,
  `absent_id` varchar(36) NOT NULL,
  `approved_user` varchar(36) DEFAULT NULL COMMENT 'รหัสผู้อนุมัติ',
  `approved_status` varchar(100) DEFAULT NULL COMMENT 'ผลการพิจารณาอนุมัติ',
  `approved_datetime` datetime DEFAULT NULL COMMENT 'วันที่อนุมัติ',
  `approval_comment` varchar(200) DEFAULT NULL COMMENT 'ความเห็นผู้อนุมัติ',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่นำเสนอขออนุมัติ',
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`approve_id`),
  UNIQUE KEY `absent_id` (`absent_id`,`approved_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;





