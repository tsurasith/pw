

-- Structure for view `nl_registered_subjects_group_teachers`

DROP VIEW IF EXISTS `nl_registered_subjects_group_teachers`;


CREATE VIEW `nl_registered_subjects_group_teachers`  AS 
    select 
        su.SubjectCode,
        ss.SubjectName,
        te.club_code,
        cc.club_name,
        ss.SubjectUnit,
        ss.SubjectHour,
        su.subject_level,
        te.room_id,
        ss.SubjectGroup,
        sg.subject_group_name,
        ss.SubjectType,
        te.teacher_id,
        concat(tt.firstname,' ',tt.lastname) as teacher_name,
        su.acadyear,
        su.acadsemester,
        (
            select count(*) from register_students rt inner join students s
            on (rt.student_id = s.id and rt.acadyear = s.xEDBE)
            where
                su.subject_register_id = rt.subject_register_id and 
                right(te.room_id,1)    = s.room and 
                su.subject_level       = ((s.xlevel-3)*3)+xyearth
        ) as class_registered,
        (
            select count(*) from register_students rt inner join students s
            on (rt.student_id = s.id and rt.acadyear = s.xEDBE)
            where
                su.subject_register_id = rt.subject_register_id and 
                su.subject_level       = ((s.xlevel-3)*3)+xyearth and
                rt.club_code           = te.club_code
        ) as level_registered,
        su.is_split_class
    from
        register_subjects su 
        left join register_teachers te        on (su.subject_register_id = te.subject_register_id)
        left join curriculum_subjects ss      on (su.SubjectCode = ss.SubjectCode)
        left join curriculum_subject_group sg on (ss.SubjectGroup = sg.subject_group_id)
        left join teachers tt                 on (te.teacher_id = tt.teacher_id)
        left join curriculum_clubs cc         on (te.club_code  =  cc.club_code)
    where
        concat(su.is_split_class,te.club_code) != '10000'
;



