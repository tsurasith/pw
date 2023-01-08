<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['ConfirmDelete']) && trim($_POST['curriculum_id']) != "" && trim($_POST['curriculum_name']) != "" ){

		$_sql_delete = "
			DELETE FROM `curriculums`
			WHERE
				`curriculum_id` = '" . trim($_POST['curriculum_id']) . "'
		";

		$_res = mysqli_query($_connection,$_sql_delete);
		if($_res){
			$_processing_text = "ลบข้อมูลหลักสูตรการสอน (แผนการเรียน) เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "หลักสูตรการสอน (แผนการเรียน): ";
			$_text .= trim($_POST['curriculum_name']) . " ถูกลบออกจากระบบแล้ว ";
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
			header("Location: index.php?option=module_curriculum/CreateCurriculum");
		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถลบข้อมูลหลักสูตรการสอน (แผนการเรียน) ได้" . "<br/>";
			$_processing_text .= "แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "ไม่พบหลักสูตรที่ต้องการ กรุณาตรวจสอบอีกครั้ง";
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
					<span class="normal"><font color="#0066FF"><strong>3.3 เพิ่ม/แก้ไข หลักสูตรการเรียนการสอน (แผนการเรียน) - ยืนยันการลบหลักสูตร</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					</font>
				</td>
			</tr>
	</table>
  
	<div align="center">
		<?php
			$_sql_cur = "select * from curriculums where curriculum_id = '" . $_POST['curriculum_id'] . "'";
			$_res_curr = mysqli_query($_connection,$_sql_cur);
			$_rows = mysqli_num_rows($_res_curr);
		?>
		<? if($_rows>0){  ?>
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
				<tr> 
				<th colspan="7" align="center">
					<font color='red'>	
					ข้อมูล หลักสูตร และ แผนการสอน ที่ต้องการลบ
					 <br/>
					หากต้องการลบหลักสูตรการสอน (แผนการเรียน) นี้ให้คลิกที่ปุ่ม ยืนยันลบ ด้านล่าง<br/><br/>
				</th>
				</tr>
				<tr height="35px"> 
					<td class="key" width="80px" align="center">รหัสหลักสูตร</td>
					<td class="key" width="130px" align="center">ระดับการศึกษา</td>
					<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
					<td class="key" width="280px" align="center">ชื่อหลักสูตร (แผนการเรียน)</td>
					<td class="key" width="90px" align="center">สถานะ</td>
					<td class="key" width="80px" align="center">จำนวนวิชาที่เปิดสอน</td>
					<td class="key" width="160px" align="center">แก้ไขล่าสุด</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
					<tr>
						<td align="center"><?=$_dat['curriculum_code']?></td>
						<td><?=displayEducationLevel($_dat['curriculum_level'])?></td>
						<td align="center"><?=$_dat['curriculum_start_year']?></td>
						<td><?=$_dat['curriculum_name']?></td>
						<td align="center"><?=$_dat['curriculum_status']?></td>
						<td align="center">-</td>
						<td align="center"><?=$_dat['updated_datetime']?></td>
				</tr>
				<tr>
						<td align="center" colspan="7">
							<br/><br/>
							<form action="index.php?option=module_curriculum/DeleteCurriculum" method="post">
								<input type="hidden" name="curriculum_id" value="<?=$_dat['curriculum_id']?>"/>
								<input type="hidden" name="curriculum_name" value="<?=$_dat['curriculum_name']?>"/>
								<input type="hidden" name="curriculum_level" value="<?=$_dat['curriculum_level']?>"/>
								<input type="submit" name="ConfirmDelete" value="ยืนบันลบ"/>
								<input type="button" name="cancel" value="ยกเลิก" onclick="location.href='index.php?option=module_curriculum/CreateCurriculum';"/>
							</form>	
						</td>
					</tr>
				<? } ?>
			</table>
		<? } else { ?>
				<div align='center'><font color='red'><br/><br/>ยังไม่มีการบันทึกหลักสูตรการสอน (แผนการเรียน)</font></div>
		<? } ?>
	</div>

</div>

