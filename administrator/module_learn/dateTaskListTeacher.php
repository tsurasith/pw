
<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>2.1 บันทึกการเข้าเรียน (เฉพาะรายวิขาที่สอน)</strong></font></span></td>
      <td align="right">
	  <? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

			//==============$_POST value===========//
			if(isset($_POST['acadyearX'])) { $acadyear = $_POST['acadyearX']; }
			if(isset($_POST['acadsemesterX'])) { $acadsemester = $_POST['acadsemesterX']; }


			$_date = "";
			$_date = isset($_POST['date'])?$_POST['date']:"";

			if($_date == ""){
				$_date = isset($_REQUEST['date'])?$_REQUEST['date']:"";
			}

			$_date = "";
			$_date = isset($_POST['date'])?$_POST['date']:"";
	
			$_teacher_id = "";
			$_teacher_id = isset($_POST['teacher_id'])?$_POST['teacher_id']:"";

		?>
        ปีการศึกษา
        <?php  
					echo "<a href=\"index.php?option=module_learn/dateTaskListTeacher&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/dateTaskListTeacher&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/dateTaskListTeacher&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/dateTaskListTeacher&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				
		
           <font color="#000000" size="2">
           		<form name="sSelect" method="post" action="index.php?option=module_learn/dateTaskListTeacher">
				   <? if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc100" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") { ?>
				   <font  size="2" color="#000000">เลือกครูผู้สอน
					<?php 
							$sql_teacher = " 
								select 
									u.user_account_prefix,
									u.user_account_firstname,
									u.user_account_lastname,
									u.user_account_id,
									count(t.teacher_id) as c
								from 
									users_account u 
									left join teaching_schedule t 
									on (u.user_account_id = t.teacher_id and t.acadyear = '" . $acadyear ."' and t.acadsemester = '" . $acadsemester ."')
								group by 
									u.user_account_id,
									u.user_account_prefix,
									u.user_account_firstname,
									u.user_account_lastname
								order by convert(u.user_account_firstname using tis620) ";
							//echo $sql_Room ;
							$resTeacher = mysqli_query($_connection,$sql_teacher);	
							$_submit_teacher_name = "";		
					?>
				
					<select name="teacher_id" class="inputboxUpdate" onChange="setTeacherName(this)">
						<option value=""></option>
						<?php
							$_select = "";
							while($dat = mysqli_fetch_assoc($resTeacher))
							{
								$_select = (isset($_POST['teacher_id'])&&$_POST['teacher_id'] == $dat['user_account_id']?"selected":"");
								echo "<option value=\"" . $dat['user_account_id'] . "\" $_select>";
								echo $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $dat['c'] . ')';
								echo "</option>";
							}
							
						?>
					</select><br/>
					<? } else { ?> 
						<input type="hidden" name="teacher_id" value=<?=$_SESSION['user_account_id']?> />
					<? } ?>
					<input type="hidden" value="" name="teacher_name" />
					เลือกวันที่ 
						<select name="date" class="inputboxUpdate" >
						<option value=""></option>
					<?php
					$sql_date = "select distinct task_date,weekday from student_learn_task where task_status = '0' and acadyear = '" .$acadyear ."' and acadsemester = '" .$acadsemester."' order by task_date " ;
					$result = mysqli_query($_connection,$sql_date);
					while($data = mysqli_fetch_assoc($result)) { ?>
						<option value="<?=$data['task_date']?>" <?=$_date==$data['task_date']?"selected":""?>>
							<?=displayFullDate($data['task_date'])?> (<?=displayDayofWeek($data['weekday'])?>)
						</option>
					<? }  ?>
						</select>
						<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
						<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
						<input type="hidden" name="search" value="search"> <br/>
						<input type="submit" name="submit" value="เรียกดู" class="button">
                </form>
           </font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if(isset($_POST['submit']) && $_POST['teacher_id'] != ""){ ?>


	<?

		$_totalRows = 0;
		$_sql_learntable = "

				SELECT DISTINCT
					t.teacher_id,
					t.club_code as SubjectCode,
					(
						select cc.club_name from curriculum_clubs cc
						where
							cc.club_code = t.club_code
					) as location,
					l.task_date,
					l.period,
					'000' AS task_roomid,
					l.acadyear,
					l.acadsemester,
					l.weekday,
					(
					SELECT
						EXISTS( 
							SELECT * FROM student_learn c
							WHERE
								c.check_date  = l.task_date AND 
								c.period      = t.period AND
								c.club_code   = t.club_code AND
								c.teacher_id  = '" . $_teacher_id . "' 
					)
				) AS task_status,
				'master' AS 'class_type'
				FROM
					teaching_schedule t
				LEFT JOIN student_learn_task l ON
					(
						l.acadyear = t.acadyear AND l.acadsemester = t.acadsemester AND l.weekday = t.weekday AND l.period = t.period AND t.room_id LIKE '%00' AND LEFT(l.task_roomid, 1) = t.level AND t.teacher_id IS NOT NULL
					)
				WHERE
					l.task_date   = '" . $_date . "' and
					t.teacher_id  = '" . $_teacher_id . "' and 
					t.club_code  != '0000'
	
				union

				select distinct
						t.teacher_id,
						t.SubjectCode,
						t.location,
						l.task_date,
						l.period,
						if(right(t.room_id,2)='00' , t.room_id,l.task_roomid) as task_roomid,
						l.acadyear,
						l.acadsemester,
						l.weekday,
						(
							select exists 
							(
								select * from student_learn c
								where
									c.check_date   = l.task_date and
									c.SubjectCode  = t.SubjectCode and
									c.period       = t.period
							)
						) as task_status,
						'master' as 'class_type'
				from 
					teaching_schedule t left join student_learn_task l 
					on (
							l.acadyear    = t.acadyear   and l.acadsemester = t.acadsemester and
							l.weekday     = t.weekday    and l.period = t.period and 
							t.room_id     like '%00'     and
							left(l.task_roomid,1) = t.level and 
							t.teacher_id is not null
						)
				where
					l.task_date  = '" . $_date . "' and
					t.teacher_id = '" . $_teacher_id . "' and 
					t.club_code  = '0000'
				
				UNION

				select 
						t.teacher_id,
						t.SubjectCode,
						t.location,
						l.task_date,
						l.period,
						l.task_roomid,
						l.acadyear,
						l.acadsemester,
						l.weekday,
						l.task_status,
						'master' as 'class_type'
				from 
					student_learn_task l left join teaching_schedule t 
					on (
							l.acadyear = t.acadyear and l.acadsemester = t.acadsemester and
							l.weekday = t.weekday and l.period = t.period and 
							l.task_roomid = t.room_id and t.teacher_id is not null
						)
				where
					l.task_date = '" . $_date . "' and
					t.teacher_id = '" . $_teacher_id . "' 
				UNION

				SELECT
					s.teacher_id,
					s.SubjectCode,
					s.location,
					s.teaching_date,
					s.period,
					s.room_id,
					s.acadyear,
					s.acadsemester,
					s.weekday,
					l.task_status,
					'substitute' AS 'class_type'
				FROM
					teaching_substitute s inner join student_learn_task l
					on 
					(
						l.acadyear = s.acadyear AND 
						l.acadsemester = s.acadsemester AND 
						l.weekday = s.weekday AND 
						l.period = s.period AND 
						l.task_roomid = s.room_id AND 
						l.task_date = s.teaching_date and 
						s.teacher_id IS NOT NULL
					)
				WHERE 
					s.teaching_date = '" . $_date . "' and s.teacher_id = '" . $_teacher_id . "' 
				
				UNION

				SELECT
					s.teacher_id,
					s.SubjectCode,
					s.location,
					s.teaching_date,
					s.period,
					s.room_id,
					s.acadyear,
					s.acadsemester,
					s.weekday,
					(
						select exists 
						(
							select * from student_learn c
							where
								c.check_date   = '" . $_date . "' and 
								c.SubjectCode  = s.SubjectCode and
								c.period       = s.period
						)
					) as task_status,
					'substitute' AS 'class_type'
				FROM
					teaching_substitute s 
				WHERE 
					s.teaching_date = '" . $_date . "' and s.teacher_id = '" . $_teacher_id . "' and
					s.room_id    like '%00'

				order by
					period


				" ;
		//echo $_sql_learntable . "<br/>";
		
		$_resTeaching = mysqli_query($_connection,$_sql_learntable);
		$_totalRows = mysqli_num_rows($_resTeaching);

		$_teaching = array(array());
		
		if($_totalRows>0){
			while($_x = mysqli_fetch_assoc($_resTeaching)){
				$_teaching[$_x['period']] = $_x;
			}
		}
		//print_r($_teaching);
		?>
	<div align="center">
	<table class="admintable" align="center">
		<tr>
			<th colspan="10" align="center">
				<img src="../images/school_logo.png" width="120px"> <br/>
				แสดงรายวิชาที่ต้องบันทึกการเข้าเรียนของนักเรียน<br/>
				ของคุณครู <?=getUserAccountName($_connection,$_POST['teacher_id'])?> <br/>
				วัน <?=displayDayOfWeek(date('w',strtotime($_date)))?> 
				ที่ <?=isset($_date)&&$_date!=""?displayFullDate($_date):""?> <br/>
			</th>
		</tr>
		<tr height="35px"> 
			<? $_width = "95px"; ?>
			<td class="key" width="80px" align="center">วัน/คาบ</td>
			<td class="key" width="<?=$_width?>" align="center">1</td>
			<td class="key" width="<?=$_width?>" align="center">2</td>
			<td class="key" width="<?=$_width?>" align="center">3</td>
			<td class="key" width="<?=$_width?>" align="center">4</td>
			<td class="key" width="<?=$_width?>" align="center">พักเที่ยง</td>
			<td class="key" width="<?=$_width?>" align="center">5</td>
			<td class="key" width="<?=$_width?>" align="center">6</td>
			<td class="key" width="<?=$_width?>" align="center">7</td>
			<td class="key" width="<?=$_width?>" align="center">8</td>
		</tr>
    <?php

		if($_totalRows>0){

			echo "<tr>";
			echo "<td align='center' class='key'>". displayDayofWeek(date('w',strtotime($_POST['date']))) . "</td>";

			for($_j=1;$_j<=8;$_j++){

				echo "<td align='center' valign='top'>";

				if(!empty($_teaching[$_j])){
					if($_teaching[$_j]['task_status']!="1"){
							
						if($_teaching[$_j]['task_roomid'] == "000"){
							echo "<a href='index.php?option=module_learn/studentListFormSinglePeriodClub&room=" .$_teaching[$_j]['task_roomid'] . "&date=" .$_teaching[$_j]['task_date'] . "&teacher_id=" . $_teaching[$_j]['teacher_id'] .
							"&period=" . $_teaching[$_j]['period'] . 
							"&acadyear=" . $_teaching[$_j]['acadyear'] . "&acadsemester=" . $_teaching[$_j]['acadsemester'] . 
							"&class_type=" . $_teaching[$_j]['class_type'] . 
							"&subject=" . $_teaching[$_j]['SubjectCode'] . "'>";
							echo "<b>" . $_teaching[$_j]['SubjectCode'] . "</b><br/>";
							echo "</a>";
						} else if(substr($_teaching[$_j]['task_roomid'],-2) == "00"){
							echo "<a href='index.php?option=module_learn/studentListFormSinglePeriodSBM&room=" .$_teaching[$_j]['task_roomid'] . "&date=" .$_teaching[$_j]['task_date'] . "&teacher_id=" . $_teaching[$_j]['teacher_id'] .
								"&period=" . $_teaching[$_j]['period'] . 
								"&acadyear=" . $_teaching[$_j]['acadyear'] . "&acadsemester=" . $_teaching[$_j]['acadsemester'] . 
								"&class_type=" . $_teaching[$_j]['class_type'] . 
								"&subject=" . $_teaching[$_j]['SubjectCode'] . "'>";
							echo "<b>" . $_teaching[$_j]['SubjectCode'] . "</b><br/>";
							echo "</a>";
						}else{
							echo "<a href='index.php?option=module_learn/studentListFormSinglePeriod&room=" .$_teaching[$_j]['task_roomid'] . "&date=" .$_teaching[$_j]['task_date'] . "&teacher_id=" . $_teaching[$_j]['teacher_id'] .
								"&period=" . $_teaching[$_j]['period'] . 
								"&acadyear=" . $_teaching[$_j]['acadyear'] . "&acadsemester=" . $_teaching[$_j]['acadsemester'] . 
								"&class_type=" . $_teaching[$_j]['class_type'] . 
								"&subject=" . $_teaching[$_j]['SubjectCode'] . "'>";
							echo "<b>" . $_teaching[$_j]['SubjectCode'] . "</b><br/>";
							echo "</a>";
						}
						
					}else{
						echo "<b>" . $_teaching[$_j]['SubjectCode'] . "</b><br/>";
					}
					
					//แสดงห้อง
					if($_teaching[$_j]['task_roomid']!="000"){
						echo getFullRoomFormat($_teaching[$_j]['task_roomid'])."<br/>";
					}
					

					//แสดงสถานที่สอน ถ้าเป็นชุมนุมจะแสดงเป็นชื่อชุมนุม
					echo $_teaching[$_j]['location'];
					if($_teaching[$_j]['class_type']=="substitute"){
						echo "<br/>(สอนแทน)";
						$_link = checkExistingSubstituteTeachingRecord($_connection,$_teaching[$_j]['SubjectCode'],$_teaching[$_j]['task_date'],$_teaching[$_j]['teacher_id'],$_teaching[$_j]['period'],$_teaching[$_j]['task_roomid']);
						echo "<br/><br/>";
						echo "<a $_link href='index.php?option=module_learn/substituteTeachingRecord&room=" .$_teaching[$_j]['task_roomid'] . "&date=" .$_teaching[$_j]['task_date'] . "&teacher_id=" . $_teaching[$_j]['teacher_id'] .
								"&period="     . $_teaching[$_j]['period'] . 
								"&acadyear="   . $_teaching[$_j]['acadyear'] . "&acadsemester=" . $_teaching[$_j]['acadsemester'] . 
								"&class_type=" . $_teaching[$_j]['class_type'] . 
								"&subject="    . $_teaching[$_j]['SubjectCode'] . "'>";
						echo "บันทึก<br/>การสอน";
						echo "</a>";
					}else{
						echo "<br/>";
						$_link = checkExistingTeachingRecord($_connection,$_teaching[$_j]['SubjectCode'],$_teaching[$_j]['task_date'],$_teaching[$_j]['teacher_id'],$_teaching[$_j]['period'],$_teaching[$_j]['task_roomid']);
						echo "<br/><br/>" ;
						echo "<a $_link href='index.php?option=module_learn/masterTeachingRecord&room=" .$_teaching[$_j]['task_roomid'] . "&date=" .$_teaching[$_j]['task_date'] . "&teacher_id=" . $_teaching[$_j]['teacher_id'] .
								"&period="     . $_teaching[$_j]['period'] . 
								"&acadyear="   . $_teaching[$_j]['acadyear'] . "&acadsemester=" . $_teaching[$_j]['acadsemester'] . 
								"&class_type=" . $_teaching[$_j]['class_type'] . 
								"&subject="    . $_teaching[$_j]['SubjectCode'] . "'>";
						echo "บันทึก<br/>การสอน";
						echo "</a>";

					}
				} else {
					echo " - ";
				}

				echo "</td>";

				if($_j==4){
					echo "<td align='center' valign='top'>-</td>";
				}
			}
		}//end if
	?>
	<tr>
		<td colspan="10">
			<br/>
			<b><u>คำแนะนำ</b></u><br/>
			1. หากต้องการบันทึกการเข้าเรียนของนักเรียน (เช็คชื่อนักเรียน) ให้คลิกที่ <b>รหัสวิขา</b><br/>
			2. หากต้องการบันทึกการสอน/บันทึกการสอนแทน ให้คลิกที่ <b>บันทึกการสอน</b>
		</td>
	</tr>
		 </table>

	</div>
	<? } ?>
</div>

