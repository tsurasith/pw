<link rel="stylesheet" type="text/css" href="module_moral/css/calendar-mos2.css"/>
<style>
	#image1 {
		display:none;
		width:700px;
	}
	#image2 {
		display:none;
		width:700px;
	}
</style>

<script>
	function show1(){
		if(document.getElementById('btn1').value == "แสดงรูปภาพ"){
			document.getElementById('image1').style.display = "block";
			document.getElementById('btn1').value = "ซ่อนรูปภาพ";
		}else{
			document.getElementById('image1').style.display = "none";
			document.getElementById('btn1').value = "แสดงรูปภาพ";
		}
	}

	function show2(){
		if(document.getElementById('btn2').value == "แสดงรูปภาพ"){
			document.getElementById('image2').style.display = "block";
			document.getElementById('btn2').value = "ซ่อนรูปภาพ";
		}else{
			document.getElementById('image2').style.display = "none";
			document.getElementById('btn2').value = "แสดงรูปภาพ";
		}
	}
</script>

<script language="JavaScript" type="text/javascript" src="module_moral/js/calendar.js"></script>
<SCRIPT language="javascript" type="text/javascript">


	  function checkValue(){
		if(document.getElementById('absent_type').value == "")
			{ alert('กรุณาเลือก การขออนุญาต'); document.getElementById('absent_type').focus(); return;}

		if(document.getElementById('start_absent_date').value == "")
			{ alert('กรุณาป้อนข้อมูล วันที่เริ่มลา/ไปราชการ'); document.getElementById('start_absent_date').focus(); return;}

		if(document.getElementById('end_absent_date').value == "")
			{ alert('กรุณาป้อนข้อมูล วันที่สิ้นสุดการลา/ไปราชการ'); document.getElementById('end_absent_date').focus(); return;}

		if(Number(document.getElementById('total_absent').value) <= 0.0)
			{ alert('กรุณาป้อนข้อมูล จำนวนระยะเวลาที่ลา/ไปราชการ'); document.getElementById('total_absent').focus(); return; }
		

		if(document.getElementById('absent_details').value == "")
			{ alert('กรุณาป้อนข้อมูล รายละเอียดประกอบการลา/ไปราชการ'); document.getElementById('absent_details').focus(); return; }
		
		const start_date = new Date(document.getElementById('start_absent_date').value);
		const end_date   = new Date(document.getElementById('end_absent_date').value);

		if(start_date > end_date)
		{
			alert('คุณป้อนข้อมูลไม่ถูกต้อง วันที่เริ่มลา มากกว่า วันสิ้นสุดการลา กรุณาตรวจสอบอีกครั้ง'); 
			document.getElementById('start_absent_date').focus(); return;
		}
		
		if(document.getElementById('contact_information').value == "")
		{ 
			alert('กรุณาป้อนข้อมูล ผู้ติดต่อและเบอร์โทร หรือ เลขที่บันทึกข้อความ'); 
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
	

	$_absent_id = "";
	if(isset($_POST['absent_id'])){
		$_absent_id = $_POST['absent_id'];
	}

?>

<?php

	$_target_display = $_target;

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
		
		$_prefix_file = $_POST['start_absent_date'] . "_" . $_POST['teacher_code'] . "_" . $_absent_id;

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

		$_absent_type_update = "";
		if(!in_array($_POST['absent_type'],array("ลาป่วย","ลากิจ","ลาคลอด"))) { 
			$_absent_type_update = "ไปราชการ";
		}else{
			$_absent_type_update = $_POST['absent_type'];
		}

		$_sql_absent = "
				
				UPDATE
					`hr_staff_absent`
				SET
					`start_absent_date`   = '" . $_POST['start_absent_date'] ."',
					`start_absent_time`   = '" . $_POST['start_absent_time'] ."',
					`end_absent_date`     = '" . $_POST['end_absent_date'] ."',
					`end_absent_time`     = '" . $_POST['end_absent_time'] ."',
					`total_absent`        = '" . $_POST['total_absent'] ."',
					`absent_type`         = '" . $_absent_type_update ."',
					`absent_subtype`      = '" . $_POST['absent_type'] ."',
					`absent_details`      = '" . trim($_POST['absent_details']) ."',
					`contact_information` = '" . trim($_POST['contact_information']) ."',
					`updated_datetime`    = CURRENT_TIMESTAMP,
					`updated_user`        = '" . $_SESSION['user_account_id'] . "' ";
		if($_file_1_name != ""){
			$_sql_absent .= ", `file_attached_ext1` = '" . $_file_1_name . "' ";
		}
		if($_file_2_name != ""){
			$_sql_absent .= ", `file_attached_ext2` = '" . $_file_2_name . "' ";
		}
					
		$_sql_absent .= " WHERE
					`absent_id` = '" . $_absent_id  . "'
			";
			 //echo $_sql_absent;
			$_resInsert = mysqli_query($_connection,$_sql_absent);
			if($_resInsert){

				$_processing_text = "ท่านได้ทำการบันทึกแก้ไขข้อมูลการขออนุญาต <b>" . $_POST['absent_type'] . "</b> เรียบร้อยแล้ว";
				$_processing_result = true;

				// line message here
				$_text .= "มีการแก้ไขคำขออนุญาต: " . $_POST['absent_type'] . " เข้ามาใหม่";
				$_text .= "\n" . "โดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token);

			}else{
				$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกแก้ไข คำยื่นขอลา ได้" . "<br/>";
				$_processing_text .= "กรุณาลองบันทึกข้อมูลใหม่อีกครั้ง หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
			}

	}

?>

<?php
		$_sql_initial = "
				select 
				s.prefix,
				s.firstname,
				s.lastname,
				s.position,
				s.TeacCode,
				h.*
			from
				hr_staff_absent h inner join hr_staff s
				on (
					h.staff_id = s.staff_id
				) 
			where
				h.absent_id = '" . $_absent_id . "'
			";
		$_res_initial = mysqli_query($_connection,$_sql_initial);
		$_row_data = mysqli_num_rows($_res_initial);
		$_dat = mysqli_fetch_assoc($_res_initial);
	?>




<div id="content">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
			<tr>
				<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48px" border="0" /></a></td>
				<td >
					<strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.4 ตรวจสอบแก้ไขการขออนุญาตลา/ไปราชการ ก่อนส่งต่อให้ผู้บริหาร</strong></font></span></td>
				<td>
					
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

	<div align="center">
		<form method="post" name="myform" enctype="multipart/form-data" autocomplete="off">
			<table class="admintable">
				<tr><td class="key" colspan="3">รายละเอียดการบันทึก </td></tr>
				<tr>
					<td align="right" width="200px">ชื่อบุคลากร :</td>
					<td>
						<?=$_dat['prefix'].$_dat['firstname'].' '.$_dat['lastname']?>
					</td>
				<tr>
					<td align="right">ตำแหน่ง :</td>
					<td>
						<?=$_dat['position']?>
					</td>
				</tr>
				<? if(in_array($_dat['absent_type'],array("ลาป่วย","ลากิจ","ลาคลอด"))) { ?>
				<tr>
					<td align="right">ขออนุญาตลา :</td>
					<td>
						<select name="absent_type" class="inputboxUpdate" id="absent_type">
							<option value=""       ></option>
							<option value="ลาป่วย"  <?=$_dat['absent_subtype']=="ลาป่วย"?"selected":""?>  >ลาป่วย</option>
							<option value="ลากิจ"   <?=$_dat['absent_subtype']=="ลากิจ"?"selected":""?>   >ลากิจ</option>
							<option value="ลาคลอด" <?=$_dat['absent_subtype']=="ลาคลอด"?"selected":""?> >ลาคลอด</option>
						</select> <font color="red">*</font>
					</td>
				</tr>
				<? }else { ?>
				<tr>
					<td align="right">ขออนุญาตไปราชการ :</td>
					<td>
						<select name="absent_type" class="inputboxUpdate" id="absent_type">
							<option value=""></option>
							<option value="ประชุม"			<?=$_dat['absent_subtype']=="ประชุม"?"selected":""?>  >ประชุม</option>
							<option value="อบรม"		   <?=$_dat['absent_subtype']=="อบรม"?"selected":""?>  >อบรม</option>
							<option value="ควบคุมนักเรียน"    <?=$_dat['absent_subtype']=="ควบคุมนักเรียน"?"selected":""?>  >ควบคุมนักเรียน</option>
							<option value="ศึกษาดูงาน"       <?=$_dat['absent_subtype']=="ศึกษาดูงาน"?"selected":""?>  >ศึกษาดูงาน</option>
						</select> <font color="red">*</font>
					</td>
				</tr>
				<? } ?>
				<tr>
					<td align="right">
						<?php
							if(in_array($_dat['absent_type'],array("ลาป่วย","ลากิจ","ลาคลอด"))) {
								echo "วันที่เริ่มลา :";
							}else{
								echo "วันที่เริ่มขออนุญาต : ";
							}
						?>
					</td>
					<td>
						<input  class="noborder2" type="text" id="start_absent_date" 
								name="start_absent_date" size="10" onClick="showCalendar(this.id)"
								value="<?=$_dat['start_absent_date']?>" />

						<select name="start_absent_time" class="inputboxUpdate">
							<option value="08:30" <?=$_dat['start_absent_time']=="08:30"?"selected":""?> >08:30</option>
							<option value="13:00" <?=$_dat['start_absent_time']=="13:00"?"selected":""?> >13:00</option>
						</select>
						<font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td align="right">
						<?php
							if(in_array($_dat['absent_type'],array("ลาป่วย","ลากิจ","ลาคลอด"))) {
								echo "วันที่สิ้นสุดการลา :";
							}else{
								echo "วันที่สิ้นสุดการขออนุญาต : ";
							}
						?>
					</td>
					<td>
						<input  class="noborder2" type="text" id="end_absent_date" 
								name="end_absent_date" size="10" onClick="showCalendar(this.id)"
								value="<?=$_dat['end_absent_date']?>" />

						<select name="end_absent_time" class="inputboxUpdate">
							<option value="12:00" <?=$_dat['end_absent_time']=="12:00"?"selected":""?> >12:00</option>
							<option value="16:30" <?=$_dat['end_absent_time']=="16:30"?"selected":""?> >16:30</option>
						</select>
						<font color="red">*</font>
					</td>
				</tr>
				<tr>
					<td align="right">รวมระยะการลา :</td>
					<td>
						<input  type="text" name="total_absent" id="total_absent" class="noborder2" 
								value="<?=$_dat['total_absent']?>" size="3" maxlength="4" 
								onKeyPress="return isDecimalKey(this,event)"/> วัน <font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right" valign="top">
						<?php
							if(in_array($_dat['absent_type'],array("ลาป่วย","ลากิจ","ลาคลอด"))) {
								echo "รายละเอียดประกอบการลา :";
							}else{
								echo "รายละเอียดเพิ่มเติม<br/>สำหรับการขออนุญาต : ";
							}
						?>
					</td>
					<td><textarea class="inputboxUpdate" name="absent_details" id="absent_details" rows="4" cols="60"><?=$_dat['absent_details']?></textarea><font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right">
						<?php
							if(in_array($_dat['absent_type'],array("ลาป่วย","ลากิจ","ลาคลอด"))) {
								echo "ชื่อผู้ติดต่อระหว่างลา :";
							}else{
								echo "เลขที่บันทึกข้อความ : ";
							}
						?>
					</td>
					<td>
						<input  class="noborder2" type="text" name="contact_information" id="contact_information" 
								value="<?=$_dat['contact_information']?>" size="50"/><font color="red">*</font></td>
				</tr>

				<?php
					
					
					
					$_img_1  = ".." . $_hr_img_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_img_1 .= $_dat['file_attached_ext1'];

					$_full_path_1  = $_target_display . $_hr_absent_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_full_path_1 .= $_dat['file_attached_ext1'];



					$_img_2  = ".." . $_hr_img_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_img_2 .= $_dat['file_attached_ext2'];

					$_full_path_2  = $_target_display . $_hr_absent_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_full_path_2 .= $_dat['file_attached_ext2'];



				?>
				<? if($_dat['file_attached_ext1']!="" && file_exists($_full_path_1)){ ?> 
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 1</td>
					<td><input type="button" id="btn1" name="button1" value="แสดงรูปภาพ" onClick="show1()" /></td>
				</tr>
				<tr>
					<td align="center" valign="top" colspan="2">
						<img id="image1" src="<?=$_img_1?>" width="85%" />
					</td>
				</tr>
				<? }else{ ?>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 1</td>
					<td>ไม่มี</td>
				</tr>
				<? } ?>
				<? if($_dat['file_attached_ext2']!="" && file_exists($_full_path_2)){ ?> 
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 2</td>
					<td><input type="button" id="btn2" name="button2" value="แสดงรูปภาพ" onClick="show2()" /></td>
				</tr>
				<tr>
					<td align="center" valign="top" colspan="2">
						<img id="image2" src="<?=$_img_2?>" width="85%" />
					</td>
				</tr>
				<? }else{ ?>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 2</td>
					<td>ไม่มี</td>
				</tr>
				<? } ?>


				<tr>
					<td align="right" valign="top">ไฟล์แนบ 1 (ถ้าต้องการแก้ไข)</td>
					<td>
						<input type="file" name="file_1" id="file_1" size="60px" accept=".jpg, .png" onchange="checkFile(this,event);"/>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 2 (ถ้าต้องการแก้ไข)</td>
					<td>
						<input type="file" name="file_2" id="file_2" size="60px" accept=".jpg, .png" onchange="checkFile(this,event);"/><br/>
						<br/>
						<br/>
						หากต้องการแก้รูปภาพ สามารถเลือกไฟล์อัพโหลดและบันทึก ไฟล์ที่อัพโหลดใหม่จะไปแทนที่ไฟล์เดิมก่อนหน้า <br/>
						ไฟล์ต้องมีรูปแบบไฟล์เป็น .jpg หรือ .png และขนาดไม่ควรเกิน 1 MB<br/>
						
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="hidden" name="staff_id" value="<?=$_dat['staff_id']?>" />
						<input type="hidden" name="absent_id" value="<?=$_absent_id?>" />
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


