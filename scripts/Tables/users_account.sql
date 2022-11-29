
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

-- script to migrate from table: teacher
INSERT INTO users_account 
    (
        user_account_id,
        user_account_prefix,
        user_account_firstname,
        user_account_lastname,
        user_account_email,
        user_account_status,
        user_account_type,
        user_account_logon,
        user_account_password,
        created_datetime
    )
SELECT
    concat('XXXXXXXX-',right(rand(),4),'-XXXX-',right(rand(),4),'-XXXXXXXXXXXX'),
    PREFIX, firstname, LASTNAME, t_email, 'active', 'teacher', username, PASSWORD, CURRENT_TIMESTAMP
FROM
    teachers;

-- initial user_account_id for all current user.
 UPDATE users_account set user_account_id = '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16' where user_account_logon = 'admin' ; 
 UPDATE users_account set user_account_id = 'D6806031-6121-4BA3-913E-DF80EDD06932' where user_account_logon = 'tc100' ; 
 UPDATE users_account set user_account_id = 'D359A5FF-C4DD-4FE2-8E66-07C4ADC24DA8' where user_account_logon = 'tc101' ; 
 UPDATE users_account set user_account_id = '3D9F3821-13EC-4C70-9263-F21C0A37E718' where user_account_logon = 'tc102' ; 
 UPDATE users_account set user_account_id = '6F86006F-CF5D-4836-AD15-30C1237A5547' where user_account_logon = 'tc103' ; 
 UPDATE users_account set user_account_id = 'ABE7F927-44E0-473A-8024-D0D312A85A7F' where user_account_logon = 'tc104' ; 
 UPDATE users_account set user_account_id = '0EE423A6-F27E-431A-AB57-9BA500335B92' where user_account_logon = 'tc105' ; 
 UPDATE users_account set user_account_id = '4354F468-5271-44ED-A6F6-4EE0C8336DCF' where user_account_logon = 'tc106' ; 
 UPDATE users_account set user_account_id = '8A7A13E9-A7C0-4F49-8678-2CB35D07C4C8' where user_account_logon = 'tc107' ; 
 UPDATE users_account set user_account_id = '5DFDB915-2C32-40B9-9B94-64B6C0367B13' where user_account_logon = 'tc108' ; 
 UPDATE users_account set user_account_id = 'F0795CE9-10B7-4995-B3BC-B3AF11CD52C4' where user_account_logon = 'tc109' ; 
 UPDATE users_account set user_account_id = '53BF1A9F-1F6C-437F-B6DC-D4AB027BE0CB' where user_account_logon = 'tc110' ; 
 UPDATE users_account set user_account_id = '4169AEE7-64FC-4A34-8EA3-27796F7214E3' where user_account_logon = 'tc111' ; 
 UPDATE users_account set user_account_id = 'AC822532-D79E-4577-A89B-76CA7A06F224' where user_account_logon = 'tc112' ; 
 UPDATE users_account set user_account_id = '51A4223A-8A72-43AF-BB25-BFB2C1070787' where user_account_logon = 'tc113' ; 
 UPDATE users_account set user_account_id = 'BE536FA7-AA37-4BB6-BFBF-F0A234640515' where user_account_logon = 'tc114' ; 
 UPDATE users_account set user_account_id = 'CCD43932-95D3-4A18-8C7C-73C250B0E79B' where user_account_logon = 'tc115' ; 
 UPDATE users_account set user_account_id = '3B5E8FD5-5209-49B9-AADF-8C55CF1EDCCD' where user_account_logon = 'tc116' ; 
 UPDATE users_account set user_account_id = '205B33BB-2EA9-4516-9F3B-801A51C5EE41' where user_account_logon = 'tc117' ; 
 UPDATE users_account set user_account_id = '924B0B7B-BEA2-41F8-9722-FC0290E67E2F' where user_account_logon = 'tc118' ; 
 UPDATE users_account set user_account_id = '72203558-CDB2-4049-869E-FCBFA79CF0F8' where user_account_logon = 'tc119' ; 
 UPDATE users_account set user_account_id = '0CF2CC3D-57FE-410D-A2EC-C6D57B8515AD' where user_account_logon = 'tc120' ; 
 UPDATE users_account set user_account_id = '336EA6C6-7C94-41C7-AEC5-F3B545E84797' where user_account_logon = 'tc121' ; 
 UPDATE users_account set user_account_id = 'E7A1AF49-A14C-4C5A-A448-C5CFDB37E591' where user_account_logon = 'tc122' ; 
 UPDATE users_account set user_account_id = 'BA14BD0C-C867-4021-9A5D-D5114D9F79E1' where user_account_logon = 'tc123' ; 
 UPDATE users_account set user_account_id = 'A9C37C98-25F0-4C2A-ADF6-CAE729372867' where user_account_logon = 'tc124' ; 
 UPDATE users_account set user_account_id = 'B7D080BD-5B53-46FE-AF48-21D4E7003C16' where user_account_logon = 'tc125' ; 
 UPDATE users_account set user_account_id = '606816B7-B427-4B34-A791-3A2AAA1FE6F7' where user_account_logon = 'tc126' ; 
 UPDATE users_account set user_account_id = '3EA5A030-AE0A-4EA7-87FE-6F3E5F61D5D5' where user_account_logon = 'tc127' ; 
 UPDATE users_account set user_account_id = '4839D47E-467B-4E17-9AE2-1025C881B431' where user_account_logon = 'tc128' ; 
 UPDATE users_account set user_account_id = '073F4BF6-4C3F-4C53-BB31-F38531A107EA' where user_account_logon = 'tc129' ; 
 UPDATE users_account set user_account_id = '8B0869F2-E509-49C4-83CE-59DFCB85B9E8' where user_account_logon = 'tc130' ; 
 UPDATE users_account set user_account_id = '89CCBD84-01A2-491E-818D-534792FAD2FC' where user_account_logon = 'tc131' ; 
 UPDATE users_account set user_account_id = 'B62E0900-2AB0-410D-BEEC-BE52D36F2A68' where user_account_logon = 'tc132' ; 
 UPDATE users_account set user_account_id = '8A54DBFC-6DB3-4D8A-8674-943DF75B1256' where user_account_logon = 'tc133' ; 
 UPDATE users_account set user_account_id = 'C212F476-554B-4E51-AD71-941CB0597634' where user_account_logon = 'tc134' ; 
 UPDATE users_account set user_account_id = '379DC8BA-097E-4FEE-BDA6-732D56ADD19A' where user_account_logon = 'tc135' ; 
 UPDATE users_account set user_account_id = '601480A8-4AB5-423F-AC63-AC15D217D0A8' where user_account_logon = 'tc136' ; 
 UPDATE users_account set user_account_id = 'EBF6986B-B2B8-44E1-AB28-D5157223A223' where user_account_logon = 'tc137' ; 
 UPDATE users_account set user_account_id = '5D318FD9-BD66-4C07-8D76-A3D57D71CAA9' where user_account_logon = 'tc138' ; 
 UPDATE users_account set user_account_id = 'ADF1370D-A7FD-4C34-9A08-27848BA02714' where user_account_logon = 'tc139' ; 
 UPDATE users_account set user_account_id = 'A536D6E9-2028-4AC7-990D-CD7B69C24703' where user_account_logon = 'tc140' ; 
 UPDATE users_account set user_account_id = 'E038E061-0C86-4100-BC99-357B374A54F9' where user_account_logon = 'tc141' ; 
 UPDATE users_account set user_account_id = '800400A5-3CFB-4FD2-A393-AE19D7984258' where user_account_logon = 'tc142' ; 
 UPDATE users_account set user_account_id = 'B56AF6C6-9B3C-4ACA-AD73-D9FC3C03E661' where user_account_logon = 'tc143' ; 
 UPDATE users_account set user_account_id = '384C2B17-48EE-4AB1-B9FF-7ABFDBEA65EC' where user_account_logon = 'tc144' ; 
 UPDATE users_account set user_account_id = 'CC682606-B2A9-4296-8ADB-00EB7172F61F' where user_account_logon = 'tc145' ; 
 UPDATE users_account set user_account_id = '34912F2A-6633-441F-9513-77EBFCA53DEC' where user_account_logon = 'tc146' ; 
 UPDATE users_account set user_account_id = 'C97996B8-53DE-402A-84DC-18D6F63237EF' where user_account_logon = 'tc147' ; 
 UPDATE users_account set user_account_id = 'E62A6B29-5B5D-42EB-9163-F502E511F5A6' where user_account_logon = 'tc148' ; 
 UPDATE users_account set user_account_id = '25425CCE-864E-4040-93D7-D2F93A01AAA8' where user_account_logon = 'tc149' ; 
 UPDATE users_account set user_account_id = '6B856AA9-4A71-40C3-B628-DB57AA69165E' where user_account_logon = 'tc150' ; 
 UPDATE users_account set user_account_id = '2A8F3B4A-09B5-42C5-B727-6050A51A2EC6' where user_account_logon = 'tc151' ; 
 UPDATE users_account set user_account_id = 'E97B977C-ECF8-43BE-AB3C-5D9CC958BF80' where user_account_logon = 'tc152' ; 
 UPDATE users_account set user_account_id = 'E8435EEA-03EA-446A-AA9D-161B415AE55B' where user_account_logon = 'tc153' ; 
 UPDATE users_account set user_account_id = 'BE8C8BDE-1B3F-43B3-959D-60B6B05926B8' where user_account_logon = 'tc154' ; 
 UPDATE users_account set user_account_id = '2E9E0440-E1EB-4FB1-9BC2-5C7E3E11AFF0' where user_account_logon = 'tc155' ; 
 UPDATE users_account set user_account_id = '47F86D0E-674A-46D4-B55D-3BEF1AFCB262' where user_account_logon = 'tc156' ; 
 UPDATE users_account set user_account_id = 'A4DFD2C6-3110-4C94-80E9-AE32A66C5955' where user_account_logon = 'tc157' ; 
 UPDATE users_account set user_account_id = 'EA957027-470A-486F-8C29-038B9B7F06A5' where user_account_logon = 'tc158' ; 
 UPDATE users_account set user_account_id = 'B2EA41C4-66BC-4DA2-BBEF-3DDBCC0BD345' where user_account_logon = 'tc159' ; 


