
-- table: learn_subjects
-- keep the subjects from local school program

DROP TABLE IF EXISTS `learn_subjects_history`;

CREATE TABLE IF NOT EXISTS `learn_subjects_history` (
  `learn_subjects_history_id` INT(11) ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสวิชา',
  `SubjectCode` varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `SubjectName` varchar(255)  NOT NULL COMMENT 'ชื่อวิชา',
  `SubjectUnit` varchar(3)  NOT NULL COMMENT 'หน่วยกิต',
  `SubjectHour` varchar(3)  NOT NULL COMMENT 'จำนวนชั่วโมง',
  `SubjectType` varchar(15)  NOT NULL COMMENT 'ประเภทวิชา พื้นฐาน/เพิ่มเติม',
  `FirstGroup` varchar(255)  NOT NULL COMMENT 'สาระหลัก',
  `SecondGroup` varchar(255)  NOT NULL COMMENT 'สาระย่อย',
  `SubjectClass` varchar(3)  NOT NULL COMMENT 'ชั้นปีที่เปิดสอน',
  `SubjectYear` varchar(6)  NOT NULL COMMENT 'ปีการศึกษา',
  `acadyear` varchar(4) NULL,
  `acadsemester` varchar(1) NULL,
  `level` varchar(1) NULL,
  `teacher_user_id_list` varchar(500) NULL,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user` varchar(36) NULL,
  `updated_datetime` DATETIME NULL,
  `updated_user_id` varchar(36) NULL,
  PRIMARY KEY (`learn_subjects_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- TRIGGER to handle learn_subjects_history

DROP TRIGGER IF EXISTS learn_subjects_history;

DELIMITER $$

CREATE TRIGGER learn_subjects_history
    AFTER UPDATE
    ON `learn_subjects` FOR EACH ROW
BEGIN

    INSERT INTO `learn_subjects_history`(
    `SubjectCode`,
    `SubjectName`,
    `SubjectUnit`,
    `SubjectHour`,
    `SubjectType`,
    `FirstGroup`,
    `SecondGroup`,
    `SubjectClass`,
    `SubjectYear`,
    `acadyear`,
    `acadsemester`,
    `level`,
    `teacher_user_id_list`,
    `created_datetime`,
    `created_user`,
    `updated_datetime`,
    `updated_user_id`
)
VALUES(
    NEW.`SubjectCode`,
    NEW.`SubjectName`,
    NEW.`SubjectUnit`,
    NEW.`SubjectHour`,
    NEW.`SubjectType`,
    NEW.`FirstGroup`,
    NEW.`SecondGroup`,
    NEW.`SubjectClass`,
    NEW.`SubjectYear`,
    NEW.`acadyear`,
    NEW.`acadsemester`,
    NEW.`level`,
    NEW.`teacher_user_id_list`,
    NEW.`created_datetime`,
    NEW.`created_user`,
    NEW.`updated_datetime`,
    NEW.`updated_user_id`
);

END$$    

DELIMITER ;
