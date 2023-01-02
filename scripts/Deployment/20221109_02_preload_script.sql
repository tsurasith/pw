
-- add missing rooms
delete from rooms where acadyear = 2565 and acadsemester = 2;

insert into rooms(room_id,teacher_id,teacher_id2,acadsemester,acadyear)
select 
		concat((xlevel%3)*3+xyearth,if(room>=10,room,concat('0',room))),
        '','',
        2,2565
from students
	where
		xedbe = 2565
	group by xlevel,xyearth,room;


-- update bmi after import students data
update students
set
	bmi = WEIGHT/((height/100)*(height/100))
where
	height > 0;

