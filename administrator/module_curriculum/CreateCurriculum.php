<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['addnew']) && trim($_POST['curriculum_code']) != "" && trim($_POST['curriculum_name']) != "" ){

		$_sql_insert = "
			INSERT INTO `curriculums`(
				`curriculum_code`,
				`curriculum_start_year`,
				`curriculum_level`,
				`curriculum_name`,
				`curriculum_description`,
				`curriculum_status`,
				`created_datetime`,
				`created_user`,
				`updated_datetime`,
				`updated_user`
			)
			VALUES(
				'". $_POST['curriculum_code']. "',
				'". $_POST['curriculum_start_year']. "',
				'". $_POST['curriculum_level'] . "',
				'". $_POST['curriculum_name']. "',
				'". $_POST['curriculum_description']. "',
				'". $_POST['curriculum_status']. "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "'
			)
		";

		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res){
			$_processing_text = "บันทึกข้อมูลหลักสูตรการสอน (แผนการเรียน) เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "หลักสูตรการสอน (แผนการเรียน): ";
			$_text .= trim($_POST['curriculum_name']) . " ถูกเพิ่มเข้าในระบบแล้ว";
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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลหลักสูตรการสอน (แผนการเรียน) ได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "การป้อนข้อมูลบันทึกไม่ถูกต้อง ท่านอาจจะไม่ได้ระบุ รหัสหลักสูตร หรือ ชื่อหลักสูตร กรุณาตรวจสอบอีกครั้ง";
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
					<span class="normal"><font color="#0066FF"><strong>3.5 เพิ่ม/แก้ไข หลักสูตรการเรียนการสอน (แผนการเรียน)</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<br/>
					<input type="button" value="โหลดดูข้อมูลล่าสุด" onclick="location.href='index.php?option=module_curriculum/CreateCurriculum';" />
				</td>
			</tr>
	</table>
  
	<div align="center">
		<?php
			$_sql_cur = "select * from curriculums order by curriculum_level,curriculum_code ";
			$_res_curr = mysqli_query($_connection,$_sql_cur);
			$_rows = mysqli_num_rows($_res_curr);
			$_no = 1;
		?>
		<? if($_rows>0){  ?>
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
				<tr> 
				<th colspan="10" align="center">
						<img src="../images/school_logo.png" width="120px"><br/>
						หลักสูตร และ แผนการสอน โรงเรียนเพชรวิทยาคาร <br/><br/>
				</th>
				</tr>
				<tr height="35px"> 
					<td class="key" width="25px" align="center">ที่</td>
					<td class="key" width="80px" align="center">รหัสหลักสูตร</td>
					<td class="key" width="130px" align="center">ระดับการศึกษา</td>
					<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
					<td class="key" width="280px" align="center">ชื่อหลักสูตร (แผนการเรียน)</td>
					<td class="key" width="90px" align="center">สถานะ</td>
					<td class="key" width="80px" align="center">จำนวนวิชาที่เปิดสอน</td>
					<td class="key" width="160px" align="center">แก้ไขล่าสุด</td>
					<td class="key" colspan="2" align="center">-</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
					<tr>
						<td align="center"><?=$_no++?></td>
						<td align="center"><?=$_dat['curriculum_code']?></td>
						<td align="center"><?=displayEducationLevel($_dat['curriculum_level'])?></td>
						<td align="center"><?=$_dat['curriculum_start_year']?></td>
						<td><?=$_dat['curriculum_name']?></td>
						<td align="center"><?=$_dat['curriculum_status']?></td>
						<td align="center">-</td>
						<td align="center"><?=$_dat['updated_datetime']?></td>
						<td align="center">
							แก้ไข
						</td>
						<td align="center">
							<form action="index.php?option=module_curriculum/DeleteCurriculum" method="post">
								<input type="hidden" name="curriculum_id" value="<?=$_dat['curriculum_id']?>"/>
								<input type="hidden" name="curriculum_name" value="<?=$_dat['curriculum_name']?>"/>
								<input type="hidden" name="curriculum_level" value="<?=$_dat['curriculum_level']?>"/>
								<input type="submit" name="delete" value="ลบ"/>
							</form>	
						</td>
					</tr>
				<? } ?>
			</table>
		<? } else { ?>
				<div align='center'><font color='red'><br/><br/>ยังไม่มีการบันทึกหลักสูตรการสอน (แผนการเรียน)</font></div>
		<? } ?>
	</div>

	<div align="center">
		<br/><br/><br/>
		<form action="" method="post" autocomplete="off">
			<table class="admintable">
				<tr height="35px">
					<td class="key" colspan="2"> &nbsp; ส่วนเพิ่มหลักสูตรการสอน (แผนการเรียน)</td>
				</tr>
				<tr>
					<td align="right" width="200px"> รหัสหลักสูตร </td>
					<td> <input type="text" name="curriculum_code" size="15" class="inputboxUpdate" maxlength="20" /> </td>
				</tr>
				<tr>
					<td align="right"> ระดับการศึกษา </td>
					<td> 
						<select name="curriculum_level" class="inputboxUpdate">
							<option value="0">อนุบาล</option>
							<option value="1">ประถมต้น</option>
							<option value="2">ประถมปลาย</option>
							<option value="3">มัธยมศึกษาตอนต้น</option>
							<option value="4">มัธยมศึกษาตอนปลาย</option>
						</select>
					</td>
				</tr>
				<tr>
					<td  align="right"> ชื่อหลักสูตร (แผนการเรียน) </td>
					<td> <input type="text" name="curriculum_name" size="50" class="inputboxUpdate" maxlength="150" /> </td>
				</tr>
				<tr>
					<td align="right"> ปีที่เริ่มใช้หลักสูตร </td>
					<td> 
						<select name="curriculum_start_year" class="inputboxUpdate">
							<? for($_i=0;$_i<15;$_i++){
								 echo "<option value='". ((int) date("Y")+543 - (15-$_i)) . "' >";
								 echo ((int) date("Y")+543 - (15-$_i));
								 echo "</option>";
							   }	
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> สถานะหลักสูตร </td>
					<td> 
						<select name="curriculum_status" class="inputboxUpdate">
							<option value="ACTIVE">ยังใช้งานอยู่</option>
							<option value="CANCEL">หยุดทำการสอน</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"> คำอธิบายหลักสูตร (แผนการเรียน) </td>
					<td><textarea name="curriculum_description" rows="4" cols="75"></textarea></td>
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

