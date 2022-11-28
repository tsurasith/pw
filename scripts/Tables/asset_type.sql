
DROP TABLE IF EXISTS `asset_type`;

CREATE TABLE IF NOT EXISTS `asset_type` (
  `asset_type_code` INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสงบประมาณ',
  `asset_type_name` varchar(100)  NOT NULL COMMENT 'ชื่องบประมาณ',
  `asset_type_description` varchar(255)  NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `display_status` varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_type_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `asset_type` 
  (asset_type_code,
   asset_type_name,
   asset_type_description,
   display_status,
   created_datetime,
   created_user,
   updated_datetime,
   updated_user) 
VALUES 
  (NULL,'ครุภัณฑ์โฆษณาและเผยแพร่','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์โรงงาน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์ไฟฟ้าและวิทยุ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์ก่อสร้าง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์การเกษตร','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์การศึกษา','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์กีฬา','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์คอมพิวเตอร์','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์งานบ้านงานครัว','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์ดนตรีและนาฏศิลป์','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์ยานพาหนะและขนส่ง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์วิทยาศาสตร์และการแพทย์','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์สนาม','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์สำนักงาน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์สำรวจ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ครุภัณฑ์อาวุธ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'โปรแกรมคอมพิวเตอร์','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ที่ดินที่มีกรรมสิทธิ์','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ที่ดินราชพัสดุ','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'ส่วนปรับปรุงสินทรัพย์','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'สิ่งปลูกสร้าง','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'สินทรัพย์โครงสร้างพื้นฐาน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),  
  (NULL,'อาคารเพื่อการพักอาศัย','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'อาคารเพื่อประโยชน์อื่น','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL),
  (NULL,'อาคารสำนักงาน','','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL);  














