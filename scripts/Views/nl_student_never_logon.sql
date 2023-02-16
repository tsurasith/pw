
-- student never logon to SAAIS

SELECT
    s.ID AS ID,
    s.PREFIX AS PREFIX,
    s.FIRSTNAME AS FIRSTNAME,
    s.LASTNAME AS LASTNAME,
    CONCAT((((s.xLevel - 3) * 3) + s.xYearth),'/',s.ROOM) AS room,
    r.studstatus_description AS studstatus_description
FROM
    (
        students s LEFT JOIN ref_studstatus r
        ON ((s.studstatus = r.studstatus))
    )
WHERE
    (
        (
            NOT(
                s.ID IN(
                SELECT DISTINCT
                    h.user_logon_by
                FROM
                    users_logon_history h
                WHERE
                    (h.user_logon_result = 'success')
            )
            )
        ) AND(s.studstatus = '1')
    )

;