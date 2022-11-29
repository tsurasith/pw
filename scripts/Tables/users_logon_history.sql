
-- Table: `users_logon_history` สำหรับเก็บข้อมูลผู้ใช้งานระบบ

DROP TABLE IF EXISTS `users_logon_history`;
CREATE TABLE IF NOT EXISTS `users_logon_history` (
  `user_logon_history_id` varchar(36) NOT NULL COMMENT 'keep in UUID format',
  `user_account_id` varchar(36) NULL COMMENT 'keep in UUID format',
  `user_logon_by` varchar(200) NOT NULL COMMENT 'email,username',
  `user_ip` varchar(100) NOT NULL,
  `user_browser` varchar(500) NOT NULL,
  `user_logon_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_logon_result` varchar(36) NOT NULL COMMENT 'success,failed',
  PRIMARY KEY (`user_logon_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


/* 
  PHP cod like this
    password = hash("sha256","password_to_encrypt");

*/

