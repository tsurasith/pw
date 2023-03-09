
<?php


	$_subject_code = "";
	$_date = "";
	$_period = "";
	$_acadyear = "";
	$_acadsemester = "";
	$_teacher_id = "";
	$_class_type = "";

	if(isset($_POST['save'])){
		$_subject_code = $_POST['subject_code'];
		$_date = $_POST['date'];
		$_period = $_POST['period'];
		$_acadyear = $_POST['acadyear'];
		$_acadsemester = $_POST['acadsemester'];
		$_teacher_id = $_POST['teacher_id'];
		$_class_type = $_POST['class_type'];
	}else{
		$_subject_code = $_REQUEST['subject'];
		$_date = $_REQUEST['date'];
		$_period = $_REQUEST['period'] ;
		$_acadyear = $_REQUEST['acadyear'];
		$_acadsemester = $_REQUEST['acadsemester'];
		$_teacher_id = $_REQUEST['teacher_id'];
		$_class_type = $_REQUEST['class_type'];
	}

	$_processing_text = "";
	$_processing_result = false;


	if(isset($_POST['save']) && trim($_POST['teaching_details']) != ""){

		$_sql_check = "
			SELECT 
				confirmed_datetime
			FROM 
				teaching_substitute
			WHERE
				teacher_id 			= '" . $_teacher_id . "' and
				acadyear 			= '" . $_acadyear . "' and
				acadsemester 		= '" . $_acadsemester . "' and
				SubjectCode			= '" . $_subject_code . "' and
				teaching_date		= '" . $_date . "' and
				period				= '" . $_period . "' 
		";
		$_resCheck = mysqli_query($_connection,$_sql_check);
		$_datCheck = mysqli_fetch_assoc($_resCheck);

		if(@$_datCheck['confirmed_datetime']==""){
			$_sql_confirm = " 
				UPDATE teaching_substitute 
				SET
					confirmed_datetime  = current_timestamp(),
					updated_datetime    = current_timestamp(),
					updated_user_id     = '" . $_SESSION['user_account_id'] . "',
					teaching_substitute_status = '1' 
				WHERE
					teacher_id 			= '" . $_teacher_id . "' and
					acadyear 			= '" . $_acadyear . "' and
					acadsemester 		= '" . $_acadsemester . "' and
					SubjectCode			= '" . $_subject_code . "' and
					teaching_date		= '" . $_date . "' and
					period				= '" . $_period . "' 
				";
				
			$_resConfirm = mysqli_query($_connection,$_sql_confirm);
		}

		$_sql = "
			UPDATE teaching_substitute 
				SET
					has_assignment      = '" . $_POST['has_assignment'] . "',
					teaching_details    = '" . trim($_POST['teaching_details']) . "',  
					updated_datetime    = current_timestamp(),
					updated_user_id     = '" . $_SESSION['user_account_id'] . "',
					approved_datetime   = NULL,
					teaching_substitute_status = '2' 
				WHERE
					teacher_id 			= '" . $_POST['teacher_id'] . "' and
					acadyear 			= '" . $_POST['acadyear'] . "' and
					acadsemester 		= '" . $_POST['acadsemester'] . "' and
					SubjectCode			= '" . $_POST['subject_code'] . "' and
					teaching_date		= '" . $_POST['date'] . "' and
					period				= '" . $_POST['period'] . "' and
					room_id				= '" . $_POST['room_id'] . "'  
			";
		
		
		$_resUpdate = mysqli_query($_connection,$_sql);
		if($_resUpdate){


			$_processing_text = "บันทึกการสอนแทนเรียบร้อยแล้ว";
			$_processing_result = true;


			$_text  = "";
			$_text .= "ครู". getUserAccountName($_connection,$_POST['teacher_id']) . " บันทึกการเข้าสอนแทน ";
			$_text .= "ในวัน " . displayDayofWeek(date('w',strtotime($_POST['date']))) . " ";
			$_text .= "ที่ " . displayFullDate($_POST['date']) . " รหัสวิชา " . $_POST['subject_code'] . " ";
			$_text .= "ห้อง " . getFullRoomFormat($_POST['room_id']) . " ";
			$_text .= "คาบเรียนที่ " . $_POST['period'] . " เรียบร้อยแล้ว ";

			$_processing_message = $_text;

			$message = $_text;
			SendLineMessage($message,$_line_token);


		}else{
			$_processing_text  = "ไม่สามารถบันทึก บันทึกการสอนได้เนื่องจากเกิดข้อผิดพลาด <br/>";
			$_processing_text .= "หรือใช้ข้อความต่อไปนี้แจ้งผู้ดูแลระบบ : " . mysqli_error($_connection);
		}
	}

?>
<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>2.1 บันทึกการเข้าเรียน (บันทึกการสอนแทน)</strong></font></span></td>
      <td align="right">
        ปีการศึกษา <?=$acadyear?>
        ภาคเรียนที่ <?=$acadsemester?>

		<form method="post" id="cancel-form" name="cCancel" action="index.php?option=module_learn/dateTaskListTeacher">
			<input type="hidden" name="date" value="<?=$_date?>" />
			<input type="hidden" name="teacher_id" value="<?=$_teacher_id?>" />
			<input type="submit" name="submit" value="ย้อนกลับ" /> 
		</form>

	  </td>
    </tr>
  </table>
	<?php
			$_roomID = "";
			$_roomID = isset($_REQUEST['room'])?$_REQUEST['room']:"";
			
			if($_roomID==""){
				$_roomID = isset($_POST['room_id'])?$_POST['room_id']:"";
			}
			
			
			$room_id = getRoom($_roomID);
			$xyear = getXyearth($_roomID);
			$xlevel  = getXLevel($_roomID);
			/*
				$date = ????-??-??
				$room_id = ?
				$room = ?0?
				$xlevel = 3|4
				$xyear = 1-6
			*/

			$_sql_learntable = "";
			

			if(isset($_POST['save'])){
				$_sql_learntable = "
					SELECT
						t.teacher_id,
						t.SubjectCode,
						t.location,
						t.period,
						t.room_id,
						t.acadyear,
						t.acadsemester,
						t.weekday,
						s.*
					FROM
						teaching_schedule t 
						INNER JOIN teaching_substitute s ON
						(
							t.acadyear     = s.acadyear AND 
							t.acadsemester = s.acadsemester AND 
							t.weekday      = s.weekday AND 
							t.period       = s.period AND 
							t.room_id      = s.room_id AND 
							s.teacher_id   IS NOT NULL
						)
				WHERE
						s.teaching_date = '" . $_POST['date'] . "' and
						(t.teacher_id = '" . $_POST['teacher_id'] . "' or s.teacher_id = '" . $_POST['teacher_id'] . "'  )and
						(t.room_id = '". $_POST['room_id'] . "' or s.room_id = '". $_POST['room_id'] . "') and
						(t.SubjectCode = '" . $_POST['subject_code'] . "' or s.SubjectCode = '" . $_POST['subject_code'] . "' ) and 
						s.period = '" . $_POST['period'] . "' 
					";
			}else{
				$_sql_learntable = "
					SELECT
						t.teacher_id,
						t.SubjectCode,
						t.location,
						t.period,
						t.room_id,
						t.acadyear,
						t.acadsemester,
						t.weekday,
						s.*
					FROM
						teaching_schedule t 
						INNER JOIN teaching_substitute s ON
						(
							t.acadyear     = s.acadyear AND 
							t.acadsemester = s.acadsemester AND 
							t.weekday      = s.weekday AND 
							t.period       = s.period AND 
							t.room_id      = s.room_id AND 
							s.teacher_id IS NOT NULL
						)
				WHERE
						s.teaching_date = '" . $_REQUEST['date'] . "' and
						(t.teacher_id = '" . $_REQUEST['teacher_id'] . "' or s.teacher_id = '" . $_REQUEST['teacher_id'] . "'  )and
						(t.room_id = '". $_REQUEST['room'] . "' or s.room_id = '". $_REQUEST['room'] . "') and
						(t.SubjectCode = '" . $_REQUEST['subject'] . "' or s.SubjectCode = '" . $_REQUEST['subject'] . "' ) and 
						t.period = '" . $_REQUEST['period'] . "' 
					" ;
			}


			// echo $_sql_learntable . "<br/>";
			$_resCheck = mysqli_query($_connection,$_sql_learntable);
			$_check_data = mysqli_num_rows($_resCheck);


	?>
	<? if($_check_data>0) { ?>
		<? $_dat = mysqli_fetch_assoc($_resCheck); ?>
		<div align="center">
		<form method="post" action="index.php?option=module_learn/substituteTeachingRecord">
			<table  align="center" cellspacing="1" class="admintable" width="600px">
				<tr>
					<td class="key" align="center" colspan="2">
						บันทึกการสอน รหัสวิชา <font color='green'><?=$_subject_code?></font> 
						<font color='#641E16'><?=$_class_type=="substitute"?"(สอนแทน)":""?></font> <br/>
						ประจำวัน <?=displayDayOfWeek(date('w',strtotime($_date)))?>  
						ที่ <font color="green"><?=displayFullDate($_date)?></font>
						ห้อง  <font color="green"><?=getFullRoomFormat($_roomID)?> </font>
						คาบที่ <font color="green"><?=$_REQUEST['period']?></font><br/>
						ภาคเรียนที่ <?=$_acadsemester?>  ปีการศึกษา <?=$_acadyear?><br/>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>ใบงาน หรือ งานที่ครูประจำวิชามอบหมาย</b><br/><br/>
						<input type="radio" name="has_assignment" value="0" <?=$_dat['has_assignment']==0?"checked":""?> /> ไม่มี <br/>
						<input type="radio" name="has_assignment" value="1" <?=$_dat['has_assignment']==1?"checked":""?> /> มี <br/><br/>
					</td>
				</tr>
				<tr>
					<td valign="top" width="120px"><b>บันทึกการสอน</b></td>
					<td>	<textarea cols="40" rows="5" name="teaching_details" class="inputboxUpdate"><?=$_dat['teaching_details']?></textarea>
					</td>
				</tr>
				<tr>
					<td>แก้ไขล่าสุด</td>
					<td><?=$_dat['updated_datetime']?></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						
							<input type="hidden" name="room_id" value="<?=$_roomID; ?>"/>
							<input type="hidden" name="date" value="<?=$_date?>"/>
							<input type="hidden" name="period" value="<?=$_period?>"/>
							<input type="hidden" name="acadyear" value="<?=$_acadyear?>"/>
							<input type="hidden" name="acadsemester" value="<?=$_acadsemester?>"/>		
							<input type="hidden" name="teacher_id" value="<?=$_teacher_id?>" />
							<input type="hidden" name="subject_code" value="<?=$_subject_code?>" />
							<input type="hidden" name="class_type" value="<?=$_class_type?>" /> 
							<input type="submit" name="save" value="บันทึก"/>
							<input type="button" name="cancel" value="ยกเลิก" id="cancel-btn" 
									onclick="location.href='index.php?option=module_learn/dateTaskListTeacher'"/>
						
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<?php
							if(isset($_POST['save']) && $_processing_text != ""){
								if($_processing_result){
									echo "<font color='green'>";
									echo $_processing_text;
									echo "</font>";
								}else{
									echo "<font color='red'>";
									echo $_processing_text;
									echo "</font>";
								}
							}
						?>
						
					</td>
				</tr>
		</table>
		</form>
	</div>	
		
	<? } else { ?>
		<div align="center">
			<br/><br/><br/>
			<font color='red'>
				เกิดข้อผิดพลาด ไม่พบข้อมูลตามที่ร้องขอ กรุณาย้อนกับไปที่หน้าหลักแล้วทำการเลือกวันที่และวิชาที่สอนของท่าน อีกครั้ง
			</font><br/><br/><br/>
			<input type="button" value="ตกลง" 
					onclick="location.href='index.php?option=module_learn/dateTaskListTeacher'" />
		</div>
	<? } ?>
		
</div>