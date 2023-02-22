

DROP TABLE IF EXISTS `project_goals`;


CREATE TABLE IF NOT EXISTS `project_goals` (
  `project_goal_id`  INT(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส goal',
  `project_id`       varchar(36) NOT NULL COMMENT 'UUID format',
  `activity_id`      varchar(36) NULL COMMENT 'UUID format',
  `goal_details`     varchar(1000) NOT NULL COMMENT 'รายละเอียดเป้าหมาย',
  `goal_evaluate`    varchar(10) NULL COMMENT 'ผ่าน/ไม่ผ่าน',
  `goal_checker`     varchar(36) NULL,
  `created_datetime` DATETIME NULL,
  `created_user`     varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user`     varchar(36) NULL,
  PRIMARY KEY (`project_goal_id`),
  FOREIGN KEY (`project_id`)      REFERENCES projects(`project_id`)   ON UPDATE CASCADE ON DELETE RESTRICT
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
