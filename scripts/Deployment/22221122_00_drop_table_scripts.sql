
-- no longer to use
DROP TABLE grades;

-- no longer to use
DROP TABLE subjects;


-- fix missing primary kyes - this is DONE on production
ALTER TABLE `ref_studjudge` ADD PRIMARY KEY(`judge_id`); 


DELETE FROM `teachers_800` WHERE roow_id = '2022-11-29-203';

INSERT INTO `teachers_800`(
    `row_id`,
    `room_id`,
    `stutus`,
    `check_date`,
    `acadyear`,
    `acadsemester`
)	 	 	 	 	
VALUES(
    '2022-11-29-203',
    '203',
    'sign',
    '2022-11-29',
    '2565',
    '2'
);


ALTER TABLE `teachers_800` ADD PRIMARY KEY(`row_id`); 

ALTER TABLE `teachers_learn` ADD PRIMARY KEY(`row_id`); 

