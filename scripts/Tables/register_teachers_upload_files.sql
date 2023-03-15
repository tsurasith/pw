
DROP TABLE IF EXISTS `register_teachers_upload_files`;


CREATE TABLE IF NOT EXISTS `register_teachers_upload_files` (
  `upload_id`           int(11) NOT NULL AUTO_INCREMENT,
  `teacher_register_id` int(11) NOT NULL,
  `source_file_name`    varchar(100) NULL COMMENT 'ชื่อไฟล์',
  `import_status`       varchar(100) NULL COMMENT 'อัพโหลด,ยืนยัน',
  `created_datetime`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime`    DATETIME NULL ,
  `updated_user`        varchar(36) NULL COMMENT 'keep in UUID format',
  PRIMARY KEY (`upload_id`),
  FOREIGN KEY (`teacher_register_id`) REFERENCES register_teachers(`teacher_register_id`) 
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

