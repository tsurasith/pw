
-- Table: student_learn_task
-- select example
select 
    l.SubjectCode as l_SubjectCode,
    t.*
FROM
	teaching_schedule t inner join 
    student_learn_task l 
    on (
         t.acadyear     = l.acadyear and 
         t.acadsemester = l.acadsemester and 
         t.weekday      = l.weekday and 
         t.period       = l.period and 
         t.room_id      = l.task_roomid 
        )
where 
	t.SubjectCode != l.SubjectCode
;

-- update
UPDATE
	teaching_schedule t inner join 
    student_learn_task l 
    on (
         t.acadyear     = l.acadyear and 
         t.acadsemester = l.acadsemester and 
         t.weekday      = l.weekday and 
         t.period       = l.period and 
         t.room_id      = l.task_roomid and 
         t.teacher_id   = l.teacherr_id
        )
SET 
    l.teacher_id = t.teacher_id,
    l.SubjectCode = t.SubjectCode
where 
	t.SubjectCode != l.SubjectCode
;


-- -------------------------
-- -------------------------

-- Table: student_learn
-- select example

select 
	t.teacher_id  as t_teacher_id,
    t.SubjectCode as t_SubjectCode,
    t.club_code,
    l.teacher_id,
    l.SubjectCode,
    l.task_status
FROM
	teaching_schedule t inner join 
    student_learn l 
    on (
         t.acadyear = l.acadyear and 
         t.acadsemester = l.acadsemester and 
         t.weekday = l.weekday and 
         t.period = l.period and 
         t.room_id = l.class_id and 
         t.club_code = l.club_code and 
         t.teacher_id = l.teacher_id
        )
where 
    1=1
    and t.SubjectCode != l.SubjectCode
;

-- update
UPDATE
	teaching_schedule t inner join 
    student_learn l 
    on (
         t.acadyear = l.acadyear and 
         t.acadsemester = l.acadsemester and 
         t.weekday = l.weekday and 
         t.period = l.period and 
         t.room_id = l.class_id and 
         t.club_code = l.club_code and 
         t.teacher_id = l.teacher_id
        )
set 
    l.teacher_id  = t.teacher_id,
    l.SubjectCode = t.SubjectCode        
where 
    1=1
    and t.SubjectCode != l.SubjectCode
;




-- -------------------------
-- -------------------------

-- Table: teaching_record
-- select example

select 
	t.teacher_id,t.SubjectCode,t.club_code,
    l.teacher_id,l.SubjectCode,l.approved_user_id
FROM
	teaching_schedule t inner join 
    teaching_record l 
    on (
         t.acadyear     = l.acadyear and 
         t.acadsemester = l.acadsemester and 
         t.weekday      = l.weekday and 
         t.period       = l.period and 
         t.room_id      = l.room_id and 
         t.teacher_id   = l.teacher_id
        )
;


-- update 
UPDATE
	teaching_schedule t inner join 
    teaching_record l 
    on (
         t.acadyear     = l.acadyear and 
         t.acadsemester = l.acadsemester and 
         t.weekday      = l.weekday and 
         t.period       = l.period and 
         t.room_id      = l.room_id and 
         t.teacher_id   = l.teacher_id
        )
set 
    l.SubjectCode = t.SubjectCode        

;



-- STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
