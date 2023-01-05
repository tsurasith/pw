
-- 01. เพิ่มคอลัมน์สำหรับเก็บระดับชั้นใน table: mapping
ALTER TABLE `curriculum_subject_mappings` 
ADD `curriculum_mapping_level` VARCHAR(6) NOT NULL COMMENT 'ระดับชั้นที่ใช้สอนในแผนการเรียน (defualt ม.2 ในแผนอาจจะใช้สอน ม.3)' 
AFTER `SubjectCode`;


-- 02. update data into new data columns
UPDATE
    `curriculum_subject_mappings` m
INNER JOIN curriculum_subjects s ON
    (m.SubjectCode = s.SubjectCode)
SET
    m.curriculum_mapping_level = s.SubjectLevel;


