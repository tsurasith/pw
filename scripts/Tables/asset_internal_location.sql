

DROP TABLE IF EXISTS `asset_internal_location`;

CREATE TABLE IF NOT EXISTS `asset_internal_location` (
  `asset_internal_location_code` INT(3) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสสถานที่ภายใน',
  `asset_internal_location_name` varchar(100)  NOT NULL COMMENT 'ชื่อสถานที่',
  `asset_internal_location_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_internal_location_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `asset_internal_location` 
  (asset_internal_location_code,
   asset_internal_location_name,
   asset_internal_location_description,
   display_status,
   created_datetime,
   created_user,
   updated_datetime,
   updated_user) 
VALUES 
  (NULL,'ห้องผู้อำนวยการ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'หอประชุม 1','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL);












