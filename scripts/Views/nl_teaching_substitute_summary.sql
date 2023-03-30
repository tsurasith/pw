

-- Structure for view `nl_teaching_substitute_summary`

DROP VIEW IF EXISTS `nl_teaching_substitute_summary`;


CREATE VIEW `nl_teaching_substitute_summary`  AS 
    select 
        ts.teacher_id as 'sub_teacher_id',
        concat(t1.firstname,' ',t1.lastname) as 'sub_teacher',
        ts.owner_teacher_id as own_teacher_id,
        concat(t2.firstname,' ',t2.lastname) as 'own_teacher',
        ts.teaching_date,
        ts.SubjectCode,
        ts.weekday,
        ts.period,
        ts.room_id,
        ifnull(ts.approved_user_id,'') as approved_user_id,
        ifnull(concat(ap.firstname,' ',ap.lastname),'') as 'approver',
        ts.teaching_substitute_status,
        case
            when ts.teaching_substitute_status = 0 then 'แจ้งครูผู้สอนและฝ่ายวิชาการทราบแล้ว'
            when ts.teaching_substitute_status = 1 then 'ครูผู้สอนยืนยันรับทราบ'
            when ts.teaching_substitute_status = 2 then 'บันทึกการเข้าสอนแล้ว'
            when ts.teaching_substitute_status = 3 then 'เสร็จสิ้น'
            else 'ไม่ระบุ'
        end as 'teaching_status_meaning',
        ts.acadyear,
        ts.acadsemester,
        ifnull(ts.has_assignment,'') as has_assignment,
        case
            when ts.has_assignment = 0 then 'ไม่มี'
            when ts.has_assignment = 1 then 'มี'
            else 'ไม่ระบุ'
        end as has_assignment_meaning,
        ifnull(ts.teaching_details,'')   as teaching_details,
        ifnull(ts.confirmed_datetime,'') as confirmed_datetime,
        ifnull(ts.approved_datetime,'')  as approved_datetime,
        ifnull(ts.updated_datetime,'')   as updated_datetime,
        case 
            when ts.confirmed_datetime = ts.updated_datetime then 'ยืนยันเข้าสอนพร้อมบันทึกการสอน'
            when ts.confirmed_datetime < ts.updated_datetime then 'ยืนยันเข้าสอนก่อนบันทึกการสอน'
            else 'ไม่มีข้อมูลบันทึกเข้าสอน'
        end as confirm_meaning
    from
    teaching_substitute ts left join teachers t1 
    on (ts.teacher_id = t1.teacher_id)
    left join teachers t2 
    on (ts.owner_teacher_id = t2.teacher_id)
    left join teachers ap 
    on (ts.approved_user_id = ap.teacher_id)
;



