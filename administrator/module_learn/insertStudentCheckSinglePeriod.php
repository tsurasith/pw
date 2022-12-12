<?php
/* echo $_SESSION['name'] . "<br/>";
echo $_POST['room_id'] . "<br/>";
echo date('Y-m-d') . "<br/>";
echo $_POST['date'] . "<br/>"; */

$_student_learn_proccesing = false;
$_student_learn_task_processing = false;
$_teacher_learn_processing = false;

$_student_disappear = "รายชื่อนักเรียนที่หนีเรียน \n";

if(isset($_POST['save']) && $_POST['date'] != ""){

	$_student_disappear .= " รหัสวิชา " . $_POST['subject_code'] . " คาบ " . $_POST['period'] . " วันที่ " . displayDateChart($_POST['date']). "";
	for($i =0 ;$i < $_POST['count'] ;$i++)
	{
		$row_id = '';
		if($i < 10) { 
			$row_id = $_POST['date'] . '-' . $_POST['room_id'] . '-' . $_POST['period'] . '-' . '0' .$i; 
		}
		else { 
			$row_id = $_POST['date'] . '-' . $_POST['room_id'] . '-' . $_POST['period'] . '-' .$i; 
		}

		$sql_insert_student = "
				INSERT INTO `student_learn`(
					`row_id`,
					`student_id`,
					`class_id`,
					`period`,
					`timecheck_id`,
					`check_date`,
					`acadyear`,
					`acadsemester`,
					`weekday`,
					`created_datetime`,
					`created_user`,
					`teacher_id`,
					`SubjectCode`,
					`create_date`,
					`user_create`
				)
				VALUES (
						'" . $row_id . "', 
						'" . $_POST['student_id'][$i]  . "', 
						'" . $_POST['room_id'] . "', 
						'" . $_POST['period'] . "' , 
						'" . timecheck_id($_POST['check'][$i]) ."', 
						'". $_POST['date'] . "',
						'" . $_POST['acadyear'] . "', 
						'" . $_POST['acadsemester'] . "', 
						'" . date('w',strtotime($_POST['date']))  . "',
						CURRENT_TIMESTAMP,
						'" . $_SESSION['user_account_id'] . "',
						'" . $_POST['teacher_id'] . "',
						'" . $_POST['subject_code'] . "',
						'" . date('Y-m-d') . "',
						'" . $_SESSION['name'] . "' 
				);"; 
		// บันทึกข้อมูลการเช็คz
		$a = mysqli_query($_connection,$sql_insert_student) or die ('ผิดพลาดเนื่องจาก table: student_learn - ' . mysqli_error($_connection));
		$_student_learn_proccesing = $a;

		// ส่งข้อความหนีเรียน
		if(timecheck_id($_POST['check'][$i]) == "05"){
			$_student_disappear .= "\n";
			$_student_disappear .= $_POST['student_id'][$i] . " ";
			$_student_disappear .= $_POST['student_name'][$i] . "";
		}
		// บันทึกงานวินัยถ้าหนีเรียน
		if(timecheck_id($_POST['check'][$i]) == "05"){
		{
			
			$_discipline_details = "หนีเรียน รหัสวิชา " . $_POST['subject_code'] . " คาบ " . $_POST['period'] . " วันที่ " . displayDateChart($_POST['date']) ;
			
			$_dis_id = getDisciplineID($_connection);

			$sql = "
				INSERT INTO `student_discipline`(
					`dis_id`,
					`dis_studentid`,
					`dis_date`,
					`dis_time`,
					`dis_village`,
					`dis_tumbol`,
					`dis_inform`,
					`dis_informdate`,
					`dis_informgroup`,
					`dis_reciever`,
					`dis_recievedate`,
					`dis_recievedetail`,
					`dis_detail`,
					`dis_user`
				) 
				VALUES 
					(
						'" . $_dis_id . "',
						'" . $_POST['student_id'][$i] . "',
						'" . $_POST['date'] . "',
						LEFT(current_time,5),
						' ',
						' ',
						'". $_SESSION['name'] . "',
						curdate(),
						'ครู',
						'" . $_SESSION['name'] . "',
						'" . $_POST['date'] . "',
						'เห็นควรมอบให้ฝ่ายกิจการนักเรียนดำเนินการสอบสวน',
						'" . $_discipline_details . "',
						'" . $_SESSION['name'] . "'
					)";
			
			$sql_status = "
				INSERT INTO `student_disciplinestatus`(
					`student_id`,
					`dis_id`,
					`dis_status`,
					`sanc_status`,
					`point`,
					`acadyear`,
					`acadsemester`
				)
				VALUES(
						'" . $_POST['student_id'][$i]  . "',
						'" . $_dis_id . "',
						'1',
						'00',
						'0',
						'" . $acadyear . "',
						'" . $acadsemester . "'
					)";

			mysqli_query($_connection,$sql) or die ('error table:student_discipline - '. mysqli_error($_connection));
			mysqli_query($_connection,$sql_status);
		}

	} // end for-loop


	$sql_insert_teacher = "
			INSERT INTO  teachers_learn 
			VALUES (
					'" . $_POST['date'] . '-' . $_POST['room_id'] . '-' . $_POST['period'] ."',
					'" . $_POST['room_id'] . "',
					'" . $_POST['period'] . "' , 
					'" . $_POST['teacherSign'] ."' , 
					'" . $_POST['date'] ."',
					'" . $_POST['acadyear'] . "', 
					'" . $_POST['acadsemester'] . "' 
				)";
	// บันทึกการเข้าใช้งานของครู
	$b = mysqli_query($_connection,$sql_insert_teacher) or die ('ผิดพลาดเนื่องจาก table: teacher_learn - '. mysqli_error($_connection)); 
	$_teacher_learn_processing = $b;

	$_is_teaching_substitue = "0";
	if($_POST['class_type']=="master"){
		$_is_teaching_substitue = "0";
	} else {
		$_is_teaching_substitue = "1";
	}
	$_sql_updateStudentTask = "
				UPDATE 
					student_learn_task 
				SET 
					task_status 	= '1',
					teacher_id 		= '" . $_POST['teacher_id'] . "',
					SubjectCode 	= '" . $_POST['subject_code'] . "',
					is_teaching_substitute = '" . $_is_teaching_substitue . "',
					updated_datetime = CURRENT_TIMESTAMP,
					updated_user = '" . $_SESSION['user_account_id'] . "' 
				WHERE 
					task_date = '" . $_POST['date'] . "' and 
					task_roomid = '" . $_POST['room_id'] . "' 
					and period = '" . $_POST['period'] . "'
				";

	$_resStudentTask = mysqli_query($_connection,$_sql_updateStudentTask) or die ('ผิดพลาดเนื่องจาก table: student_learn_task - ' . mysqli_error($_connection));
	$_student_learn_task_processing = $_resStudentTask;

}
	?>
	<div id="content">
		<div align="center">

			<?php
				// add line notification
				if($_student_learn_proccesing && $_student_learn_task_processing){

					$_verifySQL = "
									select class_id,
										sum(if(timecheck_id = '00',timecheck_id,null)+1) as a,
										sum(if(timecheck_id = '01',timecheck_id,null)) as b,
										sum(if(timecheck_id = '02',timecheck_id,null))/2 as c,
										sum(if(timecheck_id = '03',timecheck_id,null))/3 as d,
										sum(if(timecheck_id = '04',timecheck_id,null))/4 as e,
										sum(if(timecheck_id = '05',timecheck_id,null))/5 as f,
										count(class_id) as sum
									from student_learn
									where 
										check_date = '" . $_POST['date'] . "' and 
										class_id = '" . $_POST['room_id'] . "' and
										period = '" . $_POST['period'] . "'  
									group by class_id ";
					$_text = "ผลการบันทึกการเข้าเรียน ม. " . getFullRoomFormat($_POST['room_id']);
					$_text .= " รหัสวิชา " . $_POST['subject_code'] . " คาบเรียนที่ " . $_POST['period'];
					$_text .= "\n" . reportHeader($_POST['date']);

					if($_POST['class_type'] == "substitute"){
						$_text .= "\n(สอนแทน)";
					}

					$result = mysqli_query($_connection,$_verifySQL);

					if($result){
						$dat = mysqli_fetch_assoc($result);

						$_text .= "\n" . "มา: " .      ($dat['a']>0?$dat['a']:"-");
						$_text .= "\n" . "กิจกรรม: " .  ($dat['b']>0?$dat['b']:"-");
						$_text .= "\n" . "สาย: " .     ($dat['c']>0?$dat['c']:"-");
						$_text .= "\n" . "ลา: " .      ($dat['d']>0?$dat['d']:"-");
						$_text .= "\n" . "ขาด: " .     ($dat['e']>0?$dat['e']:"-");
						$_text .= "\n" . "หนีเรียน: " .  ($dat['f']>0?$dat['f']:"-");


						$_sum = 0;
						$_sum = ($dat['a']+$dat['b']+$dat['c']+$dat['d']+$dat['e']);

						$_text .= "\n" . "รวมทั้งหมด " . $_sum . " คน ";
						$_text .= "\n" . "บันทึกข้อมูลโดย - " . $_SESSION['shortname'];

						$message = $_text;
						SendLineMessage($message,$_line_token);
						SendLineMessage($_student_disappear,$_line_token);

						/* บันทึก อีเว็นต์ล็อก */

						$_event_details = "";
						$_event_details .= "บันทึกการเข้าเรียน ารบันทึกการเข้าเรียน ม. " . getFullRoomFormat($_POST['room_id']) ." รหัสวิชา " . $_POST['subject_code'] . " คาบเรียนที่ " . $_POST['period'];

						$_event_key = hash("sha256",$_event_details);

						$_event_user_id = $_SESSION['user_account_id'];

						if(checkDuplicateEventLog($_connection,$_event_key)){
							event_log($_connection,1,2,5,
							$_event_key,
							$_event_details,
							$_event_user_id,$acadyear,$acadsemester);
						}
						
					}
				}
				
			?>
			<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
				<tr> 
				<td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
				<td width="45%"><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>ระบบบันทึกการเข้าเรียนของนักเรียน >> บันทึกข้อมูล</strong></font></span></td>
				<td>ปีการศึกษา <?=$acadyear?> ภาคเรียนที่ <?=$acadsemester?> </td>
				</tr>
			</table>
			<br/>
			<table width="75%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#CC99FF">
				<tr bgcolor="#CCCCCC"> 
					<td colspan="2"><font size="2"  ><strong>ผลการดำเนินงาน</strong></font></td>
				</tr>
				<tr bgcolor="white"> 
					<td width="200px"><font size="2"  >ตรวจสอบรายชื่อห้อง</font></td>
					<td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
				</tr>
				
				<tr bgcolor="white"> 
					<td><font size="2"  >บันทึกการเข้าห้องเรียน</font></td>
					<td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
				</tr>
				<tr bgcolor="white"> 
					<td><font size="2"  >บันทึกการเข้าร่วมของครูที่ปรึกษา</font></td>
					<td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
				</tr>
				<tr bgcolor="white"> 
					<td><font size="2"  >ส่งข้อความแจ้งเตือนทางไลน์</font></td>
					<td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
				</tr>
				<tr bgcolor="white">
					<td colspan="2" align="center">
						<br/>
						<form action="index.php?option=module_learn/dateTaskListTeacher" method="post">
							<input type="hidden" name="date" value="<?=$_POST['date']?>"/>
							<input type="hidden" name="teacher_id" value="<?=$_POST['teacher_id']?>" />
							<input type="submit" name="submit" value="ดำเนินการต่อไป" />
						</form>
						<br/>
					</td>
				</tr>
				</table>
		</div>
	</div>

<? } else {
	header("Location: index.php?option=module_learn/dateTaskListTeacher");
}

?>


<?php

//-------------------------------
function timecheck_id($value){
	if($value == 'white') return '00';
	if($value == 'lightgreen') return '01';
	if($value == 'yellow') return '02';
	if($value == 'lightblue') return '03';
	if($value == 'orange') return '04';
	if($value == 'red') return '05';
	else return 9;

}

function reportHeader($date){
	$txt = "ประจำวันที่ " ;

	$_x = explode('-',$date,3);
	switch ($_x[1]) {
		case "01" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน มกราคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "02" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน กุมภาพันธ์  พ.ศ. " . ($_x[0] + 543) ;break;
		case "03" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน มีนาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "04" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน เมษายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "05" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน พฤษภาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "06" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน มิถุุนายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "07" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน กรกฎาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "08" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน สิงหาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "09" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน กันยายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "10" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน ตุลาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "11" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน พฤศจิกายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "12" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน ธันวาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		default : $txt = $txt . "ผิดพลาด";
	}
	
	return $txt  ;
}
function getDisciplineID($_connection){
	$_sql = "select max(dis_id) as id from student_discipline";
	$_res = mysqli_query($_connection,$_sql);
	$_dat = mysqli_fetch_assoc($_res);

	return $_dat['id']+1;


}

?>