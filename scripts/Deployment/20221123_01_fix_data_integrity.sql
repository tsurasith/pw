
delete from student_800
where
    student_id not in (select id from students)
;


--
 fix missing student_800

INSERT INTO `student_800`
   	 (`row_id`, `student_id`, `class_id`, `timecheck_id`, `check_date`, `create_date`, `acadyear`, `acadsemester`, `user_create`)
VALUES
   	 ('2022-11-1630434','09198','304','00','2022-11-16','2022-11-16','2565','2','นางสิริกร บุญเผือก');
   	 
INSERT INTO `student_800`
   	 (`row_id`, `student_id`, `class_id`, `timecheck_id`, `check_date`, `create_date`, `acadyear`, `acadsemester`, `user_create`)
VALUES
   	 ('2022-11-1630434','09199','304','00','2022-11-16','2022-11-16','2565','2','นางสิริกร บุญเผือก');


INSERT INTO `student_800`
   	 (`row_id`, `student_id`, `class_id`, `timecheck_id`, `check_date`, `create_date`, `acadyear`, `acadsemester`, `user_create`)
VALUES
   	 ('2022-11-1730435','09198','304','00','2022-11-17','2022-11-17','2565','2','นางสิริกร บุญเผือก');
   	 
INSERT INTO `student_800`
   	 (`row_id`, `student_id`, `class_id`, `timecheck_id`, `check_date`, `create_date`, `acadyear`, `acadsemester`, `user_create`)
VALUES
   	 ('2022-11-1730436','09199','304','00','2022-11-17','2022-11-17','2565','2','นางสิริกร บุญเผือก');

