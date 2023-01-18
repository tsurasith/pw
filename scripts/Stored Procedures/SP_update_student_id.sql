/*
	Stored procedure: update_student_id
	เอาไว้ใช้สำหรับการแก้ไข เลขประจำตัวนักเรียน ในระบบ
	การใช้งานให้ส่่ง parameters ดังนี้
	1. ปีการศึกษาที่ต้องการจะแก้ไข
	2. ระดับการศึกษาของนักเรียน ม.ต้น = 3, ม.ปลาย = 4
	3. ชั้นปีของระดับการศึกษา เช่น ม.1=1, ม.2=2, ม.3=3, ม.4=1, ม.5=2, ม.6=3
	4. เลขประจำนักเรียนค่าเดิม
	5. เลขประจำตัวนักเรียนค่าใหม่

	ตัวอย่าง - ถ้าต้องการจะแก้ไขเลขประจำตัวนักเรียน ปีการศึกษา 2565 ของนักเรียนชั้นมัธยมศึกษาปีที่ 6 จากเลขประจำตัว 05001 เป็น 08759 สามารถเรียกคำสั่งได้ดังต่อไปนี้

	call update_student_id('2565','4','3','05001','08759');


*/


DROP PROCEDURE IF EXISTS `update_student_id`;



DELIMITER //
CREATE PROCEDURE `update_student_id` (
                                        IN original_xedbe varchar(4),
                                        IN original_xlevel varchar(1),
                                        IN original_yearth varchar(1),
                                        IN original_id varchar(5),
                                        IN new_id varchar(5)
									  )
BEGIN

	DECLARE count_original_id	INT DEFAULT -1;
    DECLARE count_new_id		INT DEFAULT -1;
    
    DECLARE is_update_available BOOL default false;
        
    DECLARE message VARCHAR(255) DEFAULT 'This is an original message.';
    
    
    SET count_original_id = (	SELECT count(*) 
                                FROM students 
                                WHERE id = original_id and xedbe = original_xedbe and xlevel = original_xlevel and xyearth = original_yearth
                                GROUP BY id );
    
    IF(count_original_id is null) THEN 
		SET is_update_available = false;
        SET message = concat('The original student id: ',original_id,' does not found in the database on acadyear: ', original_xedbe);
	END IF;

    SET count_new_id = (	SELECT count(*) 
							FROM students 
							WHERE id = new_id and xedbe = original_xedbe
							GROUP BY id  );    
    IF(count_new_id >= 1) THEN
		SET is_update_available = false; 
        SET message = concat('The new student id: ',new_id,' is existing in the database on acadyear: ', original_xedbe, '. Cannot change student id to this values');
    END IF;
    
    IF(count_new_id is null and count_original_id = 1) THEN 
		SET is_update_available = true; 
	END IF;
 
	IF(is_update_available) THEN
		-- update student id statements
        SET message = 'OK this can be update';
        
        -- 1. Table: students
        UPDATE students
        SET
			ID = new_id
		WHERE
			ID = original_id and xedbe = original_xedbe and xlevel = original_xlevel and xyearth = original_yearth;
		
        -- 2. Table: grades
        UPDATE learn_grades
        SET
			StudentCode = new_id
		WHERE
			StudentCode = original_id ;

		-- 3. Table: rooms
        UPDATE rooms
        SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;
		
        -- 4. Table: sdq_parent
        UPDATE sdq_parent
        SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;
		
        -- 5. Table: sdq_result
        UPDATE sdq_result
        SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;

		-- 6. Table: sdq_student
		UPDATE sdq_student
		SET
			student_id = new_id
		WHERE	
			student_id = original_id and acadyear = original_xedbe;
		
		-- 7. Table: sdq_teacher
		UPDATE sdq_teacher
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;

		-- 8. Table: student_800
		UPDATE student_800
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;
		
		-- 9. Table: student_color
		UPDATE student_color
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;
		
		-- 10. Table: student_discipline
		UPDATE student_discipline
		SET
			dis_studentid = new_id
		WHERE
			dis_studentid = original_id;

		-- 11. Table: student_disciplinestatus
		UPDATE student_disciplinestatus
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;

		-- 12. Table: student_drug
		UPDATE student_drug
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;

		-- 13. Table: student_investigation
		UPDATE student_investigation
		SET
			student_id = new_id
		WHERE
			student_id = original_id;
				
		-- 14. Table: student_learn
		UPDATE student_learn
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;

		-- 15. Table: student_moral
		UPDATE student_moral
		SET
			student_id = new_id
		WHERE
			student_id = original_id and acadyear = original_xedbe;

	ELSE
		select count_original_id,count_new_id,is_update_available,message;
    END IF;
/*
	Stored procedure: update_student_id
	เอาไว้ใช้สำหรับการแก้ไข เลขประจำตัวนักเรียน ในระบบ
	การใช้งานให้ส่่ง parameters ดังนี้
	1. ปีการศึกษาที่ต้องการจะแก้ไข
	2. ระดับการศึกษาของนักเรียน ม.ต้น = 3, ม.ปลาย = 4
	3. ชั้นปีของระดับการศึกษา เช่น ม.1=1, ม.2=2, ม.3=3, ม.4=1, ม.5=2, ม.6=3
	4. เลขประจำนักเรียนค่าเดิม
	5. เลขประจำตัวนักเรียนค่าใหม่

	ตัวอย่าง - ถ้าต้องการจะแก้ไขเลขประจำตัวนักเรียน ปีการศึกษา 2565 ของนักเรียนชั้นมัธยมศึกษาปีที่ 6 จากเลขประจำตัว 05001 เป็น 08759 สามารถเรียกคำสั่งได้ดังต่อไปนี้

	call update_student_id('2565','4','3','05001','08759');


*/


END //
DELIMITER ;

