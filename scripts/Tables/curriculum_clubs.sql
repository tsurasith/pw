
-- table: curriculum_clubs

DROP TABLE IF EXISTS `curriculum_clubs`;

CREATE TABLE IF NOT EXISTS `curriculum_clubs` (
  `club_code`          INT(4) ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสชุมนุม',
  `club_name`          varchar(255)  NOT NULL COMMENT 'ชื่อชุมนุม',
  `club_level`         varchar(1) NOT NULL DEFAULT 0 COMMENT 'ระดับชั้นที่เปิดสอน (0=เรียนได้ทุกชั้น,3=ม.ต้น,4=ม.ปลาย)',
  `club_status`        varchar(20) NULL DEFAULT 'ACTIVE' ,
  `club_description`   varchar(500) NULL ,
  `created_datetime`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user`       varchar(36) NULL,
  `updated_datetime`   DATETIME NULL,
  `updated_user`       varchar(36) NULL,
  PRIMARY KEY (`club_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0001','ประดิษฐ์ดอกไม้ริบบิ้น','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0002','วัฒนธรรมดอกไม้','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0003','สังคมน่ารู้','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0004','English for you','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0005','นิทานภาษาอังกฤษ','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0006','English around the world','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0007','ภาษาอังกฤษ','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0008','ทวิศึกษา','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0009','ห้องสมุด','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0010','ส่งเสริมสุขภาพ','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0011','Trick Math','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0012','คณิตศาสตร์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0013','คณิตคิดสนุก','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0014','งานช่างทั่วไป','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0015','พลังงานทดแทน','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0016','ทดลองวิทยาศาสตร์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0017','พืชสมุนไพร','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0018','ติวเคมี,ฟิสิกส์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0019','ยูทูบเบอร์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0020','TO BE NUMBER ONE','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0021','ข่าววิทยาศาสตร์น่ารู้','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0022','กีฬาฟุตซอล','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0023','อาหารเพื่อสุขภาพ','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0024','รวมศิลปะสร้างสรรค์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0025','ศิลปะสร้างสรรค์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0026','ดนตรีสากล','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0027','อาหารไทย','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0028','ดนตรีไทย','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0029','Eng  Entertertain','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0030','ดาราศาสตร์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0031','วอลเลย์บอล','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0032','ธนาคารโรงเรียน','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0033','Cover  dance  นาฏศิลป์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0034','คณิตสร้างสรรค์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0035','ภาษาไทย','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0036','Emglish is fun one','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0037','ภาษาจีน','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0038','ชุมนุมหุ่นยนต์','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0039','พอเพียง','0','ACTIVE');
INSERT INTO curriculum_clubs (club_code,club_name,club_level,club_status ) VALUES ('0040','สวนพฤกษศาสตร์','0','ACTIVE');


