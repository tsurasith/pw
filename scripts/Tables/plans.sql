

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


