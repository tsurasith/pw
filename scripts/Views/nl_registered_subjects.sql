

-- Structure for view `nl_registered_subjects`

DROP VIEW IF EXISTS `nl_registered_subjects`;


CREATE VIEW `nl_registered_subjects`  AS 
    select 
        su.SubjectCode,
        ss.SubjectName,
        ss.SubjectUnit,
        ss.SubjectHour,
        su.subject_level,
        ss.SubjectGroup,    
        sg.subject_group_name,
        su.acadyear,
        su.acadsemester,
        su.is_split_class,
        ss.SubjectType,
        (
            select count(*) from register_students rt
            where
                su.subject_register_id = rt.subject_register_id
        ) as total_registered
    from
        register_subjects su 
        left join curriculum_subjects ss      on (su.SubjectCode = ss.SubjectCode)
        left join curriculum_subject_group sg on (ss.SubjectGroup = sg.subject_group_id)

;



