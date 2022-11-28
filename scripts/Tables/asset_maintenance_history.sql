

DROP TABLE IF EXISTS `asset_maintenance_history`;

CREATE TABLE IF NOT EXISTS `asset_maintenance_history` (
  `asset_maintenance_id`  INT(11) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'running number',
  `asset_id` INT(6) ZEROFILL NOT NULL  COMMENT 'Running number',
  `asset_number` VARCHAR(15) NULL COMMENT 'เลขทะเบียนครุภัณฑ์',
  `asset_maintenance_user` varchar(36) NULL,
  `start_maintenance_date`  varchar(10) NULL,
  `finish_maintenance_date` varchar(10) NULL,
  `maintenance_cost` DECIMAL(24,2) NULL COMMENT 'ราคาค่าซ่อมบำรุง',
  `project_id` VARCHAR(36) NULL,
  `sub_project_id` VARCHAR(36) NULL,
  `reason_to_maintenance`  varchar(255)  NULL COMMENT 'เหตุผลการซ่อมบำรุง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_maintenance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



