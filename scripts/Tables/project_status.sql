

DROP TABLE IF EXISTS `project_status`;

CREATE TABLE IF NOT EXISTS `project_status` (
  `status_id`          INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสสถานะ',
  `status_name`        varchar(100)  NOT NULL COMMENT 'สถานะโครงการ',
  `status_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `created_datetime`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`       varchar(36) NULL,
  `updated_datetime`   DATETIME NULL,
  `updated_user`       varchar(36) NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `project_status`(`status_name`)VALUES('รอการพิจารณางบประมาณ');
INSERT INTO `project_status`(`status_name`)VALUES('อนุมัติงบประมาณแล้ว');
INSERT INTO `project_status`(`status_name`)VALUES('ยังไม่เริ่มดำเนินการ');
INSERT INTO `project_status`(`status_name`)VALUES('อยู่ระหว่างดำเนินการ');
INSERT INTO `project_status`(`status_name`)VALUES('รอการเบิกจ่าย');
INSERT INTO `project_status`(`status_name`)VALUES('รอการลงทะเบียนครุภัณฑ์');
INSERT INTO `project_status`(`status_name`)VALUES('ดำเนินการแล้วเสร็จ');
INSERT INTO `project_status`(`status_name`)VALUES('ยกเลิกโครงการ');



