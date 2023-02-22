<link rel="stylesheet" type="text/css" href="module_moral/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_moral/js/calendar.js"></script>
<SCRIPT language="javascript" type="text/javascript">


	  function checkValue(){
		if(document.getElementById('absent_type').value == "")
			{ alert('กรุณาเลือก การขออนุญาต ก่อน'); document.getElementById('absent_type').focus(); return;}

		if(document.getElementById('start_absent_date').value == "")
			{ alert('กรุณาป้อนข้อมูล วันที่เริ่มลา ก่อน'); document.getElementById('start_absent_date').focus(); return;}

		if(document.getElementById('end_absent_date').value == "")
			{ alert('กรุณาป้อนข้อมูล วันที่สิ้นสุดการลา ก่อน'); document.getElementById('end_absent_date').focus(); return;}

		if(Number(document.getElementById('total_absent').value) <= 0.0)
			{ alert('กรุณาป้อนข้อมูล จำนวนระยะเวลาที่ลา ก่อน'); document.getElementById('total_absent').focus(); return; }
		

		if(document.getElementById('absent_details').value == "")
			{ alert('กรุณาป้อนข้อมูล รายละเอียดประกอบการลา ก่อน'); document.getElementById('absent_details').focus(); return; }
		
		const start_date = new Date(document.getElementById('start_absent_date').value);
		const end_date   = new Date(document.getElementById('end_absent_date').value);

		if(start_date > end_date)
		{
			alert('คุณป้อนข้อมูลไม่ถูกต้อง วันที่เริ่มลา มากกว่า วันสิ้นสุดการลา กรุณาตรวจสอบอีกครั้ง'); 
			document.getElementById('start_absent_date').focus(); return;
		}
		
		if(document.getElementById('contact_information').value == "")
		{ 
			alert('กรุณาป้อนข้อมูล ผู้ติดต่อและเบอร์โทรก่อน ก่อน'); 
			document.getElementById('contact_information').focus(); return;  
		}
		else 
		{ 
			document.myform.submit(); 
		}
	  }
	  
	  
	function checkFile(f,e) {

		var file_list = e.target.files;
	
		for (var i = 0, file; file = file_list[i]; i++) {
			var sFileName = file.name;
			var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
			var iFileSize = file.size;
			var iConvert = (file.size / 1048576).toFixed(2);
	
			if (!((sFileExtension === "jpg") || (sFileExtension === "png")) || iFileSize > 1048576) {
				txt = "พบข้อผิดพลาดในการเลือกไฟล์ \n\n";
				txt += " - ประเภทไฟล์ : " + sFileExtension + "\n";
				txt += " - ขนาด: " + iConvert + " MB \n";
				txt += "โปรดตรวจสอบไฟล์อีกครั้งว่ามีประเภทไฟล์ (นามสกุล เป็น .jpg หรือ .png) และขนาดไม่เกิน 1 MB หรือไม่ \n\n";
				alert(txt);
				f.value = "";
			}
		}
	}
		  
	  
</SCRIPT>



<?php

	$_target_acadyear = $_target . $_hr_absent_folder . "/" . $acadyear;
	$_target_semester = $_target_acadyear . "/" . $acadsemester;

	$_file_1_name = "";
	$_file_2_name = "";

	if (!file_exists($_target_acadyear)) {
		mkdir($_target_acadyear, 0755);
	}

	if(!file_exists($_target_semester)){
		mkdir($_target_semester,0755);
	}

	$_target = $_target_semester;

	$_processing_text = "";
	$_processing_result = false;

	$_processing_upload_1 = "";
	$_processing_upload_2 = "";

	$_text = "";

	//$_hr_absent_folder ;


	//-- processing -- //
	if(isset($_POST['ReadyforSave'])){
		
		$_absent_id = gen_uuid();

		$_prefix_file = $_POST['start_absent_date'] . "_" . $_POST['teacher_code'] . "_" . $_absent_id;

		$_reqest_date = "";

		if(strtotime($_POST['start_absent_date']) < strtotime(date('Y-m-d'))){
			$_reqest_date = "'" . $_POST['start_absent_date'] . "'";
		}else{
			$_reqest_date = "CURRENT_TIMESTAMP";
		}
		// file processing
		$_allowFileType = ['image/jpeg','image/png'];

		$ext = "";
		if(isset($_FILES['file_1']) && $_FILES["file_1"]["name"] != ""){
			
			$_file_1_name = $_prefix_file . "_absent_f_01" ;

			if(in_array($_FILES["file_1"]["type"],$_allowFileType)) {

				$_target_file = $_target . "/" . $_FILES["file_1"]["name"];
				move_uploaded_file($_FILES["file_1"]["tmp_name"],$_target_file);

				if(file_exists($_target_file)){
					$ext = pathinfo($_target_file, PATHINFO_EXTENSION);
				}

				$_f = $_target . "/" . $_file_1_name . "." . $ext;
				
				if(file_exists($_target_file)){
					if(file_exists($_f)){
						unlink($_f);
					}
					if(rename($_target_file,$_f)){
						$_processing_upload_1 = "อัพโหลดไฟล์แนบ 1 เรียบร้อยแล้ว";
					}
				}
				$_file_1_name = str_replace($_target_semester."/","",$_f);
			}
		}

		if(isset($_FILES['file_2']) && $_FILES["file_2"]["name"] != ""){

			$_file_2_name = $_prefix_file . "_absent_f_02" ;

			if(in_array($_FILES["file_2"]["type"],$_allowFileType)) {

				$_target_file = $_target . "/" . $_FILES["file_2"]["name"];
				move_uploaded_file($_FILES["file_2"]["tmp_name"],$_target_file);

				if(file_exists($_target_file)){
					$ext = pathinfo($_target_file, PATHINFO_EXTENSION);
				}

				$_f = $_target . "/" . $_file_2_name . "." . $ext;
				
				if(file_exists($_target_file)){
					if(file_exists($_f)){
						unlink($_f);
					}
					if(rename($_target_file,$_f)){
						$_processing_upload_2 = "อัพโหลดไฟล์แนบ 2 เรียบร้อยแล้ว";
					}
				}
				$_file_2_name = str_replace($_target_semester."/","",$_f);
			}
		}

		$_sql_absent = "
				INSERT INTO `hr_staff_absent`(
					`absent_id`,
					`staff_id`,
					`start_absent_date`,
					`start_absent_time`,
					`end_absent_date`,
					`end_absent_time`,
					`total_absent`,
					`absent_type`,
					`absent_subtype`,
					`absent_details`,
					`contact_information`,
					`request_datetime`,
					`request_status`,
					`acadyear`,
					`acadsemester`,
					`file_attached_ext1`,
					`file_attached_ext2`,
					`created_datetime`,
					`created_user`,
					`updated_datetime`,
					`updated_user`
				)
				VALUES(
					'" . $_absent_id ."',
					'" . $_POST['staff_id'] ."',
					'" . $_POST['start_absent_date'] ."',
					'" . $_POST['start_absent_time'] ."',
					'" . $_POST['end_absent_date'] ."',
					'" . $_POST['end_absent_time'] ."',
					'" . $_POST['total_absent'] ."',
					'" . $_POST['absent_type'] ."',
					'" . $_POST['absent_type'] ."',
					'" . trim($_POST['absent_details']) ."',
					'" . trim($_POST['contact_information']) ."',
					"  . $_reqest_date . " ,
					'ส่งคำขอแล้ว',
					'" . $acadyear . "',
					'" . $acadsemester . "',
					'" . $_file_1_name . "',
					'" . $_file_2_name . "',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "'
				)
			";
			 //echo $_sql_absent;
			$_resInsert = mysqli_query($_connection,$_sql_absent);
			if($_resInsert){

				$_processing_text = "ท่านได้ทำการบันทึกข้อมูลการขออนุญาต <b>" . $_POST['absent_type'] . "</b> เรียบร้อยแล้ว";
				$_processing_result = true;

				// line message here
				$_text .= "มีการยื่นคำขอ: " . $_POST['absent_type'] . " เข้ามาใหม่";
				$_text .= "\n" . "โดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token_module_hr);
				SendLineMessage($message,$_line_token);

			}else{
				$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึก คำยื่นขอลา ได้" . "<br/>";
				$_processing_text .= "กรุณาลองบันทึกข้อมูลใหม่อีกครั้ง หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
			}

	}

?>





<?php
	
	$_staff_id = "";
	if(isset($_POST['staff_id']) && $_POST['staff_id'] != ""){
		$_staff_id = $_POST['staff_id'];
	}else{
		$_staff_id = $_SESSION['user_account_id'];
	}

?>


<div id="content">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
			<tr>
				<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48px" border="0" /></a></td>
				<td >
					<strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.1 บันทึกการขออนุญาตลา</strong></font></span></td>
				<td>
					<font  size="2" color="#000000">
						<form name="sSelect" method="post" action="">
						<? if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc100" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103" || $_SESSION['username'] == "tc121") { ?>
						เลือกบุคลากร
							<?php 
									$sql_teacher = " 
											select
												*
											from
												hr_staff s
											where 
												1=1
												and staff_status = 'ACTIVE'
											order by convert(s.firstname using tis620), convert(s.lastname using tis620)
										";
									//echo $sql_Room ;
									$resTeacher = mysqli_query($_connection,$sql_teacher);	
									$_submit_teacher_name = "";		
							?>
						
							<select name="staff_id" class="inputboxUpdate" onChange="document.sSelect.submit();">
								<option value=""></option>
								<?php
									$_select = "";
									while($dat = mysqli_fetch_assoc($resTeacher))
									{
										$_select = ($_staff_id == $dat['staff_id']?"selected":"");
										echo "<option value=\"" . $dat['staff_id'] . "\" $_select>";
										echo $dat['firstname']. ' ' . $dat['lastname'];
										echo "</option>";
									}
									
								?>
							</select>
							<? } else { ?> 
								<input type="hidden" name="staff_id" value=<?=$_SESSION['user_account_id']?> />
							<? } ?>
						</form>
					</font>
				</td>
			</tr>
		</table>

		<? if(isset($_POST['ReadyforSave'])){ ?>
			<div align="center">
				<table>
					<tr>
						<td>
							<br/>
							<?php
								if($_processing_result){
									echo "<font color='green'>";
									echo $_processing_text;
									echo ($_processing_upload_1 != "" ? "<br/>" . $_processing_upload_1 : "");
									echo ($_processing_upload_2 != "" ? "<br/>" . $_processing_upload_2 : "");
									echo "</font>";
								}else{
									echo "<font color='red'>";
									echo $_processing_text;
									echo "</font>";
								}
								echo "<br/><br/>"
							?>
						</td>
					</tr>
					<tr>
						<td>
						<? if($_processing_result){ ?>
							คลิกที่ลิงก์เพื่อแสดงรายละเอียดการบันทึกคำขออนุญาตลา <br/><br/>
							<center>
							<b>
								<a href="index.php?option=module_hr/RequestAbsentDetail&absent_id=<?=$_absent_id?>">
								แสดงรายละเอียด
								</a>
							</b>
							</center>
						<? } ?>
						<br/><br/>
						</td>
					</tr>
				</table>
			</div>
		<? } else { // end-if ?>
	<?php
		$_sql_initial = "
				SELECT
					s.*
				FROM
					hr_staff s
				WHERE
					s.staff_id = '" . $_staff_id . "' 
			";
		$_res_initial = mysqli_query($_connection,$_sql_initial);
		$_dat = mysqli_fetch_assoc($_res_initial);
	?>

	


	<div align="center">
		<form method="post" name="myform" enctype="multipart/form-data" autocomplete="off">
			<table class="admintable">
				<tr><td class="key" colspan="3">รายละเอียดการบันทึก </td></tr>
				<tr>
					<td align="right" width="200px">ชื่อบุคลากร :</td>
					<td><input class="noborder2" type="text" size="40" readonly="true" name="staff_name" value="<?=$_dat['prefix'].$_dat['firstname'].' '.$_dat['lastname']?>"/></td>
				<tr>
					<td align="right">ตำแหน่ง :</td>
					<td><input class="noborder2" type="text" size="40" readonly="true" name="position" value="<?=$_dat['position']?>"/></td>
				</tr>
				<tr>
					<td align="right">ขออนุญาตลา :</td>
					<td>
						<select name="absent_type" class="inputboxUpdate" id="absent_type">
							<option value=""></option>
							<option value="ลาป่วย">ลาป่วย</option>
							<option value="ลากิจ">ลากิจ</option>
							<option value="ลาคลอด">ลาคลอด</option>
						</select> <font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td align="right">วันที่เริ่มลา :</td>
					<td>
						<input class="noborder2" type="text" id="start_absent_date" name="start_absent_date" size="10" onClick="showCalendar(this.id)"/>
						<select name="start_absent_time" class="inputboxUpdate">
							<option value="08:30">08:30</option>
							<option value="13:00">13:00</option>
						</select>
						<font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td align="right">วันที่สิ้นสุดการลา :</td>
					<td>
						<input class="noborder2" type="text" id="end_absent_date" name="end_absent_date" size="10" onClick="showCalendar(this.id)"/>
						<select name="end_absent_time" class="inputboxUpdate">
							<option value="12:00">12:00</option>
							<option selected value="16:30">16:30</option>
						</select>
						<font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td align="right">รวมระยะการลา :</td>
					<td><input type="text" name="total_absent" id="total_absent" class="noborder2" value="0" size="3" maxlength="4" onKeyPress="return isDecimalKey(this,event)"/> วัน <font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right" valign="top">รายละเอียดประกอบการลา :</td>
					<td><textarea class="inputboxUpdate" name="absent_details" id="absent_details" rows="4" cols="60"></textarea><font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right">ชื่อผู้ติดต่อระหว่างลา:</td>
					<td><input class="noborder2" type="text" name="contact_information" id="contact_information" size="50"/><font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 1 (ถ้ามี)</td>
					<td>
						<input type="file" name="file_1" id="file_1" size="60px" accept=".jpg, .png" onchange="checkFile(this,event);"/>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 2 (ถ้ามี)</td>
					<td>
						<input type="file" name="file_2" id="file_2" size="60px" accept=".jpg, .png" onchange="checkFile(this,event);"/><br/>
						ไฟล์ต้องมีรูปแบบไฟล์เป็น .jpg หรือ .png และขนาดไม่ควรเกิน 1 MB<br/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="hidden" name="staff_id" value="<?=$_staff_id?>" />
						<input type="hidden" name="teacher_code" value="<?=$_dat['TeacCode']?>" />
						<input type="hidden" name="ReadyforSave" /> 
						<input type="button" value="บันทึก" class="button" onclick="checkValue();" />
						<input type="button" value="ยกเลิก" class="button" onClick="location.href='index.php?option=module_hr/index';"/> 
					</td>
				</tr>
			</table>
		</form>
	</div>
  <? } // end else ?>
</div>


