<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(
		isset($_POST['confirmEdit']) && trim($_POST['subject_code']) != "" && 
		trim($_POST['subject_name']) != ""  && 
		is_numeric(trim($_POST['subject_unit'])) &&
		is_numeric(trim($_POST['subject_hour']))
	  ){

		$_sql_insert = "
			UPDATE
			`curriculum_subjects`
				SET
					`SubjectName` 	= '" . trim($_POST['subject_name']) . "',
					`SubjectUnit` 	= '" . trim($_POST['subject_unit']) . "',
					`SubjectHour` 	= '" . trim($_POST['subject_hour']) . "',
					`SubjectType` 	= '" . $_POST['subject_type'] . "',
					`SubjectGroup`	= '" . $_POST['subject_group'] . "',
					`SubjectLevel`	= '" . $_POST['subject_level'] . "',
					`SubjectSemester` = '" . $_POST['subject_semester'] . "',
					`is_split_class`= '" . $_POST['is_split_class'] . "',
					`subject_status`= '" . $_POST['subject_status'] . "',
					`subject_description` = '" . trim($_POST['subject_description']) . "',
					`updated_datetime` = CURRENT_TIMESTAMP,
					`updated_user_id` = '" . $_SESSION['user_account_id'] . "'
				WHERE
				`SubjectCode` = '" . $_POST['subject_code'] . "'
			";

		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res){
			$_processing_text = "แก้ไขข้อมูลวิชา: <b>" . $_POST['subject_code'] . " " . $_POST['subject_name'] . "</b> เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "รหัสวิชา: ";
			$_text .= trim($_POST['subject_code']) . " ถูกแก้ไข ";
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
					<span class="normal"><font color="#0066FF"><strong>3.2 แก้ไข รายวิชาที่เปิดสอน</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<form method="post" autocomplete="off">
						<font  size="2" color="#000000">
							รหัสวิชา 
							<input type="text" name="subject_search" value="<?=isset($_POST['subject_search'])?$_POST['subject_search']:""?>" class="inputboxUpdate" size="6" maxlength="6" />
							<input type="submit" name="search" class="button" value="ค้นหา" />
						</font>
					</form>
				</td>
			</tr>
	</table>
  
	<?php
		if(isset($_POST['subject_search']) && trim($_POST['subject_search']) != ""){ ?>
		<?php
			$_sqlSearch = "select * from curriculum_subjects where SubjectCode = '" . trim($_POST['subject_search']) . "'";
			$_resSearch = mysqli_query($_connection,$_sqlSearch);
			$_rows = mysqli_num_rows($_resSearch);

		?>
			
			<? if($_rows>0){  // found the subject in DB?>

			<?	
				// show relating curriculums
				$_sqlCurr = "
					SELECT
						c.curriculum_id,
						c.curriculum_code,
						c.curriculum_start_year,
						c.curriculum_name,
						c.curriculum_status,
						c.curriculum_level,
						m.SubjectCode,
						m.curriculum_mapping_level,
						m.curriculum_mapping_semester
					FROM
						curriculums c inner join curriculum_subject_mappings m 
						on (c.curriculum_id = m.curriculum_id and m.SubjectCode = '". trim($_POST['subject_search']) . "')
					order by
						c.curriculum_level,c.curriculum_code,m.curriculum_mapping_level,
						m.curriculum_mapping_semester
					";

					$_resC = mysqli_query($_connection,$_sqlCurr);
					$_rowC = mysqli_num_rows($_resC);
					$_j = 1;
				?>

					<div align="center">
						<? if($_rowC >0){ ?> 
							<table class="admintable">
								<tr height="35px">
									<td class="key" colspan="8"> &nbsp; หลักสูตรที่รายวิชานี้บรรจุอยู่ในแผนการเรียน</td>
								</tr>
								<tr height="35px"> 
									<td class="key" width="30px" align="center">ที่</td>
									<td class="key" width="85px" align="center">รหัสหลักสูตร</td>
									<td class="key" width="280px" align="center">ชื่อหลักสูตร (แผนการเรียน)</td>
									<td class="key" width="95px" align="center">ระดับการศึกษา</td>
									<td class="key" width="50px" align="center">ชั้น</td>
									<td class="key" width="50px" align="center">ภาคเรียน</td>
									<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
									<td class="key" width="80px" align="center">สถานะ<br/>หลักสูตร</td>
								</tr>
								<? while($_datC = mysqli_fetch_assoc($_resC)){ ?>
									<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
										<td align="center"><?=$_j++?></td>
										<td align="center"><?=$_datC['curriculum_code']?></td>
										<td> &nbsp; <?=$_datC['curriculum_name']?></td>
										<td align="center"><?=displayEducationLevel($_datC['curriculum_level'])?></td>
										<td align="center"><?=$_datC['curriculum_mapping_level']!=""?"ม." . $_datC['curriculum_mapping_level']:""?></td>
										<td align="center"><?=$_datC['curriculum_mapping_semester']!=""?$_datC['curriculum_mapping_semester']:""?></td>
										<td align="center"><?=$_datC['curriculum_start_year']?></td>
										<td align="center"><?=$_datC['curriculum_status']?></td>
									</tr>
								<? } ?>
							</table>
						<? } else { 
								echo "<br/><br/>";
								echo "<font color='#900C3F'> ยังไม่มีการบรรจุรายวิชานี้ ในแผนการเรียนใด </font>";
								echo "<br/><br/>";
						} ?>
					</div>

				<? $_dat = mysqli_fetch_assoc($_resSearch); ?>
				<div align="center">
					<br/><br/>
					<form action="" method="post" autocomplete="off">
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="2"> &nbsp; รายละเอียดของรายวิชาที่ต้องการแก้ไขข้อมูล</td>
							</tr>
							<tr>
								<td align="right" width="160px"> รหัสวิชา </td>
								<td width="440px">
									<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
									<font size="4" color='green'><b><?=$_dat['SubjectCode']?></b></font> 
								</td>
							</tr>
							<tr>
								<td align="right" > ชื่อวิชา </td>
								<td> <input type="text" name="subject_name" value="<?=$_dat['SubjectName']?>" size="40" class="inputboxUpdate" maxlength="100" /> </td>
							</tr>
							<tr>
								<td align="right" > หน่วยการเรียน </td>
								<td> 
									<select name="subject_unit" class="inputboxUpdate">
										<option value="0.0" <?=($_dat['SubjectUnit']=="0.0"?"selected":"")?> >0.0</option>
										<option value="0.5" <?=($_dat['SubjectUnit']=="0.5"?"selected":"")?> >0.5</option>
										<option value="1.0" <?=($_dat['SubjectUnit']=="1.0"?"selected":"")?> >1.0</option>
										<option value="1.5" <?=($_dat['SubjectUnit']=="1.5"?"selected":"")?> >1.5</option>
										<option value="2.0" <?=($_dat['SubjectUnit']=="2.0"?"selected":"")?> >2.0</option>
										<option value="2.5" <?=($_dat['SubjectUnit']=="2.5"?"selected":"")?> >2.5</option>
										<option value="3.0" <?=($_dat['SubjectUnit']=="3.0"?"selected":"")?> >3.0</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" > จำนวนชั่วโมง </td>
								<td> <input type="text" name="subject_hour" value="<?=$_dat['SubjectHour']?>" size="6" class="inputboxUpdate" maxlength="5" onkeypress="return isNumberKey(event)" /> </td>
							</tr>
							<tr>
								<td align="right"> ประเภทวิชา </td>
								<td> 
									<select name="subject_type" class="inputboxUpdate">
										<option value="พื้นฐาน" <?=($_dat['SubjectType']=="พื้นฐาน"?"selected":"")?>>พื้นฐาน</option>
										<option value="เพิ่มเติม"<?=($_dat['SubjectType']=="เพิ่มเติม"?"selected":"")?>>เพิ่มเติม</option>
										<option value="กิจกรรมพัฒนาผู้เรียน" <?=($_dat['SubjectType']=="กิจกรรมพัฒนาผู้เรียน"?"selected":"")?>>กิจกรรมพัฒนาผู้เรียน</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right"> กลุ่มสาระการเรียนรู้ </td>
								<td> 
									<?php
										$_sqlGroup = "select * from curriculum_subject_group order by subject_group_id";
										$_resGroup = mysqli_query($_connection,$_sqlGroup);

									?>
									<select name="subject_group" class="inputboxUpdate">
										<? while($_datG = mysqli_fetch_assoc($_resGroup)){ ?>
											<option value="<?=$_datG['subject_group_id']?>" <?=($_datG['subject_group_id']==$_dat['SubjectGroup']?"selected":"")?>>
												<?=$_datG['subject_group_id'] . ' - ' . $_datG['subject_group_name']?>
											</option>
										<? } // end while ?>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right"> ระดับชั้น </td>
								<td> 
									<select name="subject_level" class="inputboxUpdate">
										<option value="0" <?=($_dat['SubjectLevel']=="0"?"selected":"")?> >เรียนได้อิสระ 0</option>
										<option value="1" <?=($_dat['SubjectLevel']=="1"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 1</option>
										<option value="2" <?=($_dat['SubjectLevel']=="2"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 2</option>
										<option value="3" <?=($_dat['SubjectLevel']=="3"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 3</option>
										<option value="4" <?=($_dat['SubjectLevel']=="4"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 4</option>
										<option value="5" <?=($_dat['SubjectLevel']=="5"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 5</option>
										<option value="6" <?=($_dat['SubjectLevel']=="6"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 6</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right"> ภาคเรียนที่เปิดสอน </td>
								<td> 
									<select name="subject_semester" class="inputboxUpdate">
										<option value="0" <?=($_dat['SubjectSemester']=="0"?"selected":"")?> >เปิดทุกภาคเรียน</option>
										<option value="1" <?=($_dat['SubjectSemester']=="1"?"selected":"")?> >เปิดเฉพาะภาคเรียนที่ 1</option>
										<option value="2" <?=($_dat['SubjectSemester']=="2"?"selected":"")?> >เปิดเฉพาะภาคเรียนที่ 2</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right"> การจัดกลุ่มเรียน </td>
								<td> 
									<select name="is_split_class" class="inputboxUpdate">
										<option value="0" <?=($_dat['is_split_class']=="0"?"selected":"")?> >แบ่งกลุ่มตามห้องเรียน</option>
										<option value="1" <?=($_dat['is_split_class']=="1"?"selected":"")?> >แบ่งกลุ่มอิสระในระดับชั้น</option>
										<option value="2" <?=($_dat['is_split_class']=="2"?"selected":"")?> >แบ่งกลุ่มอิสระในระดับการศึกษา</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right"> สถานะรายวิชา </td>
								<td> 
									<select name="subject_status" class="inputboxUpdate">
										<option value="ACTIVE" <?=($_dat['subject_status']=="ACTIVE"?"selected":"")?> >เปิดสอน</option>
										<option value="CANCEL" <?=($_dat['subject_status']=="CANCEL"?"selected":"")?> >หยุดทำการสอน</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" valign="top"> คำอธิบายรายวิชา </td>
								<td><textarea name="subject_description" rows="4" cols="50"><?=$_dat['subject_description']?></textarea></td>
							</tr>
							<tr>
								<td align="right" valign="top"> เวลาแก้ไขล่าสุด </td>
								<td><?=$_dat['updated_datetime']?> / โดย <?=getUserAccountName($_connection,$_dat['updated_user_id'])?></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><br/>
									<?php
										if(isset($_POST['confirmEdit'])){
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
							<tr>
								<td></td>
								<td>	
									<input type="hidden" name="subject_search" value=<?=$_dat['SubjectCode']?> />
									<input type="hidden" name="search" value="ค้นหา" />
									<input type="submit" name="confirmEdit" class="button" value="บันทึกแก้ไข" /> 
								</td>
							</tr>
						</table>
					</form>
				</div>
			<? } else {
				echo "<div align='center'><br/><br/>";
				echo "<font color='red'>";
				echo "ไม่พบข้อมูลรหัสวิชา:" . $_POST['subject_search'] ." ในระบบ";
				echo "</font>";
				echo "</div>";
			} ?>
	<?	} ?>

</div>

