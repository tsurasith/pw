

DROP TABLE IF EXISTS `incomes`;

CREATE TABLE IF NOT EXISTS `incomes` (
  `income_id` INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสงบประมาณ',
  `income_name` varchar(100)  NOT NULL COMMENT 'ชื่อรายได้สถานศึกษา',
  `income_type_id` INT(2) ZEROFILL  NOT NULL COMMENT 'รหัสประเภท/กลุ่มรายได้',
  `income_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`income_id`),
  FOREIGN KEY ('income_type_id') REFERENCES income_types('income_type_id') ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;









