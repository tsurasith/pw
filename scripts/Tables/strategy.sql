
DROP TABLE IF EXISTS `strategy`;

CREATE TABLE IF NOT EXISTS `strategy` (
  `strategy_id`     int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสยุทธศาสตร์/กลยุทธ์',
  `strategy_number` varchar(10) DEFAULT NULL COMMENT 'ข้อที่ เช่น 1, 3.1',
  `strategy_name`   varchar(200) NOT NULL COMMENT 'ชื่อยุทธศาสตร์/กลยุทธ์',
  `strategy_description` varchar(500) DEFAULT NULL COMMENT 'คำอธิบายแผน',
  `strategy_type`   varchar(20) DEFAULT NULL COMMENT 'ยุทธศาสตร์/กลยุทธ์',
  `display_status`  varchar(1) NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `plan_id` int(2)  UNSIGNED ZEROFILL DEFAULT NULL COMMENT 'รหัสแผน',
  `parent_strategy_id` int(2) UNSIGNED ZEROFILL DEFAULT NULL COMMENT 'รหัสยุทธศาสต์',
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`     varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user`     varchar(36) DEFAULT NULL,
  PRIMARY KEY (`strategy_id`),
  KEY `plan_id` (`plan_id`),
  KEY `parent_strategy_id` (`parent_strategy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `strategy`
--

INSERT INTO `strategy` (`strategy_id`, `strategy_number`, `strategy_name`, `strategy_description`, `strategy_type`, `display_status`, `plan_id`, `parent_strategy_id`, `created_datetime`, `created_user`, `updated_datetime`, `updated_user`) VALUES
(001, '1', 'การพัฒนาการจัดการศึกษาโดยใช้โรงเรียนเป็นฐานในการพัฒนาท้องถิ่น (SBMLD)', NULL, 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:19:08', NULL, '2023-02-26 16:18:48', NULL),
(002, '1.1', 'ส่งเสริมความเข้มแข็งคณะกรรมการสถานศึกษาขั้นพื้นฐาน', NULL, 'กลยุทธ์', 'Y', NULL, 01, '2023-02-26 23:19:48', NULL, '2023-02-26 16:19:17', NULL),
(003, '1.2', 'ส่งเสริมการศึกษาในระบบ', NULL, 'กลยุทธ์', 'Y', NULL, 01, '2023-02-26 23:20:46', NULL, '2023-02-26 16:20:11', NULL),
(004, '2', 'ส่งเสริมการจัดการศึกษาภายใต้ปรัชญาของเศรษฐกิจพอเพียง', NULL, 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:21:33', NULL, '2023-02-26 16:20:50', NULL),
(005, '2.1', 'ส่งเสริมบูรณาการหลักปรัชญาของเศรษฐกิจพอเพียงไปใช้ในการบริหารจัดการศึกษา', NULL, 'กลยุทธ์', 'Y', NULL, 04, '2023-02-26 23:22:01', NULL, '2023-02-26 16:21:52', NULL),
(006, '2.2', 'ส่งเสริมแหล่งเรียนรู้ตามหลักปรัชญาของเศรษฐกิจพอเพียงและอื่นๆที่หลากหลาย ', 'ส่งเสริมแหล่งเรียนรู้ตามหลักปรัชญาของเศรษฐกิจพอเพียงและอื่นๆที่หลากหลาย โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 04, '2023-02-26 23:23:51', NULL, '2023-02-26 16:23:30', NULL),
(007, '3', 'ส่งเสริมการศึกษาเพื่อเข้าสู่การเปลี่ยนแปลงในศตวรรษที่ 21', 'ส่งเสริมการศึกษาเพื่อเข้าสู่การเปลี่ยนแปลงในศตวรรษที่ 21', 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:25:32', NULL, '2023-02-26 16:24:44', NULL),
(008, '3.1', 'ส่งเสริมการจัดการเรียนการสอนแบบสองภาษา (Bilingual Program)', 'ส่งเสริมการจัดการเรียนการสอนแบบสองภาษา (Bilingual Program)', 'กลยุทธ์', 'Y', NULL, 07, '2023-02-26 23:25:57', NULL, '2023-02-26 16:25:34', NULL),
(009, '3.2', 'ส่งเสริมการเปลี่ยนแปลงเรียนรู้เพื่อการเปลี่ยนแปลงในศตวรรษที่ 21', 'ส่งเสริมการเปลี่ยนแปลงเรียนรู้เพื่อการเปลี่ยนแปลงในศตวรรษที่ 21', 'กลยุทธ์', 'Y', NULL, NULL, '2023-02-26 23:26:18', NULL, '2023-02-26 16:26:03', NULL),
(010, '3.3', 'ส่งเสริมสถานศึกษาเป็นศูนย์การสร้างผลิตภัณฑ์ของชุมชนเพื่อเพิ่มมูลค่าทางเศรษฐกิจ ', 'ส่งเสริมสถานศึกษาเป็นศูนย์การสร้างผลิตภัณฑ์ของชุมชนเพื่อเพิ่มมูลค่าทางเศรษฐกิจ โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 07, '2023-02-26 23:26:40', NULL, '2023-02-26 16:26:20', NULL),
(011, '4', 'ส่งเสริมพัฒนาครูและบุคลากรทางการศึกษา ', 'ส่งเสริมพัฒนาครูและบุคลากรทางการศึกษา ', 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:27:17', NULL, '2023-02-26 16:27:07', NULL),
(012, '4.1', 'พัฒนาความรู้ ความสามารถทางวิชาการและวิชาชีพ', 'พัฒนาความรู้ ความสามารถทางวิชาการและวิชาชีพ', 'กลยุทธ์', 'Y', NULL, 11, '2023-02-26 23:27:44', NULL, '2023-02-26 16:27:27', NULL),
(013, '4.2', 'ส่งเสริมคุณธรรม จริยธรรม และจรรยาบรรณวิชาชีพ', 'ส่งเสริมคุณธรรม จริยธรรม และจรรยาบรรณวิชาชีพ', 'กลยุทธ์', 'Y', NULL, 11, '2023-02-26 23:28:13', NULL, '2023-02-26 16:27:54', NULL),
(014, '4.3', 'ส่งเสริมการสร้างขวัญและกำลังใจ', 'ส่งเสริมการสร้างขวัญและกำลังใจ  โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 11, '2023-02-26 23:28:39', NULL, '2023-02-26 16:28:24', NULL),
(015, '5', 'การพัฒนาสถานศึกษาสู่มาตรฐานสากล', 'การพัฒนาสถานศึกษาสู่มาตรฐานสากล', 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:30:13', NULL, '2023-02-26 16:30:03', NULL),
(016, '5.1', 'ยกระดับคุณภาพการศึกษาให้ได้มาตรฐาน', 'ยกระดับคุณภาพการศึกษาให้ได้มาตรฐาน', 'กลยุทธ์', 'Y', NULL, 15, '2023-02-26 23:30:39', NULL, '2023-02-26 16:30:28', NULL),
(017, '5.2', 'ส่งเสริมผู้เรียนสู่ความเป็นเลิศตามอัจฉริยภาพ', 'ส่งเสริมผู้เรียนสู่ความเป็นเลิศตามอัจฉริยภาพ', 'กลยุทธ์', 'Y', NULL, 15, '2023-02-26 23:31:04', NULL, '2023-02-26 16:30:41', NULL),
(018, '5.3', 'ส่งเสริมคุณธรรม จริยธรรม ผู้เรียน', 'ส่งเสริมคุณธรรม จริยธรรม ผู้เรียน', 'กลยุทธ์', 'Y', NULL, 15, '2023-02-26 23:31:22', NULL, '2023-02-26 16:31:10', NULL),
(019, '5.4', 'สนับสนุนสิ่งอำนวยความสะดวกและทรัพยากรอื่นๆ', 'สนับสนุนสิ่งอำนวยความสะดวกและทรัพยากรอื่นๆ โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 15, '2023-02-26 23:31:46', NULL, '2023-02-26 16:31:29', NULL),
(020, '6', 'พัฒนาเทคโนโลยี และสื่อนวัตกรรมทางการศึกษา', 'พัฒนาเทคโนโลยี และสื่อนวัตกรรมทางการศึกษา', 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:32:12', NULL, '2023-02-26 16:31:53', NULL),
(021, '6.1', 'ส่งเสริมและจัดหาเทคโนโลยีที่ทันสมัยและมีคุณภาพ', 'ส่งเสริมและจัดหาเทคโนโลยีที่ทันสมัยและมีคุณภาพ', 'กลยุทธ์', 'Y', NULL, 20, '2023-02-26 23:32:32', NULL, '2023-02-26 16:32:18', NULL),
(022, '6.2', 'ส่งเสริมการผลิต และจัดหาสื่อการเรียนรู้', 'ส่งเสริมการผลิต และจัดหาสื่อการเรียนรู้', 'กลยุทธ์', 'Y', NULL, 20, '2023-02-26 23:33:00', NULL, '2023-02-26 16:32:48', NULL),
(023, '6.3', 'ส่งเสริมการใช้นวัตกรรมทางการศึกษา', 'ส่งเสริมการใช้นวัตกรรมทางการศึกษา โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 20, '2023-02-26 23:33:18', NULL, '2023-02-26 16:33:06', NULL),
(024, '7', 'ส่งเสริม สนับสนุน การกีฬา นันทนาการและการออกกำลังกาย', 'ส่งเสริม สนับสนุน การกีฬา นันทนาการและการออกกำลังกาย', 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:33:37', NULL, '2023-02-26 16:33:22', NULL),
(025, '7.1', 'ส่งเสริม สนับสนุนการจัดกิจกรรมกีฬาเพื่อสุขภาพ', 'ส่งเสริม สนับสนุนการจัดกิจกรรมกีฬาเพื่อสุขภาพ', 'กลยุทธ์', 'Y', NULL, 24, '2023-02-26 23:34:00', NULL, '2023-02-26 16:33:44', NULL),
(026, '7.2', 'ส่งเสริม กิจกรรมนันทนาการ กิจกรรมเยาวชนและประชาชน', 'ส่งเสริม กิจกรรมนันทนาการ กิจกรรมเยาวชนและประชาชน', 'กลยุทธ์', 'Y', NULL, 24, '2023-02-26 23:34:22', NULL, '2023-02-26 16:34:08', NULL),
(027, '7.3', 'ส่งเสริมการแก้ไขพฤติกรรมที่ไม่พึงประสงค์ (การพนัน ยาเสพติด เพศศึกษา ทะเลาะวิวาท สื่อออนไลน์) ', 'ส่งเสริมการแก้ไขพฤติกรรมที่ไม่พึงประสงค์ (การพนัน ยาเสพติด เพศศึกษา ทะเลาะวิวาท สื่อออนไลน์) โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 24, '2023-02-26 23:34:41', NULL, '2023-02-26 16:34:29', NULL),
(028, '8', 'ส่งเสริมการมีส่วนร่วมของชุมชนท้องถิ่นและปราชญ์ชาวบ้านในการอนุรักษ์ศิลปะ วัฒนธรรมและภูมิปัญญาท้องถิ่น', 'ส่งเสริมการมีส่วนร่วมของชุมชนท้องถิ่นและปราชญ์ชาวบ้านในการอนุรักษ์ศิลปะ วัฒนธรรมและภูมิปัญญาท้องถิ่น', 'ยุทธศาสตร์', 'Y', NULL, NULL, '2023-02-26 23:35:01', NULL, '2023-02-26 16:34:51', NULL),
(029, '8.1', 'สนับสนุนการจัดกิจกรรมทางศาสนา', 'สนับสนุนการจัดกิจกรรมทางศาสนา', 'กลยุทธ์', 'Y', NULL, 28, '2023-02-26 23:35:22', NULL, '2023-02-26 16:35:06', NULL),
(030, '8.2', 'สนับสนุนกิจกรรมด้านศิลปะ วัฒนธรรม และประเพณี', 'สนับสนุนกิจกรรมด้านศิลปะ วัฒนธรรม และประเพณี', 'กลยุทธ์', 'Y', NULL, 28, '2023-02-26 23:35:45', NULL, '2023-02-26 16:35:29', NULL),
(031, '8.3', 'อนุรักษ์ศิลปะ ภูมิปัญญาและปราชญ์ชาวบ้าน', 'อนุรักษ์ศิลปะ ภูมิปัญญาและปราชญ์ชาวบ้าน โรงเรียนในสังกัด อบจ.ชัยภูมิและกองการศึกษาฯ', 'กลยุทธ์', 'Y', NULL, 28, '2023-02-26 23:36:07', NULL, '2023-02-26 16:35:50', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `strategy`
--
ALTER TABLE `strategy`
  ADD CONSTRAINT `strategy_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `strategy_ibfk_2` FOREIGN KEY (`parent_strategy_id`) REFERENCES `strategy` (`strategy_id`) ON UPDATE CASCADE;