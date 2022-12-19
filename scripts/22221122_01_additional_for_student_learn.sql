-- incomplete input data
-- to inform teach to re-input

SELECT
    u.user_account_firstname,
    t.*
FROM
    teaching_schedule t
LEFT JOIN users_account u
ON
    (
        t.created_user = u.user_account_id
    )
WHERE
    trim(t.teacher_id) = ''
ORDER BY
    1,WEEKDAY,
    period;

-- clean data on table: teaching_schedule
-- clean data

delete from teaching_schedule
where trim(teacher_id) = ''

-- add room_id value
update 
teaching_schedule
SET
room_id = concat(level,'0',room)
WHERE
trim(room_id) = ''  ;


-- add column "weekday" in table: student_learn_task
/* if these two columns are existing
alter table student_learn DROP COLUMN weekday;
alter table student_learn_task DROP COLUMN weekday;
*/

-- step 1
alter table student_learn
add weekday varchar(1) NULL ;

alter table student_learn_task
add weekday varchar(1) NULL ;

-- step 2 execute command to correct data
update student_learn
set
	weekday = weekday(check_date)+1;

update student_learn
set
	weekday = 0
where weekday = 7;

--  step 3 correct data on table:student_learn_task
update student_learn_task
set
	weekday = weekday(task_date)+1;

update student_learn_task
set
	weekday = 0
where weekday = 7;


-- step 4 add new columns for verify created and updated user
ALTER TABLE student_learn
ADD  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE student_learn
ADD  `created_user`     varchar(36) NULL COMMENT 'keep in UUID format';

ALTER TABLE student_learn
ADD  `updated_datetime` DATETIME NULL;

ALTER TABLE student_learn
ADD  `updated_user`     varchar(36) COMMENT 'keep in UUID format';

ALTER TABLE student_learn
ADD  `teacher_id` varchar(36) NOT NULL;

ALTER TABLE student_learn
ADD  `SubjectCode` varchar(6)  NOT NULL COMMENT 'รหัสวิชา';




ALTER TABLE student_learn_task
ADD  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE student_learn_task
ADD  `created_user`     varchar(36) NULL COMMENT 'keep in UUID format';

ALTER TABLE student_learn_task
ADD  `updated_datetime` DATETIME NULL;

ALTER TABLE student_learn_task
ADD  `updated_user`     varchar(36) COMMENT 'keep in UUID format';

ALTER TABLE student_learn_task
ADD  `teacher_id` varchar(36) NOT NULL;

ALTER TABLE student_learn_task
ADD  `SubjectCode` varchar(6)  NOT NULL COMMENT 'รหัสวิชา';

ALTER TABLE student_learn_task
ADD  `is_teaching_substitute` varchar(1)  NOT NULL COMMENT '1=สอนแทน,0=ครูผู้สอนสอนเอง';

-- step 5 update GUID created_user
update student_learn s left join users_account u 
    on (s.user_create = concat(u.user_account_prefix, u.user_account_firstname,' ',u.user_account_lastname))
set
	s.created_user = u.user_account_id;

-- step 6 add primary key to table: student_learn
-- column: row_id
ALTER TABLE `student_learn` ADD PRIMARY KEY(`row_id`);


ALTER TABLE `teaching_schedule`
ADD  `room_id` varchar(3) NULL COMMENT '101,610';

UPDATE
    `teaching_schedule`
SET
    `room_id` = concat(level,'0',room);
    

-- บันทึกการสอน by adding columns in to table: teachers_learn
ALTER TABLE `teachers_learn` CHANGE `roow_id` `row_id` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `teachers_learn` ADD PRIMARY KEY(`row_id`);

ALTER TABLE `teachers_learn`
ADD  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `teachers_learn`
ADD  `created_user`     varchar(36) NULL COMMENT 'keep in UUID format';

ALTER TABLE `teachers_learn`
ADD  `updated_datetime` DATETIME NULL;

ALTER TABLE `teachers_learn`
ADD  `updated_user`     varchar(36) COMMENT 'keep in UUID format';

ALTER TABLE `teachers_learn`
ADD  `teacher_id` varchar(36) NOT NULL;

ALTER TABLE `teachers_learn`
ADD  `SubjectCode` varchar(6)  NOT NULL COMMENT 'รหัสวิชา';

ALTER TABLE `teachers_learn`
ADD  `is_teaching_substitute` varchar(1)  NOT NULL COMMENT '1=สอนแทน,0=ครูผู้สอนสอนเอง';

alter table `teachers_learn`
add weekday varchar(1) NULL ;


update `teachers_learn`
set
	weekday = weekday(check_date)+1;

update `teachers_learn`
set
	weekday = 0
where weekday = 7;

ALTER TABLE `teachers_learn` 
    CHANGE `acadyear` `acadyear` INT(11) NOT NULL AFTER `row_id`, 
    CHANGE `acadsemester` `acadsemester` INT(11) NOT NULL AFTER `acadyear`, 
    CHANGE `weekday` `weekday` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `check_date`, 
    CHANGE `SubjectCode` `SubjectCode` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'รหัสวิชา' AFTER `weekday`, 
    CHANGE `teacher_id` `teacher_id` VARCHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `SubjectCode`, 
    CHANGE `is_teaching_substitute` `is_teaching_substitute` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '1=สอนแทน,0=ครูผู้สอนสอนเอง' AFTER `created_datetime`;


ALTER TABLE `teaching_substitute` CHANGE `confirmed_datetime` `confirmed_datetime` DATETIME NULL DEFAULT NULL;

ALTER TABLE `teaching_substitute` CHANGE `approved_datetime` `approved_datetime` DATETIME NULL DEFAULT NULL;



ALTER TABLE `teachers_learn` CHANGE `SubjectCode` `SubjectCode` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'รหัสวิชา';

ALTER TABLE
    `teachers_learn` CHANGE `teacher_id` `teacher_id` VARCHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
    CHANGE `is_teaching_substitute` `is_teaching_substitute` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '1=สอนแทน,0=ครูผู้สอนสอนเอง';

