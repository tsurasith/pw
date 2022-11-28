

DROP TABLE IF EXISTS `asset_status`;

CREATE TABLE IF NOT EXISTS `asset_status` (
  `asset_status_code` INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสสถานะ',
  `asset_status_name` varchar(100)  NOT NULL COMMENT 'สถานะการใช้งาน',
  `asset_status_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_status_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `asset_status` 
  (asset_status_code,
   asset_status_name,
   asset_status_description,
   display_status,
   created_datetime,
   created_user,
   updated_datetime,
   updated_user) 
VALUES 
  (NULL,'ใช้ได้','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'จำหน่าย','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'อยู่ระหว่างตรวจสอบ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ใช้ไม่ได้','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL);



