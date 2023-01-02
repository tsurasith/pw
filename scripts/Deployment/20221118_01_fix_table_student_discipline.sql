
-- This table is missing an automatic incremental of dis_id

ALTER TABLE `student_discipline` CHANGE `dis_id` `dis_id` INT(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `student_disciplinestatus` CHANGE `dis_id` `dis_id` INT(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `student_sanction` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);


ALTER TABLE `student_drug_task` CHANGE `task_id` `task_id` INT(11) NOT NULL AUTO_INCREMENT;


