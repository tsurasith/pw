
-- delete students from acadyear = 2566
/*

09372 กอย่า ไทยอ่อน 4|3
08717 พรประภา จอมปวง 4|3
08726 รดามณี บุตะเขียว 4|4
08806 จตุพล สงนอก 4|4 

*/

        
        -- 1. Table: students
        DELETE FROM students
		WHERE
			ID = '08806' and xedbe = '2566';
		
        -- 2. Table: grades
        DELETE FROM learn_grades
		WHERE
			StudentCode = '08806' and RegisterYear like '%2566';

		-- 3. Table: register_students
		DELETE FROM register_students
		WHERE 
			student_id = '08806'and acadyear = '2566';
		
		-- 4. Table: register_students
		DELETE FROM register_students_grades_import
		WHERE 
			student_id = '08806' and acadyear = '2566';

		-- 10. Table: student_800
		DELETE FROM student_800
		WHERE
			student_id = '08806' and acadyear = '2566';
		
		-- 11. Table: student_color
		DELETE FROM student_color
		WHERE
			student_id = '08806' and acadyear = '2566';
		
		-- 12. Table: student_discipline
		DELETE FROM student_discipline
		WHERE
			dis_studentid = '08806' and dis_informdate > '2023-04-30';

		-- 13. Table: student_disciplinestatus
		DELETE FROM student_disciplinestatus
		WHERE
			student_id = '08806' and acadyear = '2566';

		-- 14. Table: student_drug
		DELETE FROM student_drug
		WHERE
			student_id = '08806' and acadyear = '2566';

		-- 15. Table: student_investigation
		DELETE FROM student_investigation
		WHERE
			student_id = '08806' and dis_investdate > '2023-04-30';
				
		-- 16. Table: student_learn
		DELETE FROM student_learn
		WHERE
			student_id = '08806' and acadyear = '2566';

		-- 17. Table: student_moral
		DELETE FROM student_moral
		WHERE
			student_id = '08806' and acadyear = '2566';

		-- 18. Table: student_point_history
		DELETE FROM student_point_history
		WHERE
			student_id = '08806' and acadyear = '2566';


--- the 2nd stduent --
       -- 1. Table: students
        DELETE FROM students
		WHERE
			ID = '08726' and xedbe = '2566';
		
        -- 2. Table: grades
        DELETE FROM learn_grades
		WHERE
			StudentCode = '08726' and RegisterYear like '%2566';

		-- 3. Table: register_students
		DELETE FROM register_students
		WHERE 
			student_id = '08726'and acadyear = '2566';
		
		-- 4. Table: register_students
		DELETE FROM register_students_grades_import
		WHERE 
			student_id = '08726' and acadyear = '2566';

		-- 10. Table: student_800
		DELETE FROM student_800
		WHERE
			student_id = '08726' and acadyear = '2566';
		
		-- 11. Table: student_color
		DELETE FROM student_color
		WHERE
			student_id = '08726' and acadyear = '2566';
		
		-- 12. Table: student_discipline
		DELETE FROM student_discipline
		WHERE
			dis_studentid = '08726' and dis_informdate > '2023-04-30';

		-- 13. Table: student_disciplinestatus
		DELETE FROM student_disciplinestatus
		WHERE
			student_id = '08726' and acadyear = '2566';

		-- 14. Table: student_drug
		DELETE FROM student_drug
		WHERE
			student_id = '08726' and acadyear = '2566';

		-- 15. Table: student_investigation
		DELETE FROM student_investigation
		WHERE
			student_id = '08726' and dis_investdate > '2023-04-30';
				
		-- 16. Table: student_learn
		DELETE FROM student_learn
		WHERE
			student_id = '08726' and acadyear = '2566';

		-- 17. Table: student_moral
		DELETE FROM student_moral
		WHERE
			student_id = '08726' and acadyear = '2566';

		-- 18. Table: student_point_history
		DELETE FROM student_point_history
		WHERE
			student_id = '08726' and acadyear = '2566';

-- the 3rd student --
        -- 1. Table: students
        DELETE FROM students
		WHERE
			ID = '08717' and xedbe = '2566';
		
        -- 2. Table: grades
        DELETE FROM learn_grades
		WHERE
			StudentCode = '08717' and RegisterYear like '%2566';

		-- 3. Table: register_students
		DELETE FROM register_students
		WHERE 
			student_id = '08717'and acadyear = '2566';
		
		-- 4. Table: register_students
		DELETE FROM register_students_grades_import
		WHERE 
			student_id = '08717' and acadyear = '2566';

		-- 10. Table: student_800
		DELETE FROM student_800
		WHERE
			student_id = '08717' and acadyear = '2566';
		
		-- 11. Table: student_color
		DELETE FROM student_color
		WHERE
			student_id = '08717' and acadyear = '2566';
		
		-- 12. Table: student_discipline
		DELETE FROM student_discipline
		WHERE
			dis_studentid = '08717' and dis_informdate > '2023-04-30';

		-- 13. Table: student_disciplinestatus
		DELETE FROM student_disciplinestatus
		WHERE
			student_id = '08717' and acadyear = '2566';

		-- 14. Table: student_drug
		DELETE FROM student_drug
		WHERE
			student_id = '08717' and acadyear = '2566';

		-- 15. Table: student_investigation
		DELETE FROM student_investigation
		WHERE
			student_id = '08717' and dis_investdate > '2023-04-30';
				
		-- 16. Table: student_learn
		DELETE FROM student_learn
		WHERE
			student_id = '08717' and acadyear = '2566';

		-- 17. Table: student_moral
		DELETE FROM student_moral
		WHERE
			student_id = '08717' and acadyear = '2566';

		-- 18. Table: student_point_history
		DELETE FROM student_point_history
		WHERE
			student_id = '08717' and acadyear = '2566';


-- the 4th student --
        
        -- 1. Table: students
        DELETE FROM students
		WHERE
			ID = '09372' and xedbe = '2566';
		
        -- 2. Table: grades
        DELETE FROM learn_grades
		WHERE
			StudentCode = '09372' and RegisterYear like '%2566';

		-- 3. Table: register_students
		DELETE FROM register_students
		WHERE 
			student_id = '09372'and acadyear = '2566';
		
		-- 4. Table: register_students
		DELETE FROM register_students_grades_import
		WHERE 
			student_id = '09372' and acadyear = '2566';

		-- 10. Table: student_800
		DELETE FROM student_800
		WHERE
			student_id = '09372' and acadyear = '2566';
		
		-- 11. Table: student_color
		DELETE FROM student_color
		WHERE
			student_id = '09372' and acadyear = '2566';
		
		-- 12. Table: student_discipline
		DELETE FROM student_discipline
		WHERE
			dis_studentid = '09372' and dis_informdate > '2023-04-30';

		-- 13. Table: student_disciplinestatus
		DELETE FROM student_disciplinestatus
		WHERE
			student_id = '09372' and acadyear = '2566';

		-- 14. Table: student_drug
		DELETE FROM student_drug
		WHERE
			student_id = '09372' and acadyear = '2566';

		-- 15. Table: student_investigation
		DELETE FROM student_investigation
		WHERE
			student_id = '09372' and dis_investdate > '2023-04-30';
				
		-- 16. Table: student_learn
		DELETE FROM student_learn
		WHERE
			student_id = '09372' and acadyear = '2566';

		-- 17. Table: student_moral
		DELETE FROM student_moral
		WHERE
			student_id = '09372' and acadyear = '2566';

		-- 18. Table: student_point_history
		DELETE FROM student_point_history
		WHERE
			student_id = '09372' and acadyear = '2566';