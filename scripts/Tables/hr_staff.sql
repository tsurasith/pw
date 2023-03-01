

DROP TABLE IF EXISTS `hr_staff`;


CREATE TABLE IF NOT EXISTS `hr_staff` (
  `staff_id` varchar(36) NOT NULL COMMENT 'รหัสบุคลากร',
  `finger_code` varchar(5) DEFAULT NULL COMMENT 'รหัสเครื่องสแกนลายนิ้วมือ',
  `TeacCode` varchar(3) NULL COMMENT 'รหัสครูระบบสารสนเทศ',
  `prefix` varchar(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL COMMENT 'ตำแหน่ง (ตรงกันกับตาราง teachers)',
  `staff_type` varchar(100) DEFAULT NULL COMMENT 'ประเภทบุคลากร (ผู้บริหาร,ครู,นักการ)',
  `staff_status` varchar(100) DEFAULT NULL COMMENT 'สถานะ (ACTIVE,INACTIVE)',
  `phone_number` varchar(30) DEFAULT NULL,
  `mobile_number` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  `updated_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `TeacCode` (`TeacCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hr_staff`
--

INSERT INTO `hr_staff` VALUES('446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16', NULL, '999', 'นาย', 'สุรสิทธิ์', 'ท้าวกอก', 'โทบี้', 'Tester', NULL, 'ACTIVE', '231313', '0831978982', 'taokok@gmail.com', '2023-01-12 15:16:49', NULL, '2023-02-03 20:52:49', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `hr_staff` VALUES('D6806031-6121-4BA3-913E-DF80EDD06932', '61', '100', 'นาย', 'เกียรติ', 'ปะหุสี', 'เป้ย', 'ผู้อำนวยการสถานศึกษา', 'ผู้บริหาร', 'ACTIVE', '', '0898652364', 'pahusee@gmail.com', '2023-01-12 15:16:49', NULL, '2023-02-03 20:42:53', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `hr_staff` VALUES('D359A5FF-C4DD-4FE2-8E66-07C4ADC24DA8', '55', '101', 'นางสาว', 'เสาวลักษณ์', 'ศรีสารคาม', 'จอย', 'รองผู้อำนวยการ ชนพ.', NULL, 'ACTIVE', '', '0833294147', 'J.music5555@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('3D9F3821-13EC-4C70-9263-F21C0A37E718', '63', '102', 'นาย', 'อนันต์', 'ปาระพิมพ์', '', 'รองผู้อำนวยการสถานศึกษา ชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0899179542', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('6F86006F-CF5D-4836-AD15-30C1237A5547', '60', '103', 'นาย', 'ทวีชัย', 'หาโกสีย์', 'ชัย', 'รองผู้อำนวยการสถานศึกษา เชี่ยวชาญ', NULL, 'ACTIVE', '', '0619561925', 'twechai2562@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('ABE7F927-44E0-473A-8024-D0D312A85A7F', '50', '104', 'นาง', 'น้ำอ้อย', 'กานิล', 'อ้อย', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0909982202', 'Namaoy18@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('0EE423A6-F27E-431A-AB57-9BA500335B92', '46', '105', 'นาย', 'บำรุงญาติ', 'เกิดมงคล', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('4354F468-5271-44ED-A6F6-4EE0C8336DCF', '15', '106', 'นาย', 'ศราวุฒิ', 'พรภูเขียว', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('8A7A13E9-A7C0-4F49-8678-2CB35D07C4C8', '2', '107', 'นาง', 'สุวรรณี', 'พรภูเขียว', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('5DFDB915-2C32-40B9-9B94-64B6C0367B13', '29', '108', 'นาง', 'ธัญญรัตน์', 'พิมัยรัมย์', 'Pha', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0908477889', 'phapha1102@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('F0795CE9-10B7-4995-B3BC-B3AF11CD52C4', '30', '109', 'นาย', 'ธวัชชัย', 'พิมัยรัมย์', 'SJark', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0857565677', 'sjark23@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('53BF1A9F-1F6C-437F-B6DC-D4AB027BE0CB', '54', '110', 'นางสาว', 'สุภาพร', 'ใสชาติ', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('4169AEE7-64FC-4A34-8EA3-27796F7214E3', '58', '111', 'นาย', 'ปรเมศวร์', 'โสเมือง', 'หนึ่ง', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0633324490', 'Pommeneung@Gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('AC822532-D79E-4577-A89B-76CA7A06F224', '14', '112', 'นาย', 'นเรศ', 'ระดากุล', 'อี๊ด', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '968605228', '0968605228', 'naretradakul@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('51A4223A-8A72-43AF-BB25-BFB2C1070787', '38', '113', 'นางสาว', 'จิราพร', 'สอนชัยภูมิ', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('BE536FA7-AA37-4BB6-BFBF-F0A234640515', '51', '114', 'นาย', 'อุทัย', 'ขันลุน', 'นิว', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0619707171', 'Khanlunxuthay@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('CCD43932-95D3-4A18-8C7C-73C250B0E79B', '7', '115', 'นาย', 'ชัยสิทธิ์', 'ตอพรม', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('3B5E8FD5-5209-49B9-AADF-8C55CF1EDCCD', '59', '116', 'นางสาว', 'พิชญ์สินีชุดา', 'เชษฐสิงห์', 'พิชซี่', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '044878107', '0621615542', 'p.chestasing@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('205B33BB-2EA9-4516-9F3B-801A51C5EE41', '20', '117', 'นาง', 'สิริกร', 'บุญเผือก', 'ครูจั๊กจั่น', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0862222175', 'bsirikorn658@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('924B0B7B-BEA2-41F8-9722-FC0290E67E2F', '53', '118', 'นาง', 'ดวงดาว', 'สิงห์ลอ', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('72203558-CDB2-4049-869E-FCBFA79CF0F8', '64', '119', 'นาย', 'สุปัญญา', 'โยธาธรรม', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('0CF2CC3D-57FE-410D-A2EC-C6D57B8515AD', '23', '120', 'นางสาว', 'ญาณิศา', 'นามจุมจัง', 'ยุง', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '-', '0932536490', 'Yanisajung 1980@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('336EA6C6-7C94-41C7-AEC5-F3B545E84797', '26', '121', 'นางสาว', 'มธุรส', 'แผ่นทอง', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('E7A1AF49-A14C-4C5A-A448-C5CFDB37E591', '65', '122', 'นางสาว', 'เจนจิรา', 'รัตน์ประทุม', 'แพร', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '', '0925761237', 'jenjira.pear0929@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('BA14BD0C-C867-4021-9A5D-D5114D9F79E1', '45', '123', 'นาย', 'สุรชัย', 'ขุมเงิน', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('A9C37C98-25F0-4C2A-ADF6-CAE729372867', '48', '124', 'นาย', 'พรณที', 'กันคำแหง', 'พอน', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '0894249852', 'pornnatee1100@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('B7D080BD-5B53-46FE-AF48-21D4E7003C16', '19', '125', 'นาย', 'ชูพงษ์', 'ลาลำโกน', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('606816B7-B427-4B34-A791-3A2AAA1FE6F7', '37', '126', 'นางสาว', 'พรศิริ', 'อินทร์เพ็ชร', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('3EA5A030-AE0A-4EA7-87FE-6F3E5F61D5D5', '16', '127', 'นาง', 'สไบแพร', 'ยงเพชร', 'แพร', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0615989787', 'sabaiprae19@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('4839D47E-467B-4E17-9AE2-1025C881B431', '12', '128', 'นาย', 'วีระเดช', 'วิโย', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('073F4BF6-4C3F-4C53-BB31-F38531A107EA', '34', '129', 'นาย', 'เฉลิมพล', 'นาบุตร', 'ปุ๊กกี้', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0968505458', 'doctorpook@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('8B0869F2-E509-49C4-83CE-59DFCB85B9E8', '24', '130', 'นาย', 'วรวุธ', 'ไชยสอน', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('89CCBD84-01A2-491E-818D-534792FAD2FC', '56', '131', 'นาง', 'กุลรัศมิ์', 'คล่องขยัน', 'หมวย', 'ครูชำนาญการพิเศษ', 'ข้าราชการครู', 'ACTIVE', '083241059', '0832410599', 'Khunlarutkh@gmail.com', '2023-01-12 15:16:49', NULL, '2023-02-04 00:15:34', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `hr_staff` VALUES('B62E0900-2AB0-410D-BEEC-BE52D36F2A68', '43', '132', 'นางสาว', 'นฤมล', 'เหล่ากวางโจน', 'ก้อย', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '', '0868546757', 'naruemon25@windowslive.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('8A54DBFC-6DB3-4D8A-8674-943DF75B1256', '31', '133', 'นางสาว', 'อุไรวรรณ', 'คุณประทุม', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('C212F476-554B-4E51-AD71-941CB0597634', '40', '134', 'นางสาว', 'ทิวารัตน์', 'อิ่มนาง', 'อุ้ย', 'ครู', NULL, 'ACTIVE', '', '0854650242', 'thivarat071134@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('379DC8BA-097E-4FEE-BDA6-732D56ADD19A', '35', '135', 'นาย', 'ศราวุทธ', 'โพธิ์สิงห์', 'เหน่ง', 'ครู', NULL, 'ACTIVE', '', '0956702692', 'nang49010211152@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('601480A8-4AB5-423F-AC63-AC15D217D0A8', '68', '136', 'นาง', 'พรรณพัชนันท์', 'ศรีชะตา', 'ส้มโอ', 'ครู', NULL, 'ACTIVE', '044878107', '0969235896', 'krusommor261402@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('EBF6986B-B2B8-44E1-AB28-D5157223A223', '36', '137', 'นางสาว', 'กนิษฐา', 'ภูดวงจิตร', '', 'ครู', 'ข้าราชการครู', 'ACTIVE', '044846509', '044846509', '', '2023-01-12 15:16:49', NULL, '2023-02-03 20:35:16', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `hr_staff` VALUES('5D318FD9-BD66-4C07-8D76-A3D57D71CAA9', '18', '138', 'นาย', 'สิทธิพล', 'สุคุณา', '', 'ครู', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('ADF1370D-A7FD-4C34-9A08-27848BA02714', '44', '139', 'นาย', 'อรุณ', 'นิลบรรพต', 'ต่าย', 'ครูชำนาญการ', NULL, 'ACTIVE', '044878107', '0881019117', 'sendo_s@hotmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('A536D6E9-2028-4AC7-990D-CD7B69C24703', '57', '140', 'นาย', 'รัฐพงษ์', 'ข่าขันมะลี', 'อาร์ท', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '', '0956611747', 'Art_36110@hotmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('E038E061-0C86-4100-BC99-357B374A54F9', '6', '141', 'นาย', 'ยุทธชัย', 'แสงชาติ', '', 'ครู', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('800400A5-3CFB-4FD2-A393-AE19D7984258', '27', '142', 'นาย', 'วิศวะ', 'สมบัติสิน', '', 'ครู', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('B56AF6C6-9B3C-4ACA-AD73-D9FC3C03E661', '11', '143', 'นาย', 'พลวัฒน์', 'พาคำ', '', 'ครู', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('384C2B17-48EE-4AB1-B9FF-7ABFDBEA65EC', '42', '144', 'นางสาว', 'อุมาพร', 'ลือปรีชา', '', 'ครูชำนาญการ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('CC682606-B2A9-4296-8ADB-00EB7172F61F', '66', '145', 'นาย', 'ณรงค์', 'รอดตรง', 'หรรม', 'ครูชำนาญการ', NULL, 'ACTIVE', '081044755', '0810447554', 'pubpibkapubpub@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('34912F2A-6633-441F-9513-77EBFCA53DEC', '3', '146', 'นาง', 'ทองทิพย์', 'พิบูลย์', '', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('C97996B8-53DE-402A-84DC-18D6F63237EF', '41', '147', 'นางสาว', 'นงเยาว์', 'เรือนบุตร', 'เยาว์', 'ครู', NULL, 'ACTIVE', '', '0940083788', 'Psangdaw@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('E62A6B29-5B5D-42EB-9163-F502E511F5A6', '62', '148', 'นาย', 'ธนพัชร์', 'มีรัตน์ธนวัต', 'รถโฟล์ค', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '00000000', '0971645807', 'tc148', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('25425CCE-864E-4040-93D7-D2F93A01AAA8', '5', '149', 'นาย', 'สมัย', 'มิตรมาตร', 'ตู่', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('6B856AA9-4A71-40C3-B628-DB57AA69165E', '39', '150', 'นาย', 'ธนพล', 'ฤาชา', 'แม็ก', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0930307008', 'thanaphon0252@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('2A8F3B4A-09B5-42C5-B727-6050A51A2EC6', '4', '151', 'นาย', 'ทวีศักดิ์', 'ถาวร', 'ตั้ม', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('E97B977C-ECF8-43BE-AB3C-5D9CC958BF80', '49', '152', 'นาย', 'คุณานนท์', 'ค้าแหวน', 'โอปอ', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('E8435EEA-03EA-446A-AA9D-161B415AE55B', '13', '153', 'นาย', 'เศรษฐพงศ์', 'เชื้ออาสา', 'เจ๋ง', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('BE8C8BDE-1B3F-43B3-959D-60B6B05926B8', '22', '154', 'นาย', 'พิเชษฐ์', 'แสงคำ', 'เขต', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('2E9E0440-E1EB-4FB1-9BC2-5C7E3E11AFF0', '21', '155', 'นาย', 'ปฏิภาณ', 'อนันเต่า', 'ปลั๊ก', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('47F86D0E-674A-46D4-B55D-3BEF1AFCB262', '67', '156', 'นาย', 'เดชประยงค์', 'หงษ์ศรีเมือง', 'เกิ้ล', 'วิทยากรพิเศษ', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('A4DFD2C6-3110-4C94-80E9-AE32A66C5955', '32', '157', 'นาย', 'ชลวิทย์', 'พรมมา', 'ต่อ', 'ครูอัตราจ้าง', NULL, 'ACTIVE', '000000000', '0000000000', NULL, '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('EA957027-470A-486F-8C29-038B9B7F06A5', '17', '158', 'นาย', 'เอกราช', 'ขวกเขียว', 'เอก', 'ครูชำนาญการพิเศษ', NULL, 'ACTIVE', '', '0643201518', 'ak.tammie@hotmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('B2EA41C4-66BC-4DA2-BBEF-3DDBCC0BD345', '', '159', 'นาย', 'กิตติภูมิ', 'ศรีมงคล', 'เปา', 'นักศึกษาฝึกประสบการณ์สอน', 'นักศึกษาฝึกประสบการณ์', 'ACTIVE', '000000000', '0825700772', 'Sr_kittiphum@kkumail.com', '2023-01-12 15:16:49', NULL, '2023-02-04 00:15:21', '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16');
INSERT INTO `hr_staff` VALUES('3c542c62-ded3-4fe0-b081-869fe44cf663', '8', '160', 'นาง', 'นิรนุช', 'พรรสุวรรณ', 'เจี๊ยบ', 'ผู้ช่วยเจ้าหน้าที่การเงินและบัญชี', NULL, 'ACTIVE', '', '0835622322', '', '2023-01-12 15:16:49', NULL, NULL, NULL);
INSERT INTO `hr_staff` VALUES('355f4f96-0337-4d12-af7a-2da58d8e56ea', '1', '161', 'นาง', 'ณฐพร', 'เจริญคุณ', 'แต้ว', 'ผู้ช่วยเจ้าพนักงานธุรการ', NULL, 'ACTIVE', '', '0630430182', 'tc161@gmail.com', '2023-01-12 15:16:49', NULL, NULL, NULL);



