DROP TABLE IF EXISTS `project_activity`;

DROP TABLE IF EXISTS `project_goals`;

DROP TABLE IF EXISTS `projects`;


CREATE TABLE IF NOT EXISTS `projects` (
  `project_id`       varchar(36) NOT NULL COMMENT 'UUID format',
  `project_number`   varchar(20) NOT NULL COMMENT 'รหัสโครงการ',
  `project_name`     varchar(255) NOT NULL COMMENT 'ชื่อโครงการ',
  `acadyear`         varchar(4) NOT NULL   COMMENT 'ปีงบประมาณ/ปีการศึกษา',
  `acadsemester`     varchar(1) NULL       COMMENT 'ภาคเรียน',
  `project_details`  text NOT NULL COMMENT 'วัตถุประสงค์',
  `start_date`       varchar(10) NOT NULL,
  `finish_date`      varchar(10) NOT NULL,
  `income_id`        INT(2) ZEROFILL  NOT NULL COMMENT 'ประเภทงบประมาณ',
  `department_id`    int(11) NOT NULL COMMENT 'รหัสฝ่าย',
  `request_budget`   decimal(11,2) NOT NULL COMMENT 'งบประมาณที่ขอ',
  `approve_budget`   decimal(11,2) NOT NULL COMMENT 'งบประมาณที่อนมัติ',
  `used_budget`      decimal(11,2) NOT NULL COMMENT 'งบประมาณที่ใช้',
  `project_owner`    varchar(36) NULL,
  `project_status`   INT(2) NULL COMMENT 'สถานะโครงการ',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`     varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user`     varchar(36) NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE(`project_number`),
  FOREIGN KEY (`income_id`)       REFERENCES incomes(`income_id`)   ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (`department_id`)   REFERENCES departments(`department_id`)     ON UPDATE CASCADE ON DELETE RESTRICT
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



