/*

    Stored procedure: remove_student_learn_date
	เอาไว้ใช้สำหรับการแก้ไข ลบวันที่สร้างงานบันทึกมาแล้วมีการบันทึกข้อมูลไปแล้วบางคาบเรียน แล้วไม่สามารถทำให้ลบ วันที่สร้างงานขึ้นมาได้
	ดังนั้นจึงสร้าง stored procedure นี้มาเพื่อ ลบ ข้อมูลวันที่สร้างงานบันทึกผิด โดยจะลบข้อมูลที่เกี่ยวข้องออกทั้งหมด
    
    ตัวอย่างคำสั่ง หากต้องการลบข้อมูลวันที่ 16 มกราคม 2023
    call remove_student_learn_date('2023-01-16');

*/




DROP PROCEDURE IF EXISTS `remove_student_learn_date`;



DELIMITER //
CREATE PROCEDURE `remove_student_learn_date` (
                                        IN removing_date varchar(10)
                                      )
BEGIN


       
    DECLARE processing_message VARCHAR(255) DEFAULT 'Processing completed.';
   
    DELETE FROM teaching_substitute WHERE teaching_date = removing_date;


    DELETE FROM teachers_learn  WHERE check_date    = removing_date;
    DELETE FROM teaching_record WHERE teaching_date = removing_date;


    DELETE FROM student_learn   WHERE check_date   = removing_date;
    DELETE FROM student_learn_task WHERE task_date = removing_date;    


    DELETE FROM student_discipline
    WHERE
        dis_date = removing_date AND
        dis_studentid IN
        (
            select distinct student_id FROM student_learn
            WHERE
                check_date = removing_date
                AND timecheck_id not IN ('00')
        )
    ;


    DELETE FROM student_disciplinestatus
    WHERE
        dis_id IN
        (
            select distinct dis_id FROM student_discipline
            WHERE
                dis_date = removing_date AND
                student_id in
                (
                    select distinct student_id FROM student_learn
                    WHERE
                        check_date = removing_date
                        AND timecheck_id not IN ('00')
                )
        );    
   
    SELECT processing_message;

    /*

        Stored procedure: remove_student_learn_date
        เอาไว้ใช้สำหรับการแก้ไข ลบวันที่สร้างงานบันทึกมาแล้วมีการบันทึกข้อมูลไปแล้วบางคาบเรียน แล้วไม่สามารถทำให้ลบ วันที่สร้างงานขึ้นมาได้
        ดังนั้นจึงสร้าง stored procedure นี้มาเพื่อ ลบ ข้อมูลวันที่สร้างงานบันทึกผิด โดยจะลบข้อมูลที่เกี่ยวข้องออกทั้งหมด
        
        ตัวอย่างคำสั่ง หากต้องการลบข้อมูลวันที่ 16 มกราคม 2023
        call remove_student_learn_date('2023-01-16');

    */

END //
DELIMITER ;


