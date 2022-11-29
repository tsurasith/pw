
-- TRIGGER to handle users_account

DROP TRIGGER IF EXISTS trigger_teachers_update;

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



