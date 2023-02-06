


DROP TABLE IF EXISTS `departments`;


CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(200) NOT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` VALUES(1, 'ฝ่ายบริหาร', '2023-01-13 14:04:23', NULL);
INSERT INTO `departments` VALUES(2, 'ฝ่ายกิจการนักเรียน', '2023-01-13 14:04:23', NULL);
INSERT INTO `departments` VALUES(3, 'ฝ่ายวิชาการ', '2023-01-13 14:04:23', NULL);
INSERT INTO `departments` VALUES(4, 'ฝ่ายบริหารทั่วไป', '2023-01-13 14:04:23', NULL);
INSERT INTO `departments` VALUES(5, 'ฝ่ายอำนวยการ', '2023-01-13 14:04:23', NULL);




