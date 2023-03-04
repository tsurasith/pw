
DROP TABLE IF EXISTS `user_permission_code`;


CREATE TABLE IF NOT EXISTS `user_permission_code` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_code` varchar(20) NOT NULL,
  `permission_name` varchar(200) DEFAULT NULL,
  `permission_details` varchar(255) DEFAULT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL COMMENT 'keep in UUID format',
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_permission_code`
--

INSERT INTO `user_permission_code` (`permission_id`, `permission_code`, `permission_name`, `permission_details`, `created_datetime`, `created_user`, `updated_datetime`, `updated_user`) VALUES
(1, 'ALLOWMODULE800', 'เข้าถึงโมดูลกิจกรรมหน้าเสาธง', 'อนุญาตให้เข้าถึงโมดูลการใช้งานกิจกรรมหน้าเสาธง', '2022-12-21 10:43:25', NULL, '2022-12-21 03:41:33', NULL),
(2, 'ALLOWMODULELEARN', 'เข้าถึงโมดูลบันทึกการเข้าเรียน', 'อนุญาตให้เข้าถึงโมดูลการใช้งานบันทึกการเข้าเรียน', '2022-12-21 10:47:11', NULL, '2022-12-21 03:43:41', NULL);

