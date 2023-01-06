


CREATE TABLE IF NOT EXISTS `hr_fingerprint_tmp` (
  `user_id` varchar(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `check_date` varchar(25) NOT NULL,
  `clock_in` varchar(20) DEFAULT NULL,
  `clock_out` varchar(20) DEFAULT NULL,
  `imported_user_id` varchar(36) NOT NULL,
  `imported_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`check_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;


