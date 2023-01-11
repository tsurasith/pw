<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	$_continue_processing = false;

	$_sql_update_curr = "";
	$_sql_update_mapping = "";
	

	if(isset($_POST['ConfirmEdit']) && trim($_POST['curriculum_id']) != "" && trim($_POST['curriculum_name']) != "" ){

		if(trim($_POST['old_curriculum_code']) != trim($_POST['curriculum_code'])){
			$_sql_curr_code_verify = " 
			SELECT * FROM `curriculums`
			WHERE
				`curriculum_code` = '" . trim($_POST['curriculum_code']) . "'
			";

			$_is_curr_code_changed = true;


			$_res_curr_check = mysqli_query($_connection,$_sql_curr_code_verify);
			$_check_row = mysqli_num_rows($_res_curr_check);
			if($_check_row > 0){
				$_processing_result = false;
				$_processing_text = "ไม่สามารถแก้ไข รหัสแผนการเรียน เป็นค่าที่ต้องการได้ เนื่องจาก รหัสนี้ถูกใช้ไปแล้ว<br/>(ท่านป้อนรหัสแผนการเรียนซ้ำกับแผนการเรียนที่มีอยู่ในระบบ)";
			}
			else{
				$_sql_update_curr = "
					UPDATE
						`curriculums`
					SET
						`curriculum_code` 			= '" . trim($_POST['curriculum_code']) ."',
						`curriculum_start_year` 	= '" . trim($_POST['curriculum_start_year']) ."',
						`curriculum_name` 			= '" . trim($_POST['curriculum_name']) ."',
						`curriculum_description`	= '" . trim($_POST['curriculum_description']) ."',
						`curriculum_status` 		= '" . trim($_POST['curriculum_status']) ."',
						`updated_datetime` 			= CURRENT_TIMESTAMP,
						`updated_user` 				= '" . $_SESSION['user_account_id'] ."'
					WHERE
					`curriculum_id` = '" . trim($_POST['curriculum_id']) ."'
				";

				$_res = mysqli_query($_connection,$_sql_update_curr);
				if($_res){
					$_sql_update_mapping = "
							UPDATE
								`curriculum_subject_mappings`
							SET
								`curriculum_code` 			= '" . trim($_POST['curriculum_code']) ."',
								`updated_datetime` 			= CURRENT_TIMESTAMP ,
								`updated_user` 				= '" . $_SESSION['user_account_id'] ."'
							WHERE
								`curriculum_id` = '" . trim($_POST['curriculum_id']) ."'
						";
					$_res2 = mysqli_query($_connection,$_sql_update_mapping);
					if($_res2){
						$_processing_text = "แก้ไขแผนการเรียนเรียบร้อยแล้ว";
						$_processing_result = true;

						// line message here
						$_text .= "แผนการเรียน: ";
						$_text .= trim($_POST['curriculum_name']) . " ถูกแก้ไขเรียบร้อยแล้ว ";
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
						$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถแก้ไข แผนการเรียน ได้" . "<br/>";
						$_processing_text .= "แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
					}
				}else{
					$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถแก้ไข แผนการเรียน ได้" . "<br/>";
					$_processing_text .= "แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
				}

			} //end else

		}else{


			$_sql_update_curr = "
						UPDATE
							`curriculums`
						SET
							`curriculum_start_year` 	= '" . trim($_POST['curriculum_start_year']) ."',
							`curriculum_name` 			= '" . trim($_POST['curriculum_name']) ."',
							`curriculum_description`	= '" . trim($_POST['curriculum_description']) ."',
							`curriculum_status` 		= '" . trim($_POST['curriculum_status']) ."',
							`updated_datetime` 			= CURRENT_TIMESTAMP,
							`updated_user` 				= '" . $_SESSION['user_account_id'] ."'
						WHERE
						`curriculum_id` = '" . trim($_POST['curriculum_id']) ."'
					";

			$_res = mysqli_query($_connection,$_sql_update_curr);
			
			if($_res){
				$_processing_text = "แก้ไขแผนการเรียนเรียบร้อยแล้ว";
				$_processing_result = true;

				// line message here
				$_text .= "แผนการเรียน: ";
				$_text .= trim($_POST['curriculum_name']) . " ถูกแก้ไขเรียบร้อยแล้ว ";
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
				//header("Location: index.php?option=module_curriculum/CreateCurriculum");
			}else{
				$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถแก้ไข แผนการเรียน ได้" . "<br/>";
				$_processing_text .= "แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
			}
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
					<span class="normal"><font color="#0066FF"><strong>3.3 เพิ่ม/แก้ไข หลักสูตรการเรียนการสอน (แผนการเรียน) - แก้ไขแผนการเรียน</strong></font></span>
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
			<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
				<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
					<tr> 
					<th colspan="7" align="center">
						<font color='purple'>	
						ข้อมูลแผนการเรียนที่ต้องการแก้ไข
						<br/>
						ท่านสามารถแก้ไขข้อมูลแผนการเรียนได้จากรายการด้านล่าง<br/><br/>
					</th>
					</tr>
					<tr height="35px"> 
						<td class="key" width="80px" align="center">รหัสแผน<br/>การเรียน</td>
						<td class="key" width="130px" align="center">ระดับการศึกษา</td>
						<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
						<td class="key" width="280px" align="center">ชื่อแผนการเรียน</td>
						<td class="key" width="90px" align="center">สถานะ</td>
						<td class="key" width="80px" align="center">จำนวนวิชาที่เปิดสอน</td>
						<td class="key" width="160px" align="center">แก้ไขล่าสุด</td>
					</tr>
					
						<tr>
							<td align="center"><?=$_dat['curriculum_code']?></td>
							<td align="center"><?=displayEducationLevel($_dat['curriculum_level'])?></td>
							<td align="center"><?=$_dat['curriculum_start_year']?></td>
							<td><?=$_dat['curriculum_name']?></td>
							<td align="center"><?=$_dat['curriculum_status']=="ACTIVE"?"ยังใช้งานอยู่":"หยุดทำการสอน"?></td>
							<td align="center"><?=$_POST['subject_count']?></td>
							<td align="center"><?=$_dat['updated_datetime']?></td>
					</tr>
				</table>

				<div align="center">
					<br/><br/><br/>
					<form action="" method="post" autocomplete="off">
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="2"> &nbsp; ส่วนแก้ไขแผนการเรียน</td>
							</tr>
							<tr>
								<td align="right" width="200px"> รหัสแผนการเรียน </td>
								<td> <input type="text" name="curriculum_code" value=<?=$_dat['curriculum_code']?> size="15" class="inputboxUpdate" maxlength="20" /> </td>
							</tr>
							<tr>
								<td align="right"> ระดับการศึกษา </td>
								<td> 
									<?=displayEducationLevel($_dat['curriculum_level'])?>
								</td>
							</tr>
							<tr>
								<td  align="right"> ชื่อแผนการเรียน </td>
								<td> <input type="text" name="curriculum_name" value="<?=$_dat['curriculum_name']?>" size="50" class="inputboxUpdate" maxlength="150" /> </td>
							</tr>
							<tr>
								<td align="right"> ปีที่เริ่มใช้แผนการเรียน </td>
								<td> 
									<select name="curriculum_start_year" class="inputboxUpdate">
										<? 
										$_selected = "";
										for($_i=0;$_i<15;$_i++){
											if($_dat['curriculum_start_year']==((int) date("Y")+543 - (15-$_i))){
												$_selected = "selected";
											}else{
												$_selected = "";
											}
											echo "<option value='". ((int) date("Y")+543 - (15-$_i)) . "' " . $_selected . " >";
											echo ((int) date("Y")+543 - (15-$_i));
											echo "</option>";
										}	
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right"> สถานะแผนการเรียน </td>
								<td> 
									<select name="curriculum_status" class="inputboxUpdate">
										<option <?=$_dat['curriculum_status']=="ACTIVE"?"selected":""?> value="ACTIVE">ยังใช้งานอยู่</option>
										<option <?=$_dat['curriculum_status']=="CANCEL"?"selected":""?> value="CANCEL">หยุดทำการสอน</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" valign="top"> คำอธิบายแผนการเรียน </td>
								<td><textarea name="curriculum_description" rows="4" cols="75"><?=$_dat['curriculum_description']?></textarea></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><br/>
									<?php
										if(isset($_POST['ConfirmEdit'])){
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
									<input type="hidden" name="old_curriculum_code" value="<?=$_dat['curriculum_code']?>"/>
									<input type="hidden" name="curriculum_id" value="<?=$_dat['curriculum_id']?>"/>
									<input type="hidden" name="subject_count" value="<?=$_POST['subject_count']?>"/>
									<input type="submit" name="ConfirmEdit" class="button" value="แก้ไข" /> 
									<input type="button" name="cancel" class="button" value="กลับ" onclick="location.href='index.php?option=module_curriculum/CreateCurriculum';"/>
								</td>
							</tr>
						</table>
					</form>
				</div>
			<? } // end while?>




		<? } else { ?>
				<div align='center'><font color='red'><br/><br/>ยังไม่มีการบันทึกแผนการเรียน</font></div>
		<? } ?>
	</div>

</div>

