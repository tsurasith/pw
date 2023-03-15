<?php


	$_target = $_target . $_student_grade_folder . "/";

	$_processing_text = "";
	$_processing_result = false;

	$_text = "";

	$_file_status = "";

	if(isset($_POST['confirm'])){

		$_sql_update = "
				update
					register_students r inner join register_students_grades_import i 
					on 
						(
						r.student_id   = i.student_id and
						r.acadsemester = i.acadsemester and
						r.acadyear     = i.acadyear and 
						r.SubjectCode  = i.SubjectCode and 
						i.import_key   = '" . $_POST['subject_register_id'].$_POST['SubjectCode'].$_POST['room_id'] ."'
						)
				set
					r.grade            = i.grade,
					r.point_100        = i.point_100,
					r.updated_datetime = CURRENT_TIMESTAMP,
					r.updated_user     = '" . $_SESSION['user_account_id'] . "'
				where
					1=1 
					and i.import_key = '" . $_POST['subject_register_id'].$_POST['SubjectCode'].$_POST['room_id'] ."'	
			";
		$_sql_update_file = "
				update 
					`register_teachers_upload_files` 
				set
					import_status = 'ยืนยันผลการเรียนแล้ว'
				where 
					upload_id = '" . $_POST['upload_id'] . "'";

		$_res_update = mysqli_query($_connection,$_sql_update);
		if($_res_update){
			$_processing_result = true;
			$_processing_text = "รายวิชา " . $_POST['SubjectCode'] . ' ' . $_POST['SubjectName'] . ' ยืนยันผบการเรียนเรียบร้อยแล้ว';

			// line message here
			$_text .= "รายวิชา " . $_POST['SubjectCode'] . ': ' . $_POST['SubjectName'];
			$_text .= " ห้อง " . getFullRoomFormat($_POST['room_id']) . " ได้ยืนยันผลการเรียนแล้ว ";
			$_text .= "" . "โดย - " . $_SESSION['shortname'];

			$_res_update_file = mysqli_query($_connection,$_sql_update_file);

			$message = $_text;
			SendLineMessage($message,$_line_token);

		}else{
			$_processing_result = false;
			$_processing_text  = "ไม่สามารถยืนยันผลการเรียน รายวิชา " . $_POST['SubjectCode'] . ' ' . $_POST['SubjectName'];
			$_processing_text .=  " หรือแจ้งข้อความนี้ต่อผู้ดูแลระบบ " . mysqli_error($_connection);
		}

	}

	if(isset($_POST['upload']))
	{
		//print_r($_FILES["student_file"]);

		$_allowFileType = [
			'application/vnd.ms-excel',
			'text/xls',
			'text/xlsx'
		];

		if(in_array($_FILES["student_file"]["type"],$_allowFileType)) {

			$_target_file = $_target . $_FILES["student_file"]["name"];
			move_uploaded_file($_FILES["student_file"]["tmp_name"],$_target_file);

			if(file_exists($_target_file)){
				$_processing_text = "อัพโหลดไฟล์เรียบร้อยแล้ว แต่ยังไม่ได้ทำการประมวลผล";

				// start process XLS here

				require_once "../include/SimpleXLS.php";
				//use Shuchkin\SimpleXLS;

				if ($xls = SimpleXLS::parse($_target_file)) {
					
					//print_r($xls->rows());
					$header_values = $data = array();

					$dd = array();

					foreach ($xls->rows() as $k => $r) {
						if ($k === 0) {
							$header_values = $r;
							continue;
						}
						$data[] = array_combine($header_values, $r);
						array_push($dd,$r);
						/*
						   $dd[][0] = 'รหัส'
						   $dd[][1] = 'คำนำหน้า'
						   $dd[][2] = 'ชื่อ'
						   $dd[][3] = 'นามสกุล'
						   $dd[][4] = 'คะแนน'
						   $dd[][4] = 'เกรด'			
						*/
					}
					// print_r($data);
					$_sql_delete = "
							DELETE FROM 
								register_students_grades_import 
							WHERE 
								import_key = '" . $_POST['subject_register_id'].$_POST['SubjectCode'].$_POST['room_id'] . "'";
					mysqli_query($_connection,$_sql_delete);
					//echo $_sql_delete . "<br/>";

					

					for($_i=0;$_i<count($data);$_i++){

						$_point = "";
						if(is_numeric(trim($dd[$_i][4]))){
							$_point = trim($dd[$_i][4]);
						}else{
							$_point = "0.0";
						}
						$_sql_insert = "
								REPLACE INTO `register_students_grades_import`(
									`import_key`,
									`student_id`,
									`SubjectCode`,
									`acadyear`,
									`acadsemester`,
									`point_100`,
									`grade`,
									`prefix`,
									`firstname`,
									`lastname`,
									`created_datetime`,
									`created_user`,
									`updated_datetime`,
									`updated_user`,
									`subject_register_id`
								)
								VALUES(
									'" . $_POST['subject_register_id'].$_POST['SubjectCode'].$_POST['room_id'] ."',
									'" . trim($dd[$_i]['0']) . "',
									'" . $_POST['SubjectCode'] . "',
									'" . $_POST['acadyear'] . "',
									'" . $_POST['acadsemester'] . "',
									'" . $_point . "',
									'" . trim($dd[$_i][5]) . "',
									'" . trim($dd[$_i][1]) . "',
									'" . trim($dd[$_i][2]) . "',
									'" . trim($dd[$_i][3]) . "',
									CURRENT_TIMESTAMP,
									'" . $_SESSION['user_account_id'] . "',
									CURRENT_TIMESTAMP,
									'" . $_SESSION['user_account_id'] . "',
									'" . $_POST['subject_register_id'] . "'
								)

							";

						// echo $_sql_insert . "<br>";

						$_res = mysqli_query($_connection,$_sql_insert);
						if($_res){
							$_processing_result = true;
							$_processing_text = "ระบบนำเข้าข้อมูลจากไฟล์ MS-Excel สู่ฐานข้อมูลเรียบร้อยรอการประมวลผลต่อไป";
						}else{
							$_processing_result = false;
							$_processing_text = "การนำเข้าข้อมูลจากไฟล์ MS-Excel สู่ฐานข้อมูลผิดพลาด เกิดจาก - " . mysqli_error($_connection);
						}
						
					}
					

				} else {
					//echo SimpleXLS::parseError();
					$_processing_text = "ระบบไม่สามารถนำเข้าข้อมูลได้ กรุณาตรวจสอบรูปแบบไฟล์ที่นำเข้าอีกครั้ง หรือตรวจสอบให้มั่นใจว่าได้ทำการจัดการไฟล์ข้อมูลให้อยู่ในรูปแบบที่ถูกต้อง";
				}
			}
			if($_processing_result){

				// delete existing souce file information
				$_sql_delete_file = "delete from `register_teachers_upload_files` where teacher_register_id = '" . $_POST['teacher_register_id'] . "'";
				mysqli_query($_connection,$_sql_delete_file);

				// insert source file information
				$_sql_insert_file = "
								INSERT INTO `register_teachers_upload_files`(
									`teacher_register_id`,
									`source_file_name`,
									`import_status`,
									`created_datetime`,
									`created_user`,
									`updated_datetime`,
									`updated_user`
								)
								VALUES(
									'" . $_POST['teacher_register_id'] . "',
									'" . $_FILES["student_file"]["name"] . "',
									'อัปโหลดผลการเรียนรอการยืนยัน',
									CURRENT_TIMESTAMP,
									'" . $_SESSION['user_account_id'] . "',
									CURRENT_TIMESTAMP,
									'" . $_SESSION['user_account_id'] . "'
								)
							";
				//echo $_sql_insert_file . "<br/>";
				$_insert_file = mysqli_query($_connection,$_sql_insert_file);

				

				// line message here
				$_text .= "รายวิชา " . $_POST['SubjectCode'] . ': ' . $_POST['SubjectName'];
				$_text .= " ห้อง " . getFullRoomFormat($_POST['room_id']) . " ได้อัปโหลดผลการเรียนแล้ว ";
				$_text .= "" . "โดย - " . $_SESSION['shortname'];

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
			}
		}else{
			$_processing_text = "คุณไม่ได้อัพโหลดไฟล์นามสกุล xls หรือ xlsx อย่างถูกต้อง กรุณาส่งไฟล์ข้อมูลให้ผู้ดูแลระบบตรวจสอบ";
		}
		
	}// --- end upload file
?>

<?php

	$_teacher_register_id = "";
	$_import_key          = $_POST['subject_register_id'].$_POST['SubjectCode'].$_POST['room_id'];


	if(isset($_POST['teacher_register_id'])){
		$_teacher_register_id = $_POST['teacher_register_id'];
	}

	$_sql_check_existing_file = "
				select 
					f.*,
					t.firstname,t.lastname
				from 
					register_teachers_upload_files f left join teachers t 
					on (f.created_user = t.teacher_id)
				where f.teacher_register_id = '" . $_POST['teacher_register_id'] . "'";

	$_res_existing_file = mysqli_query($_connection,$_sql_check_existing_file);
	//echo $_sql_check_existing_file . "<br/>";

	$_check_file_existing = mysqli_num_rows($_res_existing_file);

	if($_check_file_existing > 0){
		$_f = mysqli_fetch_assoc($_res_existing_file);
		$_file_status = $_f['import_status'];
	}

	$xlevel = getXlevel($_POST['room_id']);
	$xyearth= getXyearth($_POST['room_id']);
	$room = getRoom($_POST['room_id']);

	$_sql_import_list = "";

	if($room != 0){
		$_sql_import_list = "
					
				select 
					s.student_id,
					ss.sex,
					ss.prefix,
					ss.firstname,
					ss.lastname,
					concat(((ss.xlevel-3)*3)+xyearth,'/',ss.room) as room_id,
					s.SubjectCode,
					s.point_100,
					s.grade,
					i.student_id as i_student_id,
					i.prefix as i_prefix,
					i.firstname as i_firstname,
					i.lastname as i_lastname,
					i.SubjectCode as i_SubjectCode,
					i.point_100 as i_point_100,
					i.grade as i_grade
				from
					register_students s inner join students ss 
					on (s.student_id = ss.id and s.acadyear = ss.xedbe)
					left join register_students_grades_import i 
					on (
						s.student_id = i.student_id and 
						s.acadsemester = i.acadsemester and 
						s.acadyear = i.acadyear and
						s.SubjectCode = '" . $_POST['SubjectCode'] . "'
						)
				where
					ss.room        = '" . $room . "' and 
					ss.xlevel      = '" . $xlevel . "' and 
					ss.xYearth     = '" . $xyearth . "' and
					s.SubjectCode  = '" . $_POST['SubjectCode'] . "' and
					s.acadyear     = '" . $acadyear . "' and
					s.acadsemester = '" . $acadsemester . "' 
					
				union

				select 
					s.student_id,
					ss.sex,
					ss.prefix,
					ss.firstname,
					ss.lastname,
					concat(((ss.xlevel-3)*3)+xyearth,'/',ss.room) as room_id,
					s.SubjectCode,
					s.point_100,
					s.grade,
					i.student_id as i_student_id,
					i.prefix as i_prefix,
					i.firstname as i_firstname,
					i.lastname as i_lastname,
					i.SubjectCode as i_SubjectCode,
					i.point_100 as i_point_100,
					i.grade as i_grade
				from
					register_students_grades_import i 
					left join students ss 
					on (i.student_id = ss.id and i.acadyear = ss.xedbe)
					left join register_students s
					on (
						 s.student_id = i.student_id and 
						 s.acadsemester = i.acadsemester and 
						 s.acadyear = i.acadyear and
						 s.SubjectCode = '" . $_POST['SubjectCode'] . "'
						)
				where
					i.import_key   = '" . $_import_key . "' and
					i.SubjectCode  = '" . $_POST['SubjectCode'] . "' and 
					i.acadyear     = '" . $acadyear . "' and
					i.acadsemester = '" . $acadsemester . "' 

				order by
				sex,
				convert(firstname using tis620)

			";
	}else{
		$_sql_import_list = "
					
				select 
					s.student_id,
					ss.sex,
					ss.prefix,
					ss.firstname,
					ss.lastname,
					concat(((ss.xlevel-3)*3)+xyearth,'/',ss.room) as room_id,
					s.SubjectCode,
					s.point_100,
					s.grade,
					i.student_id as i_student_id,
					i.prefix as i_prefix,
					i.firstname as i_firstname,
					i.lastname as i_lastname,
					i.SubjectCode as i_SubjectCode,
					i.point_100 as i_point_100,
					i.grade as i_grade
				from
					register_students s inner join students ss 
					on (s.student_id = ss.id and s.acadyear = ss.xedbe)
					left join register_students_grades_import i 
					on (
						 s.student_id = i.student_id and 
						 s.acadsemester = i.acadsemester and 
						 s.acadyear = i.acadyear and
						 s.SubjectCode = '" . $_POST['SubjectCode'] . "'
						)
				where
					ss.xlevel      = '" . $xlevel . "' and 
					ss.xYearth     = '" . $xyearth . "' and
					s.SubjectCode  = '" . $_POST['SubjectCode'] . "' and
					s.acadyear     = '" . $acadyear . "' and
					s.acadsemester = '" . $acadsemester . "' 
					
				union

				select 
					s.student_id,
					ss.sex,
					ss.prefix,
					ss.firstname,
					ss.lastname,
					concat(((ss.xlevel-3)*3)+xyearth,'/',ss.room) as room_id,
					s.SubjectCode,
					s.point_100,
					s.grade,
					i.student_id as i_student_id,
					i.prefix as i_prefix,
					i.firstname as i_firstname,
					i.lastname as i_lastname,
					i.SubjectCode as i_SubjectCode,
					i.point_100 as i_point_100,
					i.grade as i_grade
				from
					register_students_grades_import i 
					left join students ss 
					on (i.student_id = ss.id and i.acadyear = ss.xedbe)
					left join register_students s
					on (
						 s.student_id = i.student_id and 
						 s.acadsemester = i.acadsemester and 
						 s.acadyear = i.acadyear and
						 s.SubjectCode = '" . $_POST['SubjectCode'] . "'
						)
				where
					i.import_key   = '" . $_import_key . "' and
					i.SubjectCode  = '" . $_POST['SubjectCode'] . "' and 
					i.acadyear     = '" . $acadyear . "' and
					i.acadsemester = '" . $acadsemester . "' 

				order by
				sex,
				convert(firstname using tis620)

			";
	}
	
	//echo $_sql_import_list. "<br/>";
	$_res_import = mysqli_query($_connection,$_sql_import_list);
	$_check_existing_grade = mysqli_num_rows($_res_import);

	$_import_count = 0;

?>

<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      			<td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        			<span class="normal"><font color="#0066FF"><strong>3.4.1 อัปโหลดผลกาาเรียน (วิชาพื้นฐาน,เพิ่มเติม) - ส่วนประมวลผล</strong></font></span></td>
				<td >
					<form method="get">
							<input type="hidden" name="option"       value="module_gpa/uploadGrade" />
							<input type="hidden" name="acadyear"     value="<?=$_POST['acadyear']?>" />
							<input type="hidden" name="acadsemester" value="<?=$_POST['acadsemester']?>" />
							<input type="hidden" name="teacher_id"   value="<?=$_POST['teacher_id']?>" />
							<input type="submit" name="back" value="ย้อนกลับ" />
					</form>
				</td>
			</tr>
	</table>
 
	<div align="center">
		<form method="post" enctype="multipart/form-data" action=""> 
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center" width="500px">
				<tr height="35px">  
					<td colspan="2" align="center">
							<img src="../images/school_logo.png" width="120px"><br/> 
							ท่านกำลังจะทำการอัปโหลดผลการเรียน <br/>
							ภาคเรียนที่ <b><?=$_POST['acadsemester']?></b> ปีการศึกษา <b><?=$_POST['acadyear']?></b> <br/>
							รายวิชา <font color='green'><b><?=$_POST['SubjectCode']?> <?=$_POST['SubjectName']?></b></font>
							ห้อง <font color='green'><b><?=getFullRoomFormat($_POST['room_id'])?></b> </font><br/>
							ครูผู้สอน <font color='green'><b><?=getUserAccountName($_connection,$_POST['teacher_id'])?></b></font>

							<br/>
					</td>
				</tr>
				<? if($_check_file_existing>0) { ?>
					<tr>
						<td width="80px" valign="top">สถานะ</td>
						<td width="700px">
							<?php
								if($_f['import_status']=="อัปโหลดผลการเรียนรอการยืนยัน"){
									echo "<font color='purple'><b>";
									echo $_f['import_status'];
									echo "</b></font>";
								}else if($_f['import_status']=="ยืนยันผลการเรียนแล้ว"){
									echo "<font color='green'><b>";
									echo $_f['import_status'];
									echo "</b></font>";
								}else{
									echo "<font color='red'><b>";
									echo "รอการอัปโหลดไฟล์";
									echo "</b></font>";
								}
								echo "<br/>"
							?>
							รายวิชานี้ได้ทำการอัปโหลดแล้วเมื่อเวลา <b><?=$_f['created_datetime']?></b> <br/>
							ชื่อไฟล์ <b><?=$_f['source_file_name']?></b> <br/>
							โดย <b><?=$_f['firstname']. ' ' . $_f['lastname']?> </b>
						</td>
					</tr>
				<? } ?>
				<tr>
					<td>
						<br/>เลือกไฟล์
					</td>
					<td>
						<br/>
						<input type="file" name="student_file" accept=".xls, .xlsx" size="200px"/><br/>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="hidden" name="SubjectCode"         value="<?=$_POST['SubjectCode']?>" />
						<input type="hidden" name="teacher_register_id" value="<?=$_POST['teacher_register_id']?>" />
						<input type="hidden" name="SubjectName"         value="<?=$_POST['SubjectName']?>" />
						<input type="hidden" name="teacher_id"          value="<?=$_POST['teacher_id']?>" />
						<input type="hidden" name="room_id"             value="<?=$_POST['room_id']?>" />
						<input type="hidden" name="club_code"           value="<?=$_POST['club_code']?>" />
						<input type="hidden" name="acadyear"            value="<?=$_POST['acadyear']?>" />
						<input type="hidden" name="acadsemester"        value="<?=$_POST['acadsemester']?>" />
						<input type="hidden" name="subject_register_id" value="<?=$_POST['subject_register_id']?>" />

						<input type="submit" name="upload" value="อัพโหลด" class="button"/>
					</td>
					<tr>
						<td colspan="2">
							<b>ขั้นตอน</b><br/>
							1. เมื่อเข้าสู่หน้าจัดการผลการเรียน รายชื่อนักเรียนที่ลงทะเบียนเรียนจะปรากฎด้านซ้ายมือ <br/>
							2. ตรวจสอบรายละเอียดของวิชาเรียน ห้องเรียน ที่จะอัปโหลดผลการเรียนให้ถูกต้อง <br/>
							3. เมื่ออัปโหลดผลการเรียนแล้ว รายชื่อนักเรียนจากไฟล์ MS-Excel จะปรากฎด้านขวามือ <br/>
							4. ตรวจสอบความถูกต้องรายชื่อนักเรียน ผลการเรียน<br/>
							5. กดปุ่ม "ยืนยัน" เพื่อทำการยืนยันผลการเรียน แล้วถ้าสถานะเปลี่ยนเป็น "ยืนยันส่งผลการเรียน" แล้วถือว่าเสร็จสิ้น

							<br/><br/>
							<u>การตรวจสอบข้อมูล</u><br/>
							- เลขประจำตัว<font color='red'>สีแดง</font> หมายถึง ไม่มีผลการเรียนของนักเรียนให้ตรวจสอบไฟล์ MS-excel <br/>
							- เลขประจำตัว<font color='purple'>สีม่วง</font> หมายถึง ระบบไม่ได้ลงทะเบียนให้นักเรียน หรือเลขประจำตัวอาจจะไม่ตรงกันให้แจ้งงานทะเบียน

							<br/><br/>
						</td>
					</tr>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<?php
							if(isset($_POST['upload']) || isset($_POST['confirm'])){
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

	<? if($_check_existing_grade > 0){ ?> 
		<div align="center">
			<table class="admintable">
				<tr>
					<th align="center" colspan="11">กรุณาตรวจสอบข้อมูล หากผลการเรียนฝั่งขวามือถูกต้อง ให้กด ยืนยัน ด้านล่าง</th>
				</tr>
				<tr>
					<td class="key" align="center" colspan="6">อ้างอิงจากฐานข้อมูลระบบสารสนเทศ</td>
					<td class="key" align="center" colspan="4">อ้างอิงจากไฟล์นำเข้าข้อมูล</td>
				</tr>
				<tr>
					<td class="key" align="center" width="20px" >ที่</td>
					<td class="key" align="center" width="80px" >เลขประจำตัว</td>
					<td class="key" align="center" width="180px">ชื่อ-สกุล</td>
					<td class="key" align="center" width="40px" >ห้อง</td>
					<td class="key" align="center" width="50px" >คะแนน</td>
					<td class="key" align="center" width="50px" >เกรด</td>

					<td class="key" align="center" width="80px" >เลขประจำตัว</td>
					<td class="key" align="center" width="180px">ชื่อ-สกุล</td>
					<td class="key" align="center" width="50px" >คะแนน</td>
					<td class="key" align="center" width="50px" >เกรด</td>
				</tr>
				<? $_order = 1; ?>
				<? while($_dat = mysqli_fetch_assoc($_res_import)){ ?>
					<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
						<td align="center" valign="top"><?=$_order++?></td>
						<td align="center" valign="top">
							<?php
								if($_dat['student_id'] != $_dat['i_student_id']){
									echo "<font color='red'>";
									echo $_dat['student_id'];
									echo "</font>";
								}else{
									echo $_dat['student_id'];
								}
							?>
						</td>
						<td align="left"><?=$_dat['prefix'].$_dat['firstname'].' ' . $_dat['lastname']?></td>
						<td align="center"><?=$_dat['room_id']?></td>
						<td align="center"><?=$_dat['point_100']?></td>
						<td align="right" style="padding-right:20px"><?=displayGrade($_dat['grade'])?></td>

						<td align="center">
						<?php
								if($_dat['student_id'] != $_dat['i_student_id']){
									echo "<font color='purple'>";
									echo $_dat['i_student_id'];
									echo "</font>";
								}else{
									echo $_dat['i_student_id'];
								}

								if($_dat['student_id']==$_dat['i_student_id'] && displayGrade($_dat['i_grade']) != "-"){
									$_import_count++;
								}
							?>
						</td>
						<td align="left"><?=$_dat['i_prefix'].$_dat['i_firstname'].' ' . $_dat['i_lastname']?></td>
						<td align="center"><?=$_dat['i_point_100']?></td>
						<td align="right" style="padding-right:20px"><?=displayGrade($_dat['i_grade'])?></td>
					</tr>
				<? } ?>
				<? if($_import_count>0 && $_file_status != ""){ ?>
					<tr height="35px">
						<td align="center" colspan="11">
							<form method="post" action="">
								<input type="hidden" name="SubjectCode"         value="<?=$_POST['SubjectCode']?>" />
								<input type="hidden" name="teacher_register_id" value="<?=$_POST['teacher_register_id']?>" />
								<input type="hidden" name="SubjectName"         value="<?=$_POST['SubjectName']?>" />
								<input type="hidden" name="teacher_id"          value="<?=$_POST['teacher_id']?>" />
								<input type="hidden" name="room_id"             value="<?=$_POST['room_id']?>" />
								<input type="hidden" name="club_code"           value="<?=$_POST['club_code']?>" />
								<input type="hidden" name="acadyear"            value="<?=$_POST['acadyear']?>" />
								<input type="hidden" name="acadsemester"        value="<?=$_POST['acadsemester']?>" />
								<input type="hidden" name="subject_register_id" value="<?=$_POST['subject_register_id']?>" />
								<input type="hidden" name="upload_id"           value="<?=$_f['upload_id']?>" />
								<input type="submit" name="confirm" value="ยืนยัน" class="button" />
							</form>
						</td>
					</tr>
				<? } ?>
			</table>
		</div>
	<? } ?>
</div>

<?php
	// lab test
	/*
		require_once "../include/SimpleXLS.php";
		//use Shuchkin\SimpleXLS;


		$_file = $_target . "Ex_2565_2_ธวัชชัย_ศ22102_m21.xls";

		if(file_exists($_file)){
			echo "OK";
		}else{
			echo "NO";
		}
		echo "<h1> books.xls </h1><pre>";
		if ($xls = SimpleXLS::parse($_file)) {
			print_r($xls->rows());
		} else {
			echo SimpleXLS::parseError();
		}
		echo '<pre>';
	*/
	?>