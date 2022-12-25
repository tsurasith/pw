
-- table: curriculum_subjects

DROP TABLE IF EXISTS `curriculum_subjects`;

CREATE TABLE IF NOT EXISTS `curriculum_subjects` (
  `SubjectCode`     varchar(6)  NOT NULL COMMENT 'รหัสวิชา',
  `SubjectName`     varchar(255)  NOT NULL COMMENT 'ชื่อวิชา',
  `SubjectUnit`     DECIMAL(4,1) NOT NULL DEFAULT 0.0 COMMENT 'หน่วยกิต',
  `SubjectHour`     INT(11) NULL DEFAULT 0 COMMENT 'จำนวนชั่วโมง',
  `SubjectType`     varchar(30)  NOT NULL COMMENT 'ประเภทวิชา พื้นฐาน/เพิ่มเติม',
  `SubjectGroup`    varchar(255)  NOT NULL COMMENT 'กลุ่มสาระ',
  `SubjectLevel`    varchar(6) NOT NULL DEFAULT 0 COMMENT 'ระดับชั้นที่เปิดสอน (0=เรียนได้ทุกชั้น,1,2,3,4,5,6)',
  `SubjectSemester` varchar(1) NOT NULL DEFAULT 0 COMMENT 'ภาคเรียน (0=ทุกภาคเรียน,1,2)',
  `is_split_class`  varchar(1) NOT NULL COMMENT 'แบ่งกลุ่มเรียน',
  `subject_status`   varchar(20) NULL DEFAULT 'ACTIVE' ,
  `subject_description`   TEXT NULL ,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`      varchar(36) NULL,
  `updated_datetime`  DATETIME NULL,
  `updated_user_id`   varchar(36) NULL,
  PRIMARY KEY (`SubjectCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE table `curriculum_subjects`;

INSERT INTO `curriculum_subjects`(
    `SubjectCode`,
    `SubjectName`,
    `SubjectUnit`,
    `SubjectHour`,
    `SubjectType`,
    `SubjectGroup`,
    `SubjectLevel`,
    `SubjectSemester`,
    `is_split_class`
)
select 
 SubjectCode,
 SubjectName,
 SubjectUnit,
 SubjectHour,
 trim(substr(SubjectType,3,length(SubjectType))) as SubjectType,
 trim(substr(FirstGroup,3,length(FirstGroup))) as SubjectGroup,
 right(subjectClass,1) as SubjectLevel,
 left(subjectyear,1) as acadsemester,
 '0' as is_split_class
from learn_subjects
WHERE
SubjectYear like '%2565'
and SubjectCode not in 
(
	select SubjectCode from learn_subjects
    where
		SubjectYear like '%2565'
    group by SubjectCode
    having count(subjectcode)>1
)
order by SubjectClass
;

-- migrate round 2 -- execute more than 1 time
INSERT INTO `curriculum_subjects`(
    `SubjectCode`,
    `SubjectName`,
    `SubjectUnit`,
    `SubjectHour`,
    `SubjectType`,
    `SubjectGroup`,
    `SubjectLevel`,
    `SubjectSemester`,
    `is_split_class`
)
select 
 SubjectCode,
 SubjectName,
 SubjectUnit,
 SubjectHour,
 trim(substr(SubjectType,3,length(SubjectType))) as SubjectType,
 trim(substr(FirstGroup,3,length(FirstGroup))) as SubjectGroup,
 right(subjectClass,1) as SubjectLevel,
 left(subjectyear,1) as acadsemester,
 '0' as is_split_class
from learn_subjects
WHERE
SubjectYear like '%2565'
and SubjectCode not in 
(
	select SubjectCode from curriculum_subjects
)
order by SubjectClass;

-- migrate round 3 -- execute until return 0 row
INSERT INTO `curriculum_subjects`(
    `SubjectCode`,
    `SubjectName`,
    `SubjectUnit`,
    `SubjectHour`,
    `SubjectType`,
    `SubjectGroup`,
    `SubjectLevel`,
    `SubjectSemester`,
    `is_split_class`
)
select 
 SubjectCode,
 SubjectName,
 SubjectUnit,
 SubjectHour,
 trim(substr(SubjectType,3,length(SubjectType))) as SubjectType,
 trim(substr(FirstGroup,3,length(FirstGroup))) as SubjectGroup,
 right(subjectClass,1) as SubjectLevel,
 left(subjectyear,1) as acadsemester,
 '0' as is_split_class
from learn_subjects
WHERE
SubjectYear like '%2564'
and SubjectCode not in 
(
	select SubjectCode from curriculum_subjects
)
order by SubjectClass;


-- migrate round 04 -- execute until return 0 rows
INSERT INTO `curriculum_subjects`(
    `SubjectCode`,
    `SubjectName`,
    `SubjectUnit`,
    `SubjectHour`,
    `SubjectType`,
    `SubjectGroup`,
    `SubjectLevel`,
    `SubjectSemester`,
    `is_split_class`
)
select 
 SubjectCode,
 SubjectName,
 SubjectUnit,
 SubjectHour,
 trim(substr(SubjectType,3,length(SubjectType))) as SubjectType,
 trim(substr(FirstGroup,3,length(FirstGroup))) as SubjectGroup,
 right(subjectClass,1) as SubjectLevel,
 left(subjectyear,1) as acadsemester,
 '0' as is_split_class
from learn_subjects
WHERE
SubjectYear like '%2563'
and SubjectCode not in 
(
	select SubjectCode from curriculum_subjects
)
order by SubjectClass;



-- update กลุ่มสาระเป็นตัวเลข
UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '1'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'ภาษาไทย';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '2'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'คณิตศาสตร์';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '3'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'วิทยาศาสตร์และเทคโนโลยี';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '4'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'สังคมศึกษา ศาสนาและวัฒนธรรม';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '5'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'สุขศึกษาและพลศึกษา';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '6'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'ศิลปะ';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '7'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'การงานอาชีพ';

UPDATE `curriculum_subjects`
SET
    `subjectgroup` = '8'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'ภาษาต่างประเทศ';

UPDATE
    `curriculum_subjects`
SET
    `subjectgroup` = '9'
WHERE
    `curriculum_subjects`.`SubjectGroup` = 'กิจกรรมพัฒนาผู้เรียน';



