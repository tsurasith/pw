

DROP TABLE IF EXISTS `strategy`;

CREATE TABLE IF NOT EXISTS `strategy` (
  `strategy_id`          INT(2) ZEROFILL  NOT NULL  AUTO_INCREMENT COMMENT 'รหัสยุทธศาสตร์/กลยุทธ์',
  `strategy_name`        varchar(100)  NOT NULL COMMENT 'ชื่อยุทธศาสตร์/กลยุทธ์',
  `strategy_description` varchar(500)  NULL COMMENT 'คำอธิบายแผน',
  `strategy_type`        varchar(100) NULL COMMENT 'ยุทธศาสตร์/กลยุทธ์',
  `display_status`       varchar(1)  NOT NULL DEFAULT 'Y' COMMENT 'การแสดงใน select box, Y=แสดง,N=ไม่แสดง',
  `plan_id`              INT(2) ZEROFILL NULL COMMENT 'รหัสแผน',
  `parent_strategy_id`   INT(2) ZEROFILL NULL COMMENT 'รหัสยุทธศาสต์',
  `strategy_owner`       varchar(100) NULL COMMENT 'หน่วยงานเจ้าของยุทธศาสตร์ กุลยุทธ์',
  `created_datetime`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`         varchar(36) NULL,
  `updated_datetime`     DATETIME NULL,
  `updated_user`         varchar(36) NULL,
  PRIMARY KEY (`strategy_id`),
  FOREIGN KEY (`plan_id`)            REFERENCES plans(`plan_id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `strategy`
ADD FOREIGN KEY (`parent_strategy_id`) REFERENCES strategy(`strategy_id`) ON UPDATE CASCADE ON DELETE RESTRICT

