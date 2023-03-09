
-- adjust columns ordering
ALTER TABLE `student_learn` 
    CHANGE `teacher_id` `teacher_id` VARCHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `weekday`, 
    CHANGE `SubjectCode` `SubjectCode` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'รหัสวิชา' AFTER `teacher_id`;

-- add a new column
ALTER TABLE `student_learn` ADD `club_code` VARCHAR(4) NULL DEFAULT '0000' AFTER `SubjectCode`;


-- create index on table student_learn include 3 columns

