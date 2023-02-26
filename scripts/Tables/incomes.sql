

DROP TABLE IF EXISTS `incomes`;


CREATE TABLE IF NOT EXISTS `incomes` (
  `income_id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสงบประมาณ',
  `income_name` varchar(100) NOT NULL COMMENT 'ชื่อรายได้สถานศึกษา',
  `income_group_id` int(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'รหัสประเภท/กลุ่มรายได้',
  `income_description` varchar(255) DEFAULT NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1) NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `sort_order` int(11) NOT NULL DEFAULT '100',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`income_id`),
  KEY `income_type_id` (`income_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`income_id`, `income_name`, `income_group_id`, `income_description`, `display_status`, `sort_order`, `created_datetime`, `created_user`, `updated_datetime`, `updated_user`) VALUES
(01, 'เงินสะสม', 01, NULL, 'Y', 100, '2023-02-12 16:19:19', NULL, '2023-02-12 09:18:37', NULL),
(02, 'เงินอุดหนุนรายหัว', 04, NULL, 'Y', 100, '2023-02-23 01:36:33', NULL, '2023-02-22 18:35:40', NULL),
(03, 'ค่าเครื่องแบบนักเรียน', 01, NULL, 'Y', 100, '2023-02-23 01:37:09', NULL, '2023-02-22 18:36:47', NULL),
(04, 'ค่าอุปกรณ์การเรียน', 05, NULL, 'Y', 100, '2023-02-23 01:37:36', NULL, '2023-02-22 18:37:17', NULL),
(05, 'ค่าหนังสือเรียน', 06, NULL, 'Y', 100, '2023-02-23 01:38:15', NULL, '2023-02-22 18:37:46', NULL),
(06, 'กิจกรรมพัฒนาคุณภาพผู้เรียน', 05, NULL, 'Y', 100, '2023-02-23 01:38:52', NULL, '2023-02-22 18:38:19', NULL),
(07, 'ปัจจัยพื้นฐานสำหรับนักเรียนยากจน', 02, NULL, 'Y', 100, '2023-02-23 01:39:04', NULL, '2023-02-22 18:38:54', NULL),
(08, 'เงินหลักสูตร', 05, NULL, 'Y', 100, '2023-02-23 01:39:36', NULL, '2023-02-22 18:39:07', NULL),
(09, 'เงินรายได้สถานศึกษา', 01, NULL, 'Y', 100, '2023-02-23 01:48:53', NULL, '2023-02-22 18:48:15', NULL),
(10, 'ค่าบำรุงการศึกษา', 01, NULL, 'Y', 100, '2023-02-23 01:55:22', NULL, '2023-02-22 18:55:01', NULL),
(11, 'เงินรับฝากภาษี หัก ณ ที่จ่าย', 03, NULL, 'Y', 100, '2023-02-23 01:57:03', NULL, '2023-02-22 18:56:35', NULL),
(12, 'เงินรับฝากประกันสัญญา', 03, NULL, 'Y', 100, '2023-02-23 01:57:15', NULL, '2023-02-22 18:56:35', NULL),
(13, 'เงินรับฝากประกันสังคม', 03, NULL, 'Y', 100, '2023-02-23 01:57:15', NULL, '2023-02-22 18:56:35', NULL),
(14, 'เงินรับฝากค่าลงทะเบียน', 03, NULL, 'Y', 100, '2023-02-23 01:57:15', NULL, '2023-02-22 18:56:35', NULL),
(15, 'เงินพัฒนาห้องสมุดโรงเรียน', 03, NULL, 'Y', 100, '2023-02-23 01:57:15', NULL, '2023-02-22 18:56:35', NULL),
(16, 'เงินพัฒนาข้าราชการครู', 03, NULL, 'Y', 100, '2023-02-23 01:57:15', NULL, '2023-02-22 18:56:35', NULL),
(17, 'เงินค่าใช้จ่ายในการพัฒนาแหล่งเรียนรู้', 03, NULL, 'Y', 100, '2023-02-23 01:57:15', NULL, '2023-02-22 18:56:35', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`income_group_id`) REFERENCES `income_group` (`income_group_id`) ON UPDATE CASCADE;