

DROP TABLE IF EXISTS `asset_owner_history`;

CREATE TABLE IF NOT EXISTS `asset_owner_history` (
  `asset_owner_id`  INT(11) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'running number',
  `asset_id`        INT(6) ZEROFILL  NOT NULL,
  `asset_owner_user` varchar(36) NULL,
  `start_owner_date`  varchar(10) NULL,
  `finish_owner_date` varchar(10) NULL,
  `reason_to_return`  varchar(255)  NULL COMMENT 'เหตุผลยกเลิกการครอบครอง',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user` varchar(36) NULL,
  PRIMARY KEY (`asset_owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;














