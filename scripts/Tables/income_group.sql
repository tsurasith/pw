

DROP TABLE IF EXISTS `income_group`;


CREATE TABLE IF NOT EXISTS `income_group` (
  `income_group_id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภทรายได้',
  `income_group_name` varchar(100) NOT NULL COMMENT 'ประเภทรายได้สถานศึกษา',
  `income_group_description` varchar(255) DEFAULT NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1) NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `sort_order` int(11) NOT NULL DEFAULT '100',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`income_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


INSERT INTO `income_group` (`income_group_id`, `income_group_name`, `income_group_description`, `display_status`, `sort_order`, `created_datetime`, `created_user`, `updated_datetime`, `updated_user`) VALUES
(01, 'เงินงบประมาณ', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL),
(02, 'เงินบริจาค', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL),
(03, 'เงินรายได้', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL),
(04, 'เงินสะสม', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL),
(05, 'เงินอุดหนุน', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL),
(06, 'เงินอุดหนุนเฉพาะกิจ', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL),
(07, 'สำรองจ่าย', '', 'Y', 100, '2023-02-12 16:17:14', NULL, NULL, NULL);


