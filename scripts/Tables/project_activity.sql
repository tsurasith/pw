

DROP TABLE IF EXISTS `project_activity`;


CREATE TABLE IF NOT EXISTS `project_activity` (
  `activity_id`      varchar(36) NOT NULL COMMENT 'UUID format',
  `project_id`       varchar(36) NOT NULL COMMENT 'UUID format',
  `activity_number`  varchar(20) NOT NULL COMMENT 'รหัสกิจกรรม',
  `activity_name`    varchar(255) NOT NULL COMMENT 'ชื่อกิจกรรม',
  `activity_details` text NULL COMMENT 'วัตถุประสงค์',
  `start_date`       varchar(10) NOT NULL,
  `finish_date`      varchar(10) NOT NULL,
  `income_type_id`   INT(2) ZEROFILL  NOT NULL COMMENT 'ประเภทงบประมาณ',
  `department_id`    int(11) NOT NULL COMMENT 'รหัสฝ่าย',
  `request_budget`   decimal(11,2) NOT NULL COMMENT 'งบประมาณที่ขอ',
  `approve_budget`   decimal(11,2) NOT NULL COMMENT 'งบประมาณที่อนมัติ',
  `used_budget`      decimal(11,2) NOT NULL COMMENT 'งบประมาณที่ใช้',
  `activity_owner`   varchar(36) NULL,
  `activity_status`  INT(2) NULL COMMENT 'สถานะโครงการ',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`     varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user`     varchar(36) NULL,
  PRIMARY KEY (`activity_id`),
  UNIQUE(`activity_number`),
  FOREIGN KEY (`project_id`)      REFERENCES projects(`project_id`)   ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (`income_type_id`)  REFERENCES income_types(`income_type_id`)   ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (`department_id`)   REFERENCES departments(`department_id`)     ON UPDATE CASCADE ON DELETE RESTRICT
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



