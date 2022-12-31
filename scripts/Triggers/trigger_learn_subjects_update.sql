

-- TRIGGER to handle learn_subjects_history

DROP TRIGGER IF EXISTS learn_subjects_history;

DELIMITER $$

CREATE TRIGGER learn_subjects_history
    AFTER UPDATE
    ON `learn_subjects` FOR EACH ROW
BEGIN

    INSERT INTO `learn_subjects_history`(
    `SubjectCode`,
    `SubjectName`,
    `SubjectUnit`,
    `SubjectHour`,
    `SubjectType`,
    `FirstGroup`,
    `SecondGroup`,
    `SubjectClass`,
    `SubjectYear`,
    `acadyear`,
    `acadsemester`,
    `level`,
    `teacher_user_id_list`,
    `created_datetime`,
    `created_user`,
    `updated_datetime`,
    `updated_user_id`
)
VALUES(
    NEW.`SubjectCode`,
    NEW.`SubjectName`,
    NEW.`SubjectUnit`,
    NEW.`SubjectHour`,
    NEW.`SubjectType`,
    NEW.`FirstGroup`,
    NEW.`SecondGroup`,
    NEW.`SubjectClass`,
    NEW.`SubjectYear`,
    NEW.`acadyear`,
    NEW.`acadsemester`,
    NEW.`level`,
    NEW.`teacher_user_id_list`,
    NEW.`created_datetime`,
    NEW.`created_user`,
    NEW.`updated_datetime`,
    NEW.`updated_user_id`
);

END$$    

DELIMITER ;



