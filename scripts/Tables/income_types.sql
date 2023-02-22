

DROP TABLE IF EXISTS `income_types`;

CREATE TABLE IF NOT EXISTS `income_types` (
  `income_type_id` INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสประเภทรายได้',
  `income_type_name` varchar(100)  NOT NULL COMMENT 'ประเภทรายได้สถานศึกษา',
  `income_type_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`income_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `income_types` 
  (income_type_id,
   income_type_name,
   income_type_description,
   display_status,
   created_datetime,
   created_user,
   updated_datetime,
   updated_user) 
VALUES 
  (NULL,'เงินงบประมาณ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินบริจาค','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินรายได้','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินสะสม','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินอุดหนุน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินอุดหนุนเฉพาะกิจ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'สำรองจ่าย','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL);








