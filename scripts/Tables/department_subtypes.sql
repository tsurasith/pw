

DROP TABLE IF EXISTS `department_subtypes`;


CREATE TABLE IF NOT EXISTS `department_subtypes` (
  `department_subtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_subtype_name` varchar(200) NOT NULL,
  `department_id` int(11) NOT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`department_subtype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;


ALTER TABLE `department_subtypes` ADD `sort_order` INT NOT NULL DEFAULT '1000' COMMENT 'ค่าเอาไว้เรียงลำดับ' AFTER `updated_user`;
--
-- Dumping data for table `department_subtypes`
--

INSERT INTO `department_subtypes` VALUES(5, 'รองผู้อำนวยการฝ่ายอำนวยการ', 1, '2023-01-21 16:36:27', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `department_subtypes` VALUES(6, 'รองผู้อำนวยการฝ่ายบริหารทั่วไป', 1, '2023-01-21 04:01:11', NULL);
INSERT INTO `department_subtypes` VALUES(11, 'รองผู้อำนวยการฝ่ายวิชาการ', 1, '2023-01-21 16:19:03', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `department_subtypes` VALUES(16, 'งานวางแผน', 2, '2023-01-22 01:38:02', NULL);
INSERT INTO `department_subtypes` VALUES(17, 'งานสารบรรณ', 2, '2023-01-22 01:38:16', NULL);
INSERT INTO `department_subtypes` VALUES(18, 'งานเลขานุการ', 2, '2023-01-22 01:38:29', NULL);
INSERT INTO `department_subtypes` VALUES(19, 'งานพัสดุ', 2, '2023-01-22 01:38:40', NULL);
INSERT INTO `department_subtypes` VALUES(20, 'งานสารสนเทศฝ่าย', 2, '2023-01-22 01:38:55', NULL);
INSERT INTO `department_subtypes` VALUES(21, 'งานประเมินผลฝ่าย', 2, '2023-01-22 01:39:06', NULL);
INSERT INTO `department_subtypes` VALUES(22, 'งานวินัยและความประพฤตินักเรียน', 2, '2023-01-22 01:39:38', NULL);
INSERT INTO `department_subtypes` VALUES(23, 'งานกิจกรรมหน้าเสาธง', 2, '2023-01-22 01:41:16', NULL);
INSERT INTO `department_subtypes` VALUES(24, 'งานครูเวรประจำวัน', 2, '2023-01-22 01:41:25', NULL);
INSERT INTO `department_subtypes` VALUES(25, 'งานป้องกันและแก้ไขปัญหายาเสพติด', 2, '2023-01-22 01:41:52', NULL);
INSERT INTO `department_subtypes` VALUES(26, 'งานจราจร', 2, '2023-01-22 01:42:02', NULL);
INSERT INTO `department_subtypes` VALUES(27, 'งานยุวชนประกันภัย', 2, '2023-01-22 01:42:20', NULL);
INSERT INTO `department_subtypes` VALUES(28, 'งานส่งเสริมคุณธรรมจริยธรรม', 2, '2023-01-22 01:43:49', NULL);
INSERT INTO `department_subtypes` VALUES(29, 'งานส่งเสริมประชาธิปไตยในโรงเรียน', 2, '2023-01-22 01:44:10', NULL);
INSERT INTO `department_subtypes` VALUES(30, 'งานธนาคารความดี', 2, '2023-01-22 01:44:22', NULL);
INSERT INTO `department_subtypes` VALUES(31, 'งานรัฐพิธีและกิจกรรมวันสำคัญ', 2, '2023-01-22 01:44:36', NULL);
INSERT INTO `department_subtypes` VALUES(32, 'งานกิจกรรมพัฒนาผู้เรียน', 2, '2023-01-22 01:44:53', NULL);
INSERT INTO `department_subtypes` VALUES(33, 'งานระบบดูแลช่วยเหลือนักเรียน', 2, '2023-01-22 01:45:32', NULL);
INSERT INTO `department_subtypes` VALUES(34, 'งานชมรม To Be Number 1', 2, '2023-01-22 01:45:53', NULL);
INSERT INTO `department_subtypes` VALUES(35, 'งานหัวหน้าระดับและครูที่ปรึกษา', 2, '2023-01-22 01:46:08', NULL);
INSERT INTO `department_subtypes` VALUES(36, 'งานแนะแนวการศึกษา', 2, '2023-01-22 01:46:25', NULL);
INSERT INTO `department_subtypes` VALUES(37, 'งานทุนการศึกษา', 2, '2023-01-22 01:46:35', NULL);
INSERT INTO `department_subtypes` VALUES(38, 'รองผู้อำนวยการฝ่ายกิจการนักเรียน', 1, '2023-01-22 01:47:25', NULL);
INSERT INTO `department_subtypes` VALUES(39, 'ผู้อำนวยการโรงเรียน', 1, '2023-01-22 01:47:37', NULL);
INSERT INTO `department_subtypes` VALUES(40, 'งานวางแผนงาน', 4, '2023-01-22 01:57:00', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `department_subtypes` VALUES(41, 'งานสารบรรณ', 4, '2023-01-22 01:50:47', NULL);
INSERT INTO `department_subtypes` VALUES(42, 'งานเลขานุการ', 4, '2023-01-22 01:50:58', NULL);
INSERT INTO `department_subtypes` VALUES(43, 'งานพัสดุ', 4, '2023-01-22 01:51:04', NULL);
INSERT INTO `department_subtypes` VALUES(44, 'งานสารสนเทศ', 4, '2023-01-22 01:51:12', NULL);
INSERT INTO `department_subtypes` VALUES(45, 'งานประเมินผล', 4, '2023-01-22 01:51:19', NULL);
INSERT INTO `department_subtypes` VALUES(46, 'งานโสตทัศนูปกรณ์', 4, '2023-01-22 01:51:46', NULL);
INSERT INTO `department_subtypes` VALUES(47, 'งานสื่อสารและประชาสัมพันธ์', 4, '2023-01-22 01:52:01', NULL);
INSERT INTO `department_subtypes` VALUES(48, 'งานสวัสดิการร้านค้า', 4, '2023-01-22 01:57:28', NULL);
INSERT INTO `department_subtypes` VALUES(49, 'งานธนาคารโรงเรียน', 4, '2023-01-22 01:57:37', NULL);
INSERT INTO `department_subtypes` VALUES(50, 'งานชุมชนและภาคีเครือข่าย', 4, '2023-01-22 01:57:49', NULL);
INSERT INTO `department_subtypes` VALUES(51, 'งานอนามัยโรงเรียน', 4, '2023-01-22 01:58:00', NULL);
INSERT INTO `department_subtypes` VALUES(52, 'งานส่งเสริมสุขภาพนักเรียน', 4, '2023-01-22 01:58:14', NULL);
INSERT INTO `department_subtypes` VALUES(53, 'งานโรงเรียนปลอดขยะ', 4, '2023-01-22 01:58:23', NULL);
INSERT INTO `department_subtypes` VALUES(54, 'งานสาธารณูปโภค', 4, '2023-01-22 01:58:36', NULL);
INSERT INTO `department_subtypes` VALUES(55, 'งานโภชนาการ', 4, '2023-01-22 01:58:48', NULL);
INSERT INTO `department_subtypes` VALUES(56, 'งานอาคารสถานที่', 4, '2023-01-22 02:00:56', NULL);
INSERT INTO `department_subtypes` VALUES(57, 'งานภูมิทัศน์และสิ่งแวดล้อม', 4, '2023-01-22 02:01:05', NULL);
INSERT INTO `department_subtypes` VALUES(58, 'งานหอพักนักเรียน', 4, '2023-01-22 02:01:17', NULL);
INSERT INTO `department_subtypes` VALUES(59, 'งานนักการภาคโรงและลูกจ้าง', 4, '2023-01-22 02:01:28', NULL);
INSERT INTO `department_subtypes` VALUES(60, 'งานวางแผนงาน', 5, '2023-01-22 02:02:21', NULL);
INSERT INTO `department_subtypes` VALUES(61, 'งานสารบรรณ', 5, '2023-01-22 02:02:32', NULL);
INSERT INTO `department_subtypes` VALUES(62, 'งานเลขานุการ', 5, '2023-01-22 02:02:39', NULL);
INSERT INTO `department_subtypes` VALUES(63, 'งานพัสดุ', 5, '2023-01-22 02:02:45', NULL);
INSERT INTO `department_subtypes` VALUES(64, 'งานสารสนเทศ', 5, '2023-01-22 02:02:50', NULL);
INSERT INTO `department_subtypes` VALUES(65, 'งานประเมินผล', 5, '2023-01-22 02:02:55', NULL);
INSERT INTO `department_subtypes` VALUES(66, 'งานสารบรรณโรงเรียน', 5, '2023-01-22 02:03:09', NULL);
INSERT INTO `department_subtypes` VALUES(67, 'งานปฏิคมโรงเรียน', 5, '2023-01-22 02:03:23', NULL);
INSERT INTO `department_subtypes` VALUES(68, 'งานคณะกรรมการสถานศึกษา', 5, '2023-01-22 02:03:31', NULL);
INSERT INTO `department_subtypes` VALUES(69, 'งานยานพาหนะ', 5, '2023-01-22 02:03:43', NULL);
INSERT INTO `department_subtypes` VALUES(70, 'งานบุคลากร', 5, '2023-01-22 02:03:51', NULL);
INSERT INTO `department_subtypes` VALUES(71, 'งานสร้างขวัญและกำลังใจ', 5, '2023-01-22 02:04:01', NULL);
INSERT INTO `department_subtypes` VALUES(72, 'งานเวรยามรักษาความปลอดภัย', 5, '2023-01-22 02:04:16', NULL);
INSERT INTO `department_subtypes` VALUES(73, 'งานประกันอุบัติเหตุนักเรียน', 5, '2023-01-22 02:04:35', NULL);
INSERT INTO `department_subtypes` VALUES(74, 'งานแผนงานโรงเรียน', 5, '2023-01-22 02:04:47', NULL);
INSERT INTO `department_subtypes` VALUES(75, 'งานสารสนเทศโรงเรียน', 5, '2023-01-22 02:04:54', NULL);
INSERT INTO `department_subtypes` VALUES(76, 'งานพัสดุและทรัพย์สิน', 5, '2023-01-22 02:05:06', NULL);
INSERT INTO `department_subtypes` VALUES(77, 'งานการเงินและบัญชี', 5, '2023-01-22 02:05:15', NULL);
INSERT INTO `department_subtypes` VALUES(78, 'งานควบคุมภายใน', 5, '2023-01-22 02:05:24', NULL);
INSERT INTO `department_subtypes` VALUES(79, 'งานตรวจสอบภายใน', 5, '2023-01-22 02:05:30', NULL);
INSERT INTO `department_subtypes` VALUES(81, 'งานวางแผน', 3, '2023-01-22 02:06:49', NULL);
INSERT INTO `department_subtypes` VALUES(82, 'งานสารบรรณ', 3, '2023-01-22 02:06:59', NULL);
INSERT INTO `department_subtypes` VALUES(83, 'งานเลขานุการ', 3, '2023-01-22 02:07:10', NULL);
INSERT INTO `department_subtypes` VALUES(84, 'งานพัสดุ', 3, '2023-01-22 02:07:15', NULL);
INSERT INTO `department_subtypes` VALUES(85, 'งานสารสนเทศ', 3, '2023-01-22 02:07:25', NULL);
INSERT INTO `department_subtypes` VALUES(86, 'งานประเมินผล', 3, '2023-01-22 02:07:41', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `department_subtypes` VALUES(87, 'งานพัฒนาหลักสูตรสถานศึกษา', 3, '2023-01-22 02:08:10', NULL);
INSERT INTO `department_subtypes` VALUES(88, 'งานจัดกระบวนการการจัดการเรียนรู้', 3, '2023-01-22 02:08:45', NULL);
INSERT INTO `department_subtypes` VALUES(89, 'งานจัดตารางเรียนตารางสอน', 3, '2023-01-22 02:08:58', NULL);
INSERT INTO `department_subtypes` VALUES(90, 'งานจัดสอนแทน', 3, '2023-01-22 02:09:04', NULL);
INSERT INTO `department_subtypes` VALUES(91, 'งานพัฒนาสื่อ นวัตกรรมและเทคโนโลยี', 3, '2023-01-22 02:09:17', NULL);
INSERT INTO `department_subtypes` VALUES(92, 'งานประกันคุณภาพการศึกษา', 3, '2023-01-22 02:09:45', NULL);
INSERT INTO `department_subtypes` VALUES(93, 'งานส่งเสริมวิชาการ', 3, '2023-01-22 02:10:34', NULL);
INSERT INTO `department_subtypes` VALUES(94, 'งานนิเทศการศึกษา', 3, '2023-01-22 02:10:49', NULL);
INSERT INTO `department_subtypes` VALUES(95, 'งานห้องสมุดและแหล่งเรียนรู้', 3, '2023-01-22 02:10:59', NULL);
INSERT INTO `department_subtypes` VALUES(96, 'งานสถานศึกษาพอเพียง', 3, '2023-01-22 02:11:09', NULL);
INSERT INTO `department_subtypes` VALUES(97, 'งานสวนพฤกษศาสตร์', 3, '2023-01-22 02:11:22', NULL);
INSERT INTO `department_subtypes` VALUES(98, 'งานส่งเสริมความเป็นเลิศวิชาการ', 3, '2023-01-22 02:11:34', NULL);
INSERT INTO `department_subtypes` VALUES(99, 'งานทะเบียนนักเรียน', 3, '2023-01-22 02:11:45', NULL);
INSERT INTO `department_subtypes` VALUES(100, 'งานวัดและประเมินผลการเรียน', 3, '2023-01-22 02:11:59', NULL);
INSERT INTO `department_subtypes` VALUES(101, 'งานรับนักเรียน', 3, '2023-01-22 02:12:05', NULL);
INSERT INTO `department_subtypes` VALUES(102, 'งานเทียบโอนผลการเรียน', 3, '2023-01-22 02:12:19', NULL);


ALTER TABLE `department_subtypes` ADD `sort_order` INT NOT NULL DEFAULT '1000' COMMENT 'ค่าเอาไว้เรียงลำดับ' AFTER `updated_user`;