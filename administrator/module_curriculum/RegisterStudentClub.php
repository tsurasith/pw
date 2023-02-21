<?php
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
							`club_code`,
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
							'" . $_POST['club_code'] ."',
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
							student_id          = '" . $_data[0] . "' and 
							SubjectCode         = '" . $_POST['SubjectCode'] . "' and
							register_student_id = '" . $_data[1] . "' and
							club_code           = '" . $_POST['club_code'] ."'
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
			$_processing_text .= " โดยชุมนุม/กิจกรรมที่มีการบันทึก คือ " . $_POST['club_code'] . ' ' . $_POST['club_name'];
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
				<span class="normal"><font color="#0066FF"><strong>4.5 จัดการลงทะเบียนเรียนกิจกรรมพัฒนาผู้เรียนแบบคละห้อง</strong></font></span>
			</td>
			<td>
				<form method="post">
					<? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; } ?>
					ปีการศึกษา<?php  
							echo "<a href=\"index.php?option=module_curriculum/RegisterStudentClub&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo '<font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/RegisterStudentClub&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
						ภาคเรียนที่
							<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
									else {
										echo " <a href=\"index.php?option=module_curriculum/RegisterStudentClub&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
									}
									if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
									else {
										echo " <a href=\"index.php?option=module_curriculum/RegisterStudentClub&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
									}
								?>
					</font><br/>
					<font  size="2" color="#000000">เลือกรายวิชา
					<?php 
							$_sql_club = "";
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
												left join curriculum_subjects s on (r.SubjectCode = s.SubjectCode and r.club_code = '000')
											where 
												r.acadyear       = '" . $acadyear . "' and 
												r.acadsemester   = '" . $acadsemester . "' and
												r.is_split_class = '1' and
												s.SubjectType    = 'กิจกรรมพัฒนาผู้เรียน'
											order by
												r.subject_level,
												convert(r.SubjectCode using tis620)
										";
							$_res_subject_info = mysqli_query($_connection,$_sql_subject_info);	

							$_subject = array();
							$_yearth  = 0;
							$_level   = 0;

							if(isset($_POST['subject_info']) && $_POST['subject_info'] != ""){
								$_subject = explode("|", $_POST['subject_info']);

								if($_subject[0]>3){
									$_level  = 4;
									$_yearth = $_subject[0]-3;
								}else{
									$_level  = 3;
									$_yearth = $_subject[0];
								}
								$_sql_club = "
											select 
												c.club_code,
												c.club_name,
												c.club_level,
												c.club_status,
												t.teacher_register_id,
												t.SubjectCode,
												t.acadyear,
												t.acadsemester,
												t.subject_register_id,
												concat(t.subject_level,'|',c.club_code,'|',t.teacher_register_id,'|',t.teacher_id,'|',c.club_name) as club_info
											from 
												curriculum_clubs c left join register_teachers t
												on (
													c.club_code    = t.club_code and
													t.acadyear     = '" . $acadyear . "' and
													t.SubjectCode  = '" . $_subject[1] . "' and
													t.acadsemester = '" . $acadsemester . "' 
													)
											where
												1=1 
												and c.club_level in ('0','" . $_level . "') 
												and t.subject_register_id IS NOT NULL
											order by 
													convert(c.club_name using tis620),
													c.club_code	";
							}							
							
					?>
					
						<select name="subject_info" class="inputboxUpdate" onchange="this.form.submit()">
							<option value=""></option>
							<? while($_sub = mysqli_fetch_assoc($_res_subject_info)) {
									$_select = (isset($_POST['subject_info'])&& $_POST['subject_info'] == $_sub['subject_info']?"selected":"");
									echo "<option value=\"" . $_sub['subject_info'] . "\" $_select>";
									echo $_sub['SubjectCode']. ' ' . $_sub['SubjectName'] . ' ชั้น ม.' . $_sub['subject_level'];
									echo "</option>";
								}
							?>
						</select>
						<br/>

						<? if(isset($_POST['subject_info']) && $_POST['subject_info'] != ""){ ?>
							<? $_res_club = mysqli_query($_connection,$_sql_club); ?>
							เลือกชุมนุม 
							<select name="club_info" class="inputboxUpdate" onchange="this.form.submit()">
								<option value=""></option>
								<? while($_club = mysqli_fetch_assoc($_res_club)) { ?>
									<? $_select = (isset($_POST['club_info']) && $_POST['club_info'] == $_club['club_info']?"selected":""); ?>
									<option value="<?=$_club['club_info']?>" <?=$_select?> >
										<?=$_club['club_code'] . ' ' . $_club['club_name']?>
									</option>
								<? } ?>
							</select>
						<? } ?>
						<br/>

						<input type="checkbox" name="registered" value="OK"  
							   <?=isset($_POST['registered'])=="OK"?"checked='checked'":"";?>
							   onchange="this.form.submit()" />
						เฉพาะนักเรียนที่ลงทะเบียนเรียนชุมนุม/กิจกรรมนี้
					
					</font>
				</form>
			</td>
		</tr>
	</table>


<?
	  $_xlevel  = 0;
	  $_xyearth = 0;

 ?>
	<? if(isset($_POST['club_info']) && $_POST['club_info'] == ""){ ?>
		<center><br/><font color="#FF0000">กรุณาเลือกรายวิชาก่อน !</font></center>
	<? }//end if ตรวจสอบรายวิชา ?>

	<? if(isset($_POST['club_info']) && $_POST['club_info'] != ""){ ?>
		<?php
			// ex - 1|0022|610|00000000-0000-0000-0000-000000000000|กีฬาฟุตซอล
			$_club_info = explode("|", $_POST['club_info']);


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
								r.register_student_id,
								r.club_code,
								c.club_name
							from 
								students s left join register_students r
								on (
									 s.id           = r.student_id and
									 s.xedbe        = r.acadyear and
									 r.SubjectCode  = '" . $_sub_info[1] . "' and
									 r.acadsemester = '" . $acadsemester . "' and
									 r.acadyear     = '" . $acadyear ."'
								    )
								left join curriculum_clubs c
								on (
									 r.club_code = c.club_code
								    )
							where 
								s.xlevel  = '". $_xlevel . "' and 
								s.xyearth = '" . $_xyearth . "' and 
								s.xedbe   = '" . $acadyear . "' and 
								s.studstatus in (1,2) 
							";
			$_sql_student .= "";

			if(isset($_POST['registered'])=="OK") $_sql_student .= " and r.club_code = '" . $_club_info[1] . "'";

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
								สำหรับการจัดการลงทะเบียนเรียน รายวิชา 
								<font color='green'><?=$_sub_info[1]?> : <?=$_sub_info[4]?></font> <br/>
								เพื่อลงทะเบียนเรียน ชุมนุม/กิจกรรม 
								<font color='green'><?=$_club_info[1]. ': ' . $_club_info[4]?></font>
								</b>
							</td>
						</tr>
						<tr>
							<td width="40px" align="center" class="key">เลขที่</td>
							<td width="75px" align="center" class="key">เลขประจำตัว</td>
							<td  align="center" class="key">ชื่อ - นามสกุล</td>
							<td align="center" width="70px" class="key">ชื่อเล่น</td>
							<td align="center" width="60px" class="key">ห้อง</td>
							<td width="100px"  align="center" class="key">สถานภาพปัจจุบัน</td>
							<td width="190px" align="center" class="key">การลงทะเบียน ชุมนุม/กิจกรรม</td>
							<td align="center" width="40px" class="key"><input type='checkbox' name='checkall' onclick='checkedAll(<?=mysqli_num_rows($_res_student)?>);' ></td>
						</tr>
						<? $_i = 1;?>
						<? while($_dat = mysqli_fetch_assoc($_res_student)){ ?>
							<?php
								$_tr_bg = "";
								$_check_box_status = "";

								if($_dat['club_code'] != "" && $_dat['club_code']==$_club_info[1]){
									// editable because club_code = searching command
									$_tr_bg = "bgcolor='#FFFF99'";
									$_check_box_status = "checked";
								}else if($_dat['club_code'] != "" && $_dat['club_code']!=$_club_info[1]){
									// uneditable
									$_tr_bg = "bgcolor='#F2F4F4'";
									$_check_box_status = "disabled";
								}
							?>
						<tr id="row<?=$_i?>" <?=$_tr_bg?>  >
							<td align="center"><?=$_i?></td>
							<td align="center"><?=$_dat['id']?></td>
							<td><?=$_dat['prefix'].$_dat['firstname']. ' ' .$_dat['lastname']?></td>
							<td align="center"><?=$_dat['nickname']!=""?$_dat['nickname']:"-"?></td>
							<td align="center"><?=$_sub_info[0].'/'.$_dat['room']?></td>
							<td align="center"><?=displayStudentStatusColor($_dat['studstatus'])?></td>
							<td align="left">
								<?php
									if($_dat['student_id']!=""){
										echo $_dat['club_code'] . ' ' . $_dat['club_name'];
									}else{
										echo " -";
									}

									$_check_box_value = "";
									$_check_box_value = $_dat['id'] . "|" . $_dat['register_student_id'] . "";

								?>
							</td>
							<td align="center">
								<input type="hidden"   name="student[<?=$_i?>]" value="<?=$_check_box_value?>" />
								<input type="checkbox" name="selected_student[<?=$_i?>]" 
									   id="chk<?=$_i?>" value="<?=$_check_box_value?>" 
									   <?=$_check_box_status?>
									   onclick="changeBackgroundColor('row<?=$_i?>',<?=$_i++?>)" />
							</td>
						</tr>
						<? } //end while?>
						<tr height="50px">
							<td colspan="7" align="center">
								<input type="hidden" name="acadyear"            value="<?=$acadyear?>" />
								<input type="hidden" name="acadsemester"        value="<?=$acadsemester?>" />
								<input type="hidden" name="counter"             value="<?=$_i?>" />
								<input type="hidden" name="club_code"           value="<?=$_club_info[1]?>" />
								<input type="hidden" name="club_name"           value="<?=$_club_info[4]?>" />
								<input type="hidden" name="SubjectCode"         value="<?=$_sub_info[1]?>" />
								<input type="hidden" name="SubjectName"         value="<?=$_sub_info[4]?>" />
								<input type="hidden" name="subject_register_id" value="<?=$_sub_info[2]?>" />
								<input type="hidden" name="search"              value=""/>
								<input type="hidden" name="subject_info"        value="<?=$_POST['subject_info']?>" />
								<input type="hidden" name="club_info"           value="<?=$_POST['club_info']?>" />
								<input type="submit" name="save"                value="บันทึก" class="button" />
								<input type="button" value="ยกเลิก" class="button" onclick="location.href='index.php?option=module_curriculum/RegisterStudentClub'" />
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


