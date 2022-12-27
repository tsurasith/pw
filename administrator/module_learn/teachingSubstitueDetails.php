

<?php

	$_processing_text = "";
	$_processing_result = false;

	if(isset($_POST['confirmApprove'])){
		if($_POST['approved_datetime']==""){
			$_sqlApproved = "
					UPDATE teaching_substitute 
					SET
						approved_datetime  = current_timestamp(),
						approved_user_id     = '" . $_SESSION['user_account_id'] . "',
						teaching_substitute_status = '3' 
					WHERE
						teacher_id 			= '" . $_POST['teacher_id'] . "' and
						acadyear 			= '" . $acadyear . "' and
						acadsemester 		= '" . $acadsemester . "' and
						SubjectCode			= '" . $_POST['subject_code'] . "' and
						teaching_date		= '" . $_POST['teaching_date'] . "' and
						period				= '" . $_POST['period'] . "' 
				";
			$_resApprove = mysqli_query($_connection,$_sqlApproved);
			if($_resApprove){
				$_processing_text = "การสอนแทนรหัสวิชา " . $_POST['subject_code'] . " ";
				$_processing_text .= "วัน" . displayDayofWeek(date('w',strtotime($_POST['teaching_date']))) . " ";
				$_processing_text .= "ที่ " . displayFullDate($_POST['teaching_date']) . " ";
				$_processing_text .= "ได้รับการตรวจสอบแล้ว \n";
				$_processing_text .= "โดย " . $_SESSION['shortname'] . " ";

				$message = $_processing_text;
				SendLineMessage($message,$_line_token);

			}else{
				$_processing_text  = "ไม่สามารถบันทึก การอนุมัติ ได้เนื่องจากเกิดข้อผิดพลาด <br/>";
				$_processing_text .= "หรือใช้ข้อความต่อไปนี้แจ้งผู้ดูแลระบบ : " . mysqli_error($_connection);
			}

		}
	}
?>

<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.4 ยืนยัน/ติดตามการสอนแทน (Reviews and approval) </strong></font></span></td>
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
			echo $acadyear;
		?>
        
        ภาคเรียนที่ 
        <?php 
			echo $acadsemester;
		?>
				<br/>
			
		<form name="sSelect" method="post" action="index.php?option=module_learn/teachingSubstitueConfirmList">
			<input type="hidden" name="date" value="<?=$_POST['teaching_date']?>">
			<input type="hidden" name="teaching_date" value="<?=$_POST['teaching_date']?>">
			<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
			<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
			<input type="hidden" name="search" value="search"> <br/>
			<input type="submit" name="submit" value="ย้อนกลับ">
		</form>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if((isset($_POST['details']) && $_POST['teaching_date'] != "")) { ?>

		<?php
			$_sql_teachingSubstitute = "
				select 
						u.user_account_prefix, 
						u.user_account_firstname,
						u.user_account_lastname,
						s.acadyear,
						s.acadsemester,
						s.teacher_id,
						s.owner_teacher_id,
						s.teaching_date,
						s.SubjectCode,
						s.period,
						s.room_id,
						convert_tz(s.created_datetime,'+00:00','+07:00') as created_datetime,
						convert_tz(s.confirmed_datetime,'+00:00','+07:00') as confirmed_datetime,
						convert_tz(s.approved_datetime,'+00:00','+07:00') as approved_datetime,
						convert_tz(s.updated_datetime,'+00:00','+07:00') as updated_datetime,
						s.approved_user_id,
						s.has_assignment,
						trim(s.teaching_details) as teaching_details,
						o.user_account_prefix as owner_prefix, 
						o.user_account_firstname as owner_firstname,
						o.user_account_lastname as owner_lastname,
						s.teaching_substitute_status
				from 
					teaching_substitute s left join users_account u
					on (s.teacher_id = u.user_account_id)
					left join users_account o
					on (s.owner_teacher_id = o.user_account_id)
				where
					s.teaching_date = '". $_POST['teaching_date'] ."' and
					s.teacher_id = '" . $_POST['teacher_id'] . "'  and
					s.period = '" . $_POST['period'] . "' 
				order by
					convert(o.user_account_firstname using tis620),
					convert(o.user_account_lastname using tis620),
					s.period
			";
			$_res_display = mysqli_query($_connection,$_sql_teachingSubstitute);
			$_count_display = mysqli_num_rows($_res_display);

			$_no = 1;
			$_previous_name = "";

		?>
		<?	if($_count_display>0){ ?>
				<div align="center">
					<br/>
					<form action="" method="post">
						<table class="admintable">
						<? while($_dat = mysqli_fetch_assoc($_res_display)){ ?>
							<tr>
								<th align="center" colspan="11">
								รายละเอียดการสอนแทน <?=displayDayofWeek(date('w',strtotime($_POST['teaching_date'])))?>  
								ที่  <?=displayFullDate($_POST['teaching_date'])?> <br/>
								ห้อง <font color='green'><?=getFullRoomFormat($_dat['room_id'])?></font>
								รหัสวิชา <font color='green'><?=$_dat['SubjectCode']?></font> 
								คาบเรียนที่ <font color='green'><?=$_dat['period']?></font> 
								<br/><br/>
								</th>
							</tr>
							<tr>
								<td class="key" align="center" width="140px">ครูเจ้าของวิชา</td>
								<td class="key" align="center" width="140px">ครูสอนแทน</td>
								<td class="key" align="center" width="60px">ใบงาน</td>
								<td class="key" align="center" width="540px">บันทึกการสอนแทน</td>
							</tr>
							<tr>
								<td valign="top" align="center">
									<?
										echo "ครู" . $_dat['owner_firstname'] . ' ' . $_dat['owner_lastname'];
									?>
								</td>
								<td valign="top" align="center"><?='ครู' . $_dat['user_account_firstname'] . ' ' . $_dat['user_account_lastname']?></td>
								<td align="center" valign="top">
									<?php
										if($_dat['has_assignment']=="1"){
											echo "มี";

										}else if($_dat['has_assignment']=="0"){
											echo "ไม่มี";
										}
										else{
											echo "-";
										}
									?>
								</td>
								<td align="left" valign="top">
									<?php
										if(strlen($_dat['teaching_details'])>0){
											echo $_dat['teaching_details'];
										}
										else{
											echo "-";
										}
									?>
									<br/><br/>
									
									<?
										// displayTeachingSubstituteStatus($_dat['teaching_substitute_status'])
										$_sum = 0;
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
															check_date = '" . $_POST['teaching_date'] . "' and 
															class_id = '" . $_dat['room_id'] . "' and
															period = '" . $_dat['period'] . "'  
														group by class_id ";

										$result = mysqli_query($_connection,$_verifySQL);
										$_rows_stat = mysqli_num_rows($result);

										if($_rows_stat>0){
										$_datStat = mysqli_fetch_assoc($result);
										$_sum = ($_datStat['a']+$_datStat['b']+$_datStat['c']+$_datStat['d']+$_datStat['e']+$_datStat['f']);
									?>
											<table class="admintable">
												<tr>
													<td colspan="2" class="key" align="center"><b>การเข้าเรียน</b></td>
												</tr>
												<tr>
													<td align="right" width="85px">มา</td>
													<td align="right" width="30px"><?=($_datStat['a']>0?$_datStat['a']:"-")?></td>
												</tr>
												<tr>
													<td align="right">สาย</td>
													<td align="right"><?=($_datStat['b']>0?$_datStat['b']:"-")?></td>
												</tr>
												<tr>
													<td align="right">กิจกรรม</td>
													<td align="right"><?=($_datStat['c']>0?$_datStat['c']:"-")?></td>
												</tr>
												<tr>
													<td align="right">ลา</td>
													<td align="right"><?=($_datStat['d']>0?$_datStat['d']:"-")?></td>
												</tr>
												<tr>
													<td align="right">ขาด</td>
													<td align="right"><?=($_datStat['e']>0?$_datStat['e']:"-")?></td>
												</tr>
												<tr>
													<td align="right">หนีเรียน</td>
													<td align="right"><?=($_datStat['f']>0?$_datStat['f']:"-")?></td>
												</tr>
												<tr>
													<td class="key" align="center">รวม</td>
													<td class="key" align="right"><?=$_sum?></td>
												</tr>
											</table>
									<? } // end if - in class data ?>
								</td>
							</tr>
							<tr>
								<td align="right" colspan="3"></td>
								<td><?=$_dat['created_datetime']?> - เวลาแจ้งสอนแทน</td>
							</tr>
							<tr>
							<td align="right" colspan="3"></td>
								<td><?=($_dat['confirmed_datetime']==""?"0000-00-00 00:00:00":$_dat['confirmed_datetime'])?> - เวลายืนยันรับทราบ</td>
							</tr>
							<tr>
							<td align="right" colspan="3"></td>
								<td><?=(($_rows_stat==0 || $_dat['updated_datetime']=="")?"0000-00-00 00:00:00":$_dat['updated_datetime'])?> - เวลาบันทึกการสอนแทน</td>
							</tr>
							<? if($_dat['approved_datetime']!=""){ ?> 
								<tr>
									<td align="right" colspan="3"></td>
									<td>
										<?=$_dat['approved_datetime']?> - เวลาตรวจสอบและอนุมัติ <br/>
										<? if($_dat['approved_datetime']!="") { ?>
											ตรวจสอบและอนุมัติโดย <b><?=getUserAccountName($_connection,$_dat['approved_user_id'])?></b>
											<br/><br/>
									<? } //end-if approver ?>
									</td>
								</tr>
							<? } else if($_dat['teaching_substitute_status']=="2") {  ?>
								<tr>
									<td colspan="2">
										<input type="hidden" name="details" value="<?=$_dat['teaching_details']?>" />
										<input type="hidden" name="teaching_date" value="<?=$_dat['teaching_date']?>" />
										<input type="hidden" name="period" value="<?=$_dat['period']?>" />
										<input type="hidden" name="teacher_id" value="<?=$_dat['teacher_id']?>" />
										<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
										<input type="hidden" name="approved_datetime" value="<?=$_dat['approved_datetime']?>" />									</td>
									<td align="center"><br/>
										<input type="submit" name="confirmApprove" value="Approve"  />
									</td>
									<td></td>
								</tr>
							<? } ?>
						<? } ?>
						</table>
					</form>


				</div>
		<?	} else { ?>
			<div align="center">
					<br/><br/>
					<font color="red">ยังไม่มีการบันทึกข้อมูลในส่วนนนี้</font>
					<br/>
			</div>
		<? } ?>

	
	<? } //end if-check search ?>
</div>

