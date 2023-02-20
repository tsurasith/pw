﻿<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear']))     { $acadyear = $_POST['acadyear'];}
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester'];}
?>


<script language="javascript" type="text/javascript">
	checked = false;
	function checkedAll (count) {
		var aa= document.getElementById('frm1');
			if (checked == false){checked = true}
			else{checked = false}
			
		for (var i =1; i < count+1; i++) 
		{
			if(!document.getElementById('chk'+i).disabled)
			{ 
				document.getElementById('chk' + i).checked = checked;
				if(checked == true){document.getElementById('row'+i).bgColor='#FFFF99';}
				else{document.getElementById('row'+i).bgColor='#FFFFFF';}
			}
		} 
	}

	function changeBackgroundColor(name,i)
	{
		if(document.getElementById('chk'+i).checked == true){ 
			document.getElementById(name).bgColor='#FFFF99'; 
		}
		else {
			document.getElementById(name).bgColor='#FFFFFF';
		}
	}
</script>

<?php

	$_processing_text = "";
	$_processing_result = false;

	$_subject_register_id = "";

?>

<? 
	if(isset($_POST['save'])){

		// looping for inseart or replace
		for($_i = 1; $_i < $_POST['counter']; $_i++){
			if(isset($_POST['student'][$_i])){
				
				$_data = explode("|",$_POST['student'][$_i]);
				// $_data[0] = 08818
				// $_data[1] = register_student_id

				$_sql = "";

				if($_data[1]== "" && isset($_POST['selected_student'][$_i])){
					$_sql = "
						INSERT INTO `register_students`(
							`student_id`,
							`SubjectCode`,
							`acadyear`,
							`acadsemester`,
							`created_datetime`,
							`created_user`,
							`updated_datetime`,
							`updated_user`,
							`subject_register_id`
						)
						VALUES
						(
							'" . $_data[0] . "', 
							'" . $_POST['SubjectCode'] . "',
							'" . $_POST['acadyear']    . "',
							'" . $_POST['acadsemester'] . "',
							CURRENT_TIMESTAMP,
							'" . $_SESSION['user_account_id'] . "',
							CURRENT_TIMESTAMP,
							'" . $_SESSION['user_account_id'] . "', 
							'" . $_POST['subject_register_id'] . "' 
						)
					";
					$_res = mysqli_query($_connection,$_sql);
					if($_res){
						$_processing_result = true;
					}else{
						$_processing_result = false;
					}
				}

				if($_data[1]!="" && !isset($_POST['selected_student'][$_i])){
					$_sql = "
						delete from `register_students` 
						where
							student_id = '" . $_data[0] . "' and 
							SubjectCode = '" . $_POST['SubjectCode'] . "' and
							register_student_id = '" . $_data[1] . "' 
						";
					$_res = mysqli_query($_connection,$_sql);
					if($_res){
						$_processing_result = true;
					}else{
						$_processing_result = false;
					}
				}

				//echo $_sql . "<br/>";
				
			}
		}

		//line message
		if($_processing_result){

			$_processing_text  = "มีการบันทึกลงทะเบียนเรียนของนักเรียน รายวิชา - " . $_POST['SubjectCode'] . ': ' . $_POST['SubjectName'] . " ";
			$_processing_text .= " ชั้นมัธยมศึกษาปีที่ " . substr($_POST['subject_info'],0,1);
			$_processing_text .= " ภาคเรียนที่ " . $acadsemester . " ปีการศึกษา " . $acadyear . "  ";
			$_processing_result = true;

			// line message here
			$_text  = "";
			$_text .= $_processing_text;
			$_text .= " " . "โดย - " . $_SESSION['shortname'];

			$message = $_text;
			SendLineMessage($message,$_line_token);

			$_event_details = "";
			$_event_details .= $_text;

			$_event_key = hash("sha256",$_event_details);

			$_event_user_id = $_SESSION['user_account_id'];
			if(checkDuplicateEventLog($_connection,$_event_key)){
				event_log($_connection,1,1,2,
				$_event_key,
				$_event_details,
				$_event_user_id,$acadyear,$acadsemester);
			}

		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกการลงทะเบียนเรียนได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึก <b>ซ้ำหลายรอบระหว่างระบบประมวลผล</b> หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ - " . mysqli_error($_connection);
		}
	}
?>



<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr> 
			<td align="center">
				<a href="index.php?option=module_curriculum/index"><img src="../images/graduation.png" alt="" width="48" height="48" border="0" /></a>
			</td>
			<td>
				<strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>4.3 จัดการลงทะเบียนเรียนแบบคละห้อง</strong></font></span>
			</td>
			<td>
				<form method="post">
					<? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; } ?>
					ปีการศึกษา<?php  
							echo "<a href=\"index.php?option=module_curriculum/RegisterStudentByLevel&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo '<font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/RegisterStudentByLevel&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
						ภาคเรียนที่
							<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
									else {
										echo " <a href=\"index.php?option=module_curriculum/RegisterStudentByLevel&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
									}
									if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
									else {
										echo " <a href=\"index.php?option=module_curriculum/RegisterStudentByLevel&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
									}
								?>
					</font><br/>
					<font  size="2" color="#000000">เลือกรายวิชา
					<?php 
							$_sql_subject_info = "
											select 
												r.SubjectCode,
												s.SubjectName,
												r.subject_level,
												r.acadyear,
												r.acadsemester,
												r.teacher_id,
												r.subject_register_id,
												concat(r.subject_level,'|',r.SubjectCode,'|',r.subject_register_id,'|',r.teacher_id,'|',s.SubjectName) as subject_info
											from 
												register_teachers r 
												left join curriculum_subjects s on (r.SubjectCode = s.SubjectCode)
											where 
												r.acadyear       = '" . $acadyear . "' and 
												r.acadsemester   = '" . $acadsemester . "' and
												r.is_split_class = '1' and
												s.SubjectType    != 'กิจกรรมพัฒนาผู้เรียน'
											order by
												r.subject_level,
												convert(r.SubjectCode using tis620)
										";
							$_res_subject_info = mysqli_query($_connection,$_sql_subject_info);	
							
					?>
					
						<select name="subject_info" class="inputboxUpdate">
							<option value=""></option>
							<? while($_sub = mysqli_fetch_assoc($_res_subject_info)) {
									$_select = (isset($_POST['subject_info'])&& $_POST['subject_info'] == $_sub['subject_info']?"selected":"");
									echo "<option value=\"" . $_sub['subject_info'] . "\" $_select>";
									echo $_sub['SubjectCode'] . ' ชั้น ม.' . $_sub['subject_level'];
									echo "</option>";
								}
							?>
						</select>
						<input type="submit" value="เรียกดู" class="button" name="search"/> <br/>
						<input type="checkbox" name="registered" value="OK"  <?=isset($_POST['registered'])=="OK"?"checked='checked'":"";?> />
						เฉพาะนักเรียนที่ลงทะเบียนเรียนวิชานี้
					
					</font>
				</form>
			</td>
		</tr>
	</table>


<?
	  $_xlevel  = 0;
	  $_xyearth = 0;

 ?>
	<? if(isset($_POST['search']) && $_POST['subject_info'] == ""){ ?>
		<center><br/><font color="#FF0000">กรุณาเลือกห้องรายวิชาก่อน !</font></center>
	<? }//end if ตรวจสอบรายวิชา ?>

	<? if(isset($_POST['search']) && $_POST['subject_info'] != ""){ ?>
		<?php
			
			// ex - 1|ว21282|2defda02-0a5c-4d8f-a3a2-7067e13d74ea|00000000-0000-0000-0000-000000000000|คอมพิวเตอร์ 2
			$_sub_info = explode("|",$_POST['subject_info']);

			if($_sub_info[0]>3){
				$_xlevel = 4;
				$_xyearth = $_sub_info[0]-3;
			}else{
				$_xlevel = 3;
				$_xyearth = $_sub_info[0];
			}
			
			$_sql_student = "
							select 
								s.id,
								r.student_id,
								s.prefix,s.firstname,s.lastname,s.nickname,s.room,s.studstatus,
								r.register_student_id
							from 
								students s left join register_students r
								on (
									 s.id = r.student_id and
									 s.xedbe = r.acadyear and
									 r.SubjectCode = '" . $_sub_info[1] . "' and
									 r.acadsemester = '" . $acadsemester . "' 
								    )
							where 
								s.xlevel  = '". $_xlevel . "' and 
								s.xyearth = '" . $_xyearth . "' and 
								s.xedbe   = '" . $acadyear . "' and 
								s.studstatus in (1,2) 
							";
			$_sql_student .= "";

			if(isset($_POST['registered'])=="OK") $_sql_student .= " and r.student_id IS NOT NULL";
			$_sql_student .= " order by s.room,s.sex, convert(s.firstname using tis620),convert(s.lastname using tis620)";

			$_res_student = mysqli_query($_connection,$_sql_student);

			//echo $_sql_student."<br/>";
		?>


		<? if(mysqli_num_rows($_res_student)>0){ ?>
			<div align="center">
				<form method="post" id="frm1">
					<table class="admintable" >
						<tr>
							<td colspan="8" align="center">
								<img src="../images/school_logo.png" width="120px"><br/>
								<b>รายชื่อนักเรียนชั้นมัธยมศึกษาปีที่ <?=$_sub_info[0]?> ภาคเรียนที่ <?=$acadsemester.'/'.$acadyear?><br/>
								สำหรับการจัดการลงทะเบียนเรียน รายวิชา <?=$_sub_info[1]?> : 
								<?=$_sub_info[4]?></b>
							</td>
						</tr>
						<tr>
							<td width="40px" align="center" class="key">เลขที่</td>
							<td width="85px" align="center" class="key">เลขประจำตัว</td>
							<td  align="center" class="key">ชื่อ - นามสกุล</td>
							<td align="center" width="100px" class="key">ชื่อเล่น</td>
							<td align="center" width="60px" class="key">ห้อง</td>
							<td width="100px"  align="center" class="key">สถานภาพปัจจุบัน</td>
							<td width="100px" align="center" class="key">สถานะ<br/>ลงทะเบียน</td>
							<td align="center" width="40px" class="key"><input type='checkbox' name='checkall' onclick='checkedAll(<?=mysqli_num_rows($_res_student)?>);' ></td>
						</tr>
						<? $_i = 1;?>
						<? while($_dat = mysqli_fetch_assoc($_res_student)){ ?>
						<tr id="row<?=$_i?>" <?=$_dat['register_student_id']!=""?"bgcolor='#FFFF99'":""?> >
							<td align="center"><?=$_i?></td>
							<td align="center"><?=$_dat['id']?></td>
							<td><?=$_dat['prefix'].$_dat['firstname']. ' ' .$_dat['lastname']?></td>
							<td align="center"><?=$_dat['nickname']!=""?$_dat['nickname']:"-"?></td>
							<td align="center"><?=$_sub_info[0].'/'.$_dat['room']?></td>
							<td align="center"><?=displayStudentStatusColor($_dat['studstatus'])?></td>
							<td align="center">
								<?php
									if($_dat['student_id']!=""){
										echo "ลงทะเบียนแล้ว";
									}else{
										echo "-";
									}

									$_check_box_value = "";

									$_check_box_value = $_dat['id'] . "|" . $_dat['register_student_id'] . "";

								?>
							</td>
							<td align="center">
								<input type="hidden"   name="student[<?=$_i?>]" value="<?=$_check_box_value?>" />
								<input type="checkbox" name="selected_student[<?=$_i?>]" 
									   id="chk<?=$_i?>" value="<?=$_check_box_value?>" 
									   <?=$_dat['register_student_id']!=""?"checked":""?>
									   onclick="changeBackgroundColor('row<?=$_i?>',<?=$_i++?>)" />
							</td>
						</tr>
						<? } //end while?>
						<tr height="50px">
							<td colspan="7" align="center">
								<input type="hidden" name="acadyear"            value="<?=$acadyear?>" />
								<input type="hidden" name="acadsemester"        value="<?=$acadsemester?>" />
								<input type="hidden" name="counter"             value="<?=$_i?>" />
								<input type="hidden" name="SubjectCode"         value="<?=$_sub_info[1]?>" />
								<input type="hidden" name="SubjectName"         value="<?=$_sub_info[4]?>" />
								<input type="hidden" name="subject_register_id" value="<?=$_sub_info[2]?>" />
								<input type="hidden" name="search"              value=""/>
								<input type="hidden" name="subject_info"        value="<?=$_POST['subject_info']?>" />
								<input type="submit" name="save"                value="บันทึก" class="button" />
								<input type="button" value="ยกเลิก" class="button" onclick="location.href='index.php?option=module_curriculum/RegisterStudentByLevel'" />
							</td>
						</tr>
					</table>
				</form>
			</div>
		<? } else { //end check rows ?>
				<center><br/><font color="#008000">ไม่พบข้อมูลตามที่ค้นหา</font></center>
		<? } //end else ?>
	<? }//end if ?>

</div>


