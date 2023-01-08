<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(
		isset($_POST['addnew']) && trim($_POST['subject_code']) != "" && 
		trim($_POST['subject_name']) != ""  && 
		is_numeric(trim($_POST['subject_unit'])) &&
		is_numeric(trim($_POST['subject_hour']))
	  ){

		$_sql_insert = "
			INSERT INTO `curriculum_subjects`(
				`SubjectCode`,
				`SubjectName`,
				`SubjectUnit`,
				`SubjectHour`,
				`SubjectType`,
				`SubjectGroup`,
				`SubjectLevel`,
				`SubjectSemester`,
				`is_split_class`,
				`subject_status`,
				`subject_description`,
				`created_datetime`,
				`created_user`,
				`updated_datetime`,
				`updated_user_id`
			)
			VALUES(
				'" . $_POST['subject_code'] . "',
				'" . trim($_POST['subject_name']) . "',
				'" . trim($_POST['subject_unit']) . "',
				'" . trim($_POST['subject_hour']) . "',
				'" . $_POST['subject_type'] . "',
				'" . $_POST['subject_group'] . "',
				'" . $_POST['subject_level'] . "',
				'" . $_POST['subject_semester'] . "',
				'" . $_POST['is_split_class'] . "',
				'" . $_POST['subject_status'] . "',
				'" . trim($_POST['subject_description']) . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "'
			)
		";

		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res){
			$_processing_text = "บันทึกข้อมูลวิชา: <b>" . $_POST['subject_name'] . "</b> ที่เปิดสอนเรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "รหัสวิชา: ";
			$_text .= trim($_POST['subject_code']) . " ถูกเพิ่มเข้าในระบบแล้ว";
			$_text .= "\n" . "โดย - " . $_SESSION['shortname'];

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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึก วิชาเรียน ได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึก <b>รหัสวิชา</b> ซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "การป้อนข้อมูลบันทึกไม่ถูกต้อง ท่านอาจจะไม่ได้ระบุ รหัสวิชา หรือ ชื่อวิชา หรือ หน่วยการเรียน/จำนวนชั่วโมง อาจจะไม่ใช่ตัวเลข กรุณาตรวจสอบอีกครั้ง";
	}

?>


<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_curriculum/index">	
						<img src="../images/graduation.png" alt="" width="48" height="48" />
				</a>
				</td>
				<td width="64%"><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>3.1 เพิ่ม รายวิชาที่เปิดสอน</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
				</td>
			</tr>
	</table>
  

	<div align="center">
		<br/><br/><br/>
		<form action="" method="post" autocomplete="off">
			<table class="admintable">
				<tr height="35px">
					<td class="key" colspan="2"> &nbsp; ส่วนเพิ่มรายวิชาที่เปิดสอน</td>
				</tr>
				<tr>
					<td align="right" width="200px"> รหัสวิชา </td>
					<td> <input type="text" name="subject_code" size="10" class="inputboxUpdate" maxlength="6" /> </td>
				</tr>
				<tr>
					<td align="right" width="200px"> ชื่อวิชา </td>
					<td> <input type="text" name="subject_name" size="40" class="inputboxUpdate" maxlength="100" /> </td>
				</tr>
				<tr>
					<td align="right" width="200px"> หน่วยการเรียน </td>
					<td> 
						<select name="subject_unit" class="inputboxUpdate">
							<option value="0.0">0.0</option>
							<option value="0.5">0.5</option>
							<option value="1.0">1.0</option>
							<option value="1.5">1.5</option>
							<option value="2.0">2.0</option>
							<option value="2.5">2.5</option>
							<option value="3.0">3.0</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" width="200px"> จำนวนชั่วโมง </td>
					<td> <input type="text" name="subject_hour" size="6" class="inputboxUpdate" maxlength="5" onkeypress="return isNumberKey(event)" /> </td>
				</tr>
				<tr>
					<td align="right"> ประเภทวิชา </td>
					<td> 
						<select name="subject_type" class="inputboxUpdate">
							<option value="พื้นฐาน">พื้นฐาน</option>
							<option value="เพิ่มเติม">เพิ่มเติม</option>
							<option value="กิจกรรมพัฒนาผู้เรียน">กิจกรรมพัฒนาผู้เรียน</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> กลุ่มสาระการเรียนรู้ </td>
					<td> 
						<select name="subject_group" class="inputboxUpdate">
							<option value="0">0 - ไม่ระบุ</option>
							<option value="1">1 - ภาษาไทย</option>
							<option value="2">2 - คณิตศาสตร์</option>
							<option value="3">3 - วิทยาศาสตร์และเทคโนโลยี</option>
							<option value="4">4 - สังคมศึกษา ศาสนาและวัฒนธรรม</option>
							<option value="5">5 - สุขศึกษาและพลศึกษา</option>
							<option value="6">6 - ศิลปะ</option>
							<option value="7">7 - การงานอาชีพ</option>
							<option value="8">8 - ภาษาต่างประเทศ</option>
							<option value="9">9 - กิจกรรมพัฒนาผู้เรียน</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> ระดับชั้น </td>
					<td> 
						<select name="subject_level" class="inputboxUpdate">
							<option value="0">เรียนได้อิสระ 0</option>
							<option value="1">ชั้นมัธยมศึกษาปีที่ 1</option>
							<option value="2">ชั้นมัธยมศึกษาปีที่ 2</option>
							<option value="3">ชั้นมัธยมศึกษาปีที่ 3</option>
							<option value="4">ชั้นมัธยมศึกษาปีที่ 4</option>
							<option value="5">ชั้นมัธยมศึกษาปีที่ 5</option>
							<option value="6">ชั้นมัธยมศึกษาปีที่ 6</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> ภาคเรียนที่เปิดสอน </td>
					<td> 
						<select name="subject_semester" class="inputboxUpdate">
							<option value="0">เปิดทุกภาคเรียน</option>
							<option value="1">เปิดเฉพาะภาคเรียนที่ 1</option>
							<option value="2">เปิดเฉพาะภาคเรียนที่ 2</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> การจัดกลุ่มเรียน </td>
					<td> 
						<select name="is_split_class" class="inputboxUpdate">
							<option value="0">แบ่งกลุ่มตามห้องเรียน</option>
							<option value="1">แบ่งกลุ่มอิสระในระดับชั้น</option>
							<option value="2">แบ่งกลุ่มอิสระในระดับการศึกษา</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> สถานะรายวิชา </td>
					<td> 
						<select name="subject_status" class="inputboxUpdate">
							<option value="ACTIVE">เปิดสอน</option>
							<option value="CANCEL">หยุดทำการสอน</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"> คำอธิบายรายวิชา </td>
					<td><textarea name="subject_description" rows="4" cols="75"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><br/>
						<?php
							if(isset($_POST['addnew'])){
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
						<br/><br/>
						<input type="submit" name="addnew" class="button" value="เพิ่ม" /> 
					</td>
				</tr>
			</table>
		</form>
	</div>


</div>

