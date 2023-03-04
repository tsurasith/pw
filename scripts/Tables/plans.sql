

DROP TABLE IF EXISTS `plans`;

CREATE TABLE IF NOT EXISTS `plans` (
  `plan_id`          INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสช่วงแผน',
  `plan_name`        varchar(100)  NOT NULL COMMENT 'ชื่อแผน',
  `plan_description` varchar(500)  NULL COMMENT 'คำอธิบายแผน',
  `start_year`       varchar(4) NOT NULL,
  `finish_year`      varchar(4) NOT NULL,
  `start_date`       varchar(10) NOT NULL,
  `finish_date`      varchar(10) NOT NULL,
  `plan_type`        varchar(100) NULL COMMENT 'ประเภทของแผน(แผนห้าปี/แผนรายปี/..)',
  `plan_owner`       varchar(100) NULL COMMENT 'หน่วยงานรับผิดชอบ',
  `display_status`   varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`     varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_description`, `start_year`, `finish_year`, `start_date`, `finish_date`, `plan_type`, `plan_owner`, `display_status`, `created_datetime`, `created_user`, `updated_datetime`, `updated_user`) VALUES
(01, 'แผนยุทธศาสตร์และกลยุทธ์ ปีงบประมาณ 2566-2570 ', 'ในปีงบประมาณ พ.ศ. 2566 – 2570 กองการศึกษา ศาสนาและวัฒนธรรม ได้กำหนดวิสัยทัศน์ไว้ว่า “สืบสานประเพณี วัฒนธรรม ส่งเสริมสุขภาพ ล้ำเลิศวิชาการ ก้าวสู่การเปลี่ยนแปลงในศตวรรษที่ 21” และกำหนดยุทธศาสตร์และกลยุทธ์ในการพัฒนาการศึกษา ขององค์การบริหารส่วนจังหวัดชัยภูมิ แบ่งออกเป็น 8 ยุทธศาสตร์ 25 กลยุทธ์', '2566', '2570', '0000-00-00', '0000-00-00', 'แผนห้าปี', 'อบจ.ชัยภูมิ', 'Y', '2023-02-26 23:16:23', NULL, '2023-02-26 16:14:02', NULL);

