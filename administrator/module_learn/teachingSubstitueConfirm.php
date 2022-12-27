
 
 <?php
	$_processing_message = "";
	$_processing_result  = false;

	if(isset($_POST['confirmTeaching'])){

		$_sql_confirm = " 
					UPDATE teaching_substitute 
					SET
						confirmed_datetime  = current_timestamp(),
						updated_user_id     = '" . $_SESSION['user_account_id'] . "',
						teaching_substitute_status = '1' 
					WHERE
						owner_teacher_id 	= '" . $_POST['owner_teacher_id'] . "' and
						acadyear 			= '" . $_POST['acadyear'] . "' and
						acadsemester 		= '" . $_POST['acadsemester'] . "' and
						SubjectCode			= '" . $_POST['subject_code'] . "' and
						teaching_date		= '" . $_POST['teaching_date'] . "' and
						period				= '" . $_POST['period'] . "' 
					";
		$_resConfirm = mysqli_query($_connection,$_sql_confirm);

		if($_resConfirm){
				
			$_processing_message = "ระบบได้บันทึก ยืนยันรับทราบ การเช้าสอนแทน ของท่านแล้ว <br/>";

			$_text  = "";
			$_text .= "ครู". getUserAccountName($_connection,$_POST['teacher_id']) . " ได้ยืนยันการเข้าสอนแทน ";
			$_text .= "ครู" . getUserAccountName($_connection,$_POST['owner_teacher_id']) . " ";
			$_text .= "ในวัน " . displayDayofWeek(date('w',strtotime($_POST['teaching_date']))) . " ";
			$_text .= "ที่ " . displayFullDate($_POST['teaching_date']) . " รหัสวิชา " . $_POST['subject_code'] . " ";
			$_text .= "ห้อง " . getFullRoomFormat($_POST['room_id']) . " ";
			$_text .= "คาบเรียนที่ " . $_POST['period'] . " แล้ว ";

			$_processing_message = $_text;

			$message = $_text;
			SendLineMessage($message,$_line_token);
			$_processing_result = true;

			/*
			$_event_details = "";
			$_event_details .= "มีการบันทึกการจัดสอนแทน วันที่ " . displayFullDate($_POST['date']);

			$_event_key = hash("sha256",$_event_details.date("H:i:s"));

			$_event_user_id = $_SESSION['user_account_id'];
			if(checkDuplicateEventLog($_connection,$_event_key)){
				event_log($_connection,1,2,4,
				$_event_key,
				$_event_details,
				$_event_user_id,$acadyear,$acadsemester);
			}
			*/

		}else{
			$_processing_message  = "ไม่สามารถบันทึก ยืนยันรับทราบ การเข้าสอนแทนได้เนื่องจากเกิดข้อผิดพลาด <br/>";
			$_processing_message .= "หรือใช้ข้อความต่อไปนี้แจ้งผู้ดูแลระบบ : " . mysqli_error($_connection);
		}
	} // end save data process

?>
 
<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.4 ยืนยัน/ติดตามการสอนแทน (ยืนยันรับทราบการสอนแทน) </strong></font></span></td>
      <td align="right">
        ปีการศึกษา
        <?php 
			echo '<font color=\'blue\'>' .$acadyear . '</font>';
		?>
        
        ภาคเรียนที่ 
        <?php 
		
			echo "<font color='blue'>" . $acadsemester . "</font>";
		?>
		<br/>
			
	  </td>
    </tr>
  </table>
	  
    <br/>

<? if(isset($_POST['confirm'])) { ?>
	<div align="center">

		<table>
			<tr>
				<td colspan="2" align="center">

					<br/><br/>
					ท่านต้องการยืนยันการเข้าสอนแทน <font color='green'><b>ครู<?=getUserAccountName($_connection,$_POST['owner_teacher_id'])?></b></font> <br/>
					ในวัน <font color='green'><b><?=displayDayofWeek(date('w',strtotime($_POST['teaching_date'])))?></b> </font>
					ที่ <font color='green'><b><?=displayFullDate($_POST['teaching_date'])?></b></font>
					รหัสวิชา <font color='green'><b><?=$_POST['subject_code']?></b></font> 
					ห้อง <font color='green'><b><?=getFullRoomFormat($_POST['room_id'])?></b></font>
					คาบเรียนที่ <font color='green'><b><?=$_POST['period']?></b></font> <br/>
					ให้ทำการกดปุ่ม "ยืนยันรับทราบ" ด้านล่างเพื่อบันทึกข้อมูลและแจ้งเตือนไปยังข้อความทางไลน์
					<br/><br/>
				</td>
			</tr>
			<tr>
				<td align="right" width="50%">
					<form method="post" action="">
						<input type="hidden" name="period" value="<?=$_POST['period']?>" />
						<input type="hidden" name="room_id" value="<?=$_POST['room_id']?>" />
						<input type="hidden" name="subject_code" value="<?=$_POST['subject_code']?>" />
						<input type="hidden" name="acadyear" value="<?=$_POST['acadyear']?>" />
						<input type="hidden" name="acadsemester" value="<?=$_POST['acadsemester']?>" />
						<input type="hidden" name="teaching_date" value="<?=$_POST['teaching_date']?>" />
						<input type="hidden" name="owner_teacher_id" value="<?=$_POST['owner_teacher_id']?>" />
						<input type="hidden" name="teacher_id" value="<?=$_POST['teacher_id']?>" />
						<input type="submit" name="confirmTeaching" value="ยืนยันรับทราบ" id="confirm"/> 
					</form>
				</td>
				<td>
					<form method="post" action="index.php?option=module_learn/teachingSubstitueConfirmList">
					<input type="hidden" name="date" value="<?=$_POST['teaching_date']?>" />
						<input type="submit" name="submit" value="ยกเลิก" /> 
					</form>
				</td>
			</tr>
		</table>

	</div>
	<? } //end if-check search ?>
<? if(isset($_POST['confirmTeaching'])) { ?>
	<div align="center">

		<table>
			<tr>
				<td align="center">
				<?
						if($_processing_result){
							echo "<br/><br/><font color='green'>" . $_processing_message . "</font><br/><br/>";
						}else{
							echo "<br/><br/><font color='red'>" . $_processing_message . "</font><br/><br/>";
						}
					?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<form method="post" action="index.php?option=module_learn/teachingSubstitueConfirmList">
					<input type="hidden" name="date" value="<?=$_POST['teaching_date']?>" />
						<input type="submit" name="submit" value="ดำเนินการต่อไป" /> 
					</form>
				</td>
			</tr>
		</table>

	</div>
	<? } //end if-check search ?>
</div>

