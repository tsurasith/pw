
-- 01. table



-- 02. table




-- 03. migrate data
INSERT INTO `hr_fingerprint_import_mapping`(
    `finger_user_id`,
    `teacher_id`,
    `import_status`
)
select
	h.user_id,
    t.teacher_id,
    'ACTIVE' as import_status
from hr_fingerprint_tmp h inner join teachers t
	on (trim(left(replace(replace(replace(user_name,"น.ส.",""),"นางสาว",""),"นาย",""),locate('.',replace(replace(replace(user_name,"น.ส.",""),"นางสาว",""),"นาย",""))-1)) = t.FIRSTNAME)
where
	check_date = '28.12.2022'
