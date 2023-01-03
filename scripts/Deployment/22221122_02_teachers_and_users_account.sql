

-- 1. add column teacher_id to table: teachers
ALTER TABLE `teachers` ADD `teacher_id` VARCHAR(36) NOT NULL FIRST;


-- 2. drop existing trigger on the table: teachers
DROP TRIGGER IF EXISTS trigger_teachers_update;


-- 3. update teacher_id to sync and make it equals on table: users_account
UPDATE  
    teachers t INNER JOIN users_account u on (t.username = u.user_account_logon)
SET
    t.teacher_id = u.user_account_id;


-- 4. recreate a trigger on the table: teachers
DELIMITER $$

CREATE TRIGGER trigger_teachers_update
    AFTER UPDATE
    ON `teachers` FOR EACH ROW
BEGIN

    UPDATE
    `users_account`
    SET
        `user_account_prefix`       = NEW.`prefix`,
        `user_account_firstname`    = NEW.`firstname`,
        `user_account_lastname`     = NEW.`lastname`,
        `user_account_email`        = NEW.`t_email`,
        `user_account_type`         = NEW.`type`,
        `user_account_logon`        = NEW.`username`,
        `user_account_password`     = sha2(NEW.`password`,256),
        `updated_datetime` = CURRENT_TIMESTAMP
    WHERE
        `user_account_logon` = OLD.`username`;

END$$    

DELIMITER ;


-- 5. update empty email to null value
UPDATE teachers
SET
    t_email = NULL
WHERE
    length(t_email) < 3;


-- 5. Drop existing primary key
ALTER TABLE `teachers` DROP PRIMARY KEY;


-- 6. Adding a new primary key
ALTER TABLE `teachers` ADD PRIMARY KEY(`teacher_id`);


-- 7. Adding unique value on 2 data columns
ALTER TABLE `teachers` ADD UNIQUE(`TeacCode`);
ALTER TABLE `teachers` ADD UNIQUE(`t_email`);





