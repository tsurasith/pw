-- TRIGGER to handle trigger_learn_tables_insert
 
DROP TRIGGER IF EXISTS trigger_teaching_schedule_insert;
 
DELIMITER $$
 
CREATE TRIGGER trigger_teaching_schedule_insert
   AFTER INSERT
   ON `teaching_schedule` FOR EACH ROW
BEGIN
 
   DECLARE row_count INT;
   DECLARE day_name VARCHAR(20);
 
   SELECT COUNT(*)
   INTO row_count
   FROM
       `teaching_schedule_display`
   WHERE
       `teacher_id` = NEW.`teacher_id` and 
       `weekday` = NEW.`weekday` and 
       `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester`;
 

  
   IF(NEW.weekday = 1) THEN
       SET day_name = 'monday';
   END IF;
   IF(NEW.weekday = 2) THEN
       SET day_name = 'tuesday';
   END IF;
   IF(NEW.weekday = 3) THEN
       SET day_name = 'wednesday';
   END IF;
   IF(NEW.weekday = 4) THEN
       SET day_name = 'thursday';
   END IF;
   IF(NEW.weekday = 5) THEN
       SET day_name = 'friday';
   END IF;
 
 
   IF (row_count = 0) THEN
       INSERT INTO `teaching_schedule_display`(
           `day_name`,
           `weekday`,
           `acadyear`,
           `acadsemester`,
           `teacher_id`
       )
       VALUES(
           day_name,
           NEW.weekday,
           NEW.acadyear,
           NEW.acadsemester,
           NEW.teacher_id
       );
   END IF;
 
   IF (NEW.`period` = '1') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period1`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
 
   IF (NEW.`period` = '2') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period2`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
 
 IF (NEW.`period` = '3') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period3`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
 
  IF (NEW.`period` = '4') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period4`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
 
  IF (NEW.`period` = '5') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period5`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
   
    IF (NEW.`period` = '6') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period6`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
   
    IF (NEW.`period` = '7') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period7`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
   
   
       IF (NEW.`period` = '8') THEN
       UPDATE
       `teaching_schedule_display`
       SET
           `period8`       = concat(NEW.SubjectCode,'|',NEW.level,'/',NEW.room,'|',NEW.location,' ')
       WHERE
           `teacher_id` = NEW.`teacher_id` and `weekday` = NEW.`weekday` and
           `acadyear` = NEW.`acadyear` and `acadsemester` = NEW.`acadsemester` ;
   END IF;
   
END$$   
 
DELIMITER ;
 
 
 
