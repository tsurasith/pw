

DROP TABLE IF EXISTS `asset_unit`;

CREATE TABLE IF NOT EXISTS `asset_unit` (
  `asset_unit_code` INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสหน่วยครุภัณฑ์',
  `asset_unit_name` varchar(100)  NOT NULL COMMENT 'หน่วยครุภัณฑ์',
  `asset_unit_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_unit_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `asset_unit` 
    (asset_unit_code,
     asset_unit_name,
     asset_unit_description,
     display_status,
     created_datetime,
     created_user,
     updated_datetime,
     updated_user) 
VALUES 
    (NULL,'เครื่อง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'แห่ง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'ใบ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'คัน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'คู่','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'งาน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'อัน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'หลัง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'สนาม','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'ระบบ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'ถัง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'ตู้','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'ตัว','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'จอ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
    (NULL,'ชุด','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL);
                            
/* end */