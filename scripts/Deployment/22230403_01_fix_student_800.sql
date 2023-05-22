

-- 1. Table: ref_timecheck
-- 1.1 change the column name
ALTER TABLE `ref_timecheck` CHANGE `CHECK_POINTMINUS` `MINUS_800` INT(10) NULL DEFAULT NULL COMMENT 'หักคะแนนกิจกรรมหน้าเสาธง';

-- 1.2 add column for deduct point when student disappear from classroom
ALTER TABLE `ref_timecheck` ADD `MINUS_LEARN` INT NULL DEFAULT '0' AFTER `MINUS_800`;


-- 2. Table: student_point_history
-- 2.1 add a new column for reference 
ALTER TABLE `student_point_history` ADD `reference_id` VARCHAR(36) NULL COMMENT 'รหัสอ้างอิงการบันทึก' AFTER `point_comments`;

