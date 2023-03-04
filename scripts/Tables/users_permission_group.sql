
--
-- Table structure for table `user_permission_group`
--

DROP TABLE IF EXISTS `user_permission_group`;
CREATE TABLE IF NOT EXISTS `user_permission_group` (
  `permission_group_id`      int(11) NOT NULL AUTO_INCREMENT,
  `permission_group_code`    varchar(20) NOT NULL,
  `permission_group_name`    varchar(200) DEFAULT NULL,
  `permission_group_details` varchar(255) DEFAULT NULL,
  `created_datetime`         datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`             varchar(36) DEFAULT NULL COMMENT 'keep in UUID format',
  `updated_datetime`         datetime DEFAULT NULL,
  `updated_user`             varchar(36) DEFAULT NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`permission_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_permission_group`
--

INSERT INTO `user_permission_group` (`permission_group_id`, `permission_group_code`, `permission_group_name`, `permission_group_details`, `created_datetime`, `created_user`, `updated_datetime`, `updated_user`) VALUES
(1, 'DIRECTOR', 'ผู้อำนวยการ', 'ผู้อำนวยการ', '2022-12-21 10:32:58', NULL, '2022-12-21 03:32:26', NULL),
(2, 'MANAGEMENT', 'ผู้บริหาร', 'ผู้อำนวยการ / รองผู้อำนวยการ', '2022-12-21 10:34:08', NULL, '2022-12-21 03:33:19', NULL),
(3, 'BOARD', 'คณะกรรมการฝ่ายบริหาร', 'คณะกรรมการฝ่ายบริหาร ประกอบด้วย ผู้อำนวยการ รองผู้อำนวยการ หัวหน้าฝ่าย', '2022-12-21 10:35:19', NULL, '2022-12-21 03:34:17', NULL),
(4, 'TEACHER', 'ครูผู้สอน', 'เป็นสถานะพื้นฐานของครูผู้สอน ครูที่ปรึกษา', '2022-12-21 10:38:20', NULL, '2022-12-21 03:35:30', NULL);


