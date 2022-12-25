
-- table: curriculum_subjects

DROP TABLE IF EXISTS `curriculum_subject_group`;

CREATE TABLE IF NOT EXISTS `curriculum_subject_group` (
  `subject_group_id`     INT NOT NULL AUTO_INCREMENT COMMENT 'รหัสกลุ่มสาระการเรียนรู้',
  `subject_group_name`     varchar(200)  NOT NULL COMMENT 'ชื่อสาระกลุ่มเรียนรู้',
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`      varchar(36) NULL,
  `updated_datetime`  DATETIME NULL,
  `updated_user_id`   varchar(36) NULL,
  PRIMARY KEY (`subject_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `curriculum_subject_group`(
    `subject_group_id`,
    `subject_group_name`
)
select * from ref_academic;

UPDATE `curriculum_subject_group` SET `subject_group_name` = 'ไม่ระบุ' WHERE `curriculum_subject_group`.`subject_group_id` = 0;

