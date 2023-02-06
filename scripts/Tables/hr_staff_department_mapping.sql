
DROP TABLE IF EXISTS `hr_staff_department_mapping`;

CREATE TABLE IF NOT EXISTS `hr_staff_department_mapping` (
  `staff_id` varchar(36) NOT NULL COMMENT 'user_account_id',
  `department_id` int(11) NOT NULL COMMENT 'รหัสฝ่าย',
  `sub_department_id` int(11) NOT NULL COMMENT 'รหัสงานย่อย',
  `acadyear` int(11) NOT NULL COMMENT 'ปีการศึกษา',
  `acadsemester` int(11) NOT NULL COMMENT 'ภาคเรียน',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`staff_id`,`department_id`,`sub_department_id`,`acadyear`,`acadsemester`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


