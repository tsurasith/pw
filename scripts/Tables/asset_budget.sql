

DROP TABLE IF EXISTS `asset_budget`;

CREATE TABLE IF NOT EXISTS `asset_budget` (
  `asset_budget_code` INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสงบประมาณ',
  `asset_budget_name` varchar(100)  NOT NULL COMMENT 'ชื่องบประมาณ',
  `asset_budget_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_budget_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `asset_budget` 
  (asset_budget_code,
   asset_budget_name,
   asset_budget_description,
   display_status,
   created_datetime,
   created_user,
   updated_datetime,
   updated_user) 
VALUES 
  (NULL,'รอการระบุ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินงบประมาณ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินบริจาค','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินรายได้','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินสะสม','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินอุดหนุน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'เงินอุดหนุนเฉพาะกิจ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'งบโรงเรียน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'รับโอน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'รับโอนจากกองแผนฯ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'รับบริจาค','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'สำรองจ่าย','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL);














