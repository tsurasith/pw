
-- Table: `users_account` สำหรับเก็บข้อมูลผู้ใช้งานระบบ

DROP TABLE IF EXISTS `users_account`;
CREATE TABLE IF NOT EXISTS `users_account` (
  `user_account_id` varchar(36) NOT NULL COMMENT 'keep in UUID format',
  `user_account_prefix` varchar(200) NOT NULL,
  `user_account_firstname` varchar(200) NOT NULL,
  `user_account_lastname` varchar(200) NOT NULL,
  `user_account_email` varchar(200) NULL,
  `user_account_line_id` varchar(50) NULL,
  `user_account_status` varchar(20) NOT NULL,
  `user_account_type` varchar(255) NULL COMMENT 'teacher,student,parent,guess',
  `user_account_logon` varchar(100) NULL ,
  `user_account_password` varchar(255) NULL ,
  `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`     varchar(36) NULL COMMENT 'keep in UUID format',
  `updated_datetime` DATETIME NULL ,
  `updated_user`     varchar(36) COMMENT 'keep in UUID format',
  `last_password_chaged` DATETIME NULL ,
  PRIMARY KEY (`user_account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/* 
    password = hash("sha256","password_to_encrypt");
*/



/* script to migrate from table: teacher
SELECT
    concat('XXXXXXXX-',right(rand(),4),'-XXXX-',right(rand(),4),'-XXXXXXXXXXXX'),
    PREFIX, firstname, LASTNAME, t_email, 'active', 'teacher', username, PASSWORD, CURRENT_TIMESTAMP
FROM
    teachers
*/