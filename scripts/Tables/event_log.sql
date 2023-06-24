


DROP TABLE IF EXISTS
    `events_log`;

CREATE TABLE IF NOT EXISTS `events_log`(
    `event_log_id`              INT(11) ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'Running number',
    `event_action_type_code`    INT(2) ZEROFILL NOT NULL COMMENT 'ประเภทการกระทำ CRUD',
    `event_module_code`         INT(2) ZEROFILL NOT NULL COMMENT 'รหัสโมดูลงาน',
    `event_task_code`           INT(4) ZEROFILL NOT NULL COMMENT 'รหัสการทำงานย่อยในโมดูลงาน',
    `event_datetime`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `event_user_account_id`     VARCHAR(36) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
    `acadyear`                  INT(4) NOT NULL COMMENT 'ปีการศึกษา',
    `acadsemester`              INT(1) NOT NULL COMMENT 'ภาคเรียน',
    `event_key`                 VARCHAR(100) NOT NULL COMMENT 'รหัสป้องกันการทำงานซ้ำ',
    `event_details`             VARCHAR(255) NULL COMMENT 'รายละเอียดการทำงาน',
    PRIMARY KEY(`event_log_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;




DROP TABLE IF EXISTS
    `event_module`;

CREATE TABLE IF NOT EXISTS `event_module`(
    `event_module_code`    INT(2) ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสโมดูลงาน',
    `event_module_name`    VARCHAR(100) NOT NULL COMMENT 'ชื่อโมดูลงาน',
    `event_module_description` VARCHAR(255) NOT NULL COMMENT 'คำอธิบายเพิ่มเติม',
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_user` VARCHAR(36) NULL,
    `updated_datetime` DATETIME NULL,
    `updated_user` VARCHAR(36) NULL,
    PRIMARY KEY(`event_module_code`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `event_module` 
    (event_module_code,event_module_name,event_module_description) 
    VALUES (1,'บันทึกหน้าเสาธง','ระบบงานกิจกรรมหน้าเสาธง');
INSERT INTO `event_module` 
    (event_module_code,event_module_name,event_module_description) 
    VALUES (2,'บันทึกการเข้าเรียน','งานวิชาการ บันทึกเข้าเรียน');
INSERT INTO `event_module` 
    (event_module_code,event_module_name,event_module_description) 
    VALUES (3,'ติดตามผลการเรียน','ระบบงานกิจกรรมหน้าเสาธง');
INSERT INTO `event_module` 
    (event_module_code,event_module_name,event_module_description) 
    VALUES (4,'ธนาคารความดี','ธนาคารความดี');


DROP TABLE IF EXISTS
    `event_action_type`;

CREATE TABLE IF NOT EXISTS `event_action_type`(
    `event_action_type_code`    INT(2) ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'ประเภทการกระทำ CRUD',
    `event_action_type_name`    VARCHAR(36) NOT NULL COMMENT 'การทำงาน',
    `event_action_type_description` VARCHAR(255) NOT NULL COMMENT 'คำอธิบาย',
    PRIMARY KEY(`event_action_type_code`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (1,'ADDED','การเพิ่มข้อมูลใหม่');
INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (2,'MODIFIED','แก้ไขข้อมูล');
INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (3,'DELETED','ลบข้อมูล');
INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (4,'SEARCH','ค้นหาข้อมูล');
INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (5,'EXPORTED','ส่งข้อมูลออก');
INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (6,'PRINTED','สั่งพิมพ์');
INSERT INTO `event_action_type` 
    (event_action_type_code,event_action_type_name,event_action_type_description) 
    VALUES (7,'UPLOADED','อัปโหลดข้อมูล');
    

DROP TABLE IF EXISTS
    `event_task`;
    
CREATE TABLE IF NOT EXISTS `event_task`(
    `event_task_code`       INT(4) ZEROFILL NOT NULL AUTO_INCREMENT COMMENT 'รหัสงานย่อย',
    `event_module_code`     INT(2) ZEROFILL NOT NULL COMMENT 'รหัสโมดูลงาน',
    `event_task_name`       VARCHAR(100) NOT NULL COMMENT 'ชื่อโมดูลงาน',
    `event_task_description` VARCHAR(255) NOT NULL COMMENT 'คำอธิบายเพิ่มเติม',
    `event_task_owner`      VARCHAR(36) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
    `average_time_count`    INT NOT NULL COMMENT 'ตีค่าเฉลี่ยการทำงาน (นาที)',
    `event_task_point_count`     INT NOT NULL DEFAULT 0 COMMENT 'น้ำหนักคะแนน',
    `event_task_year`     VARCHAR(4) NOT NULL COMMENT 'ปีงบประมาณ',
    `created_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_user` VARCHAR(36) NULL,
    `updated_datetime` DATETIME NULL,
    `updated_user` VARCHAR(36) NULL,
    PRIMARY KEY(`event_task_code`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;



ALTER TABLE `events_log`
    ADD CONSTRAINT `fk_event_action_type` FOREIGN KEY (`event_action_type_code`) REFERENCES `event_action_type`(`event_action_type_code`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `events_log`
    ADD CONSTRAINT `fk_event_module` FOREIGN KEY (`event_module_code`) REFERENCES `event_module`(`event_module_code`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `events_log`
    ADD CONSTRAINT `fk_event_task` FOREIGN KEY (`event_task_code`) REFERENCES `event_task`(`event_task_code`) ON DELETE CASCADE ON UPDATE CASCADE;









