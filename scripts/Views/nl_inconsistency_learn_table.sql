
-- inconsistency learn table
DROP VIEW IF EXISTS `nl_inconsistency_learn_table`;


CREATE VIEW `nl_inconsistency_learn_table`  AS 

    SELECT
        s.task_roomid AS task_roomid,
        s.SubjectCode AS learn_task,
        t.SubjectCode AS teach_sched
    FROM
        (
            student_learn_task s LEFT JOIN teaching_schedule t
            ON
            (
                (
                    (s.acadyear = t.acadyear) AND(
                        s.acadsemester = t.acadsemester
                    ) AND(s.weekday = t.weekday) AND(s.period = t.period) AND(s.task_roomid = t.room_id)
                )
            )
        )
    WHERE
        (
            (s.SubjectCode <> t.SubjectCode) AND (s.SubjectCode <> '') AND (t.room_id <> '000')
        )
    ORDER BY
        s.task_roomid
;
