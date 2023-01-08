﻿<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['curriculum_id']) && isset($_POST['curriculum_mapping_level'])){

		$_ops = "";
		$_sql_Operation = "";
		
		$_sql_Operation = "
			UPDATE `curriculum_subject_mappings`
			SET
				`curriculum_mapping_level`		= '" . $_POST['curriculum_mapping_level'] . "',
				`curriculum_mapping_semester`	= '" . $_POST['curriculum_mapping_semester'] . "',
				`updated_datetime`				= CURRENT_TIMESTAMP,
				`updated_user`					= '". $_SESSION['user_account_id'] . "'
			WHERE
				`curriculum_id`		= '" . $_POST['curriculum_id'] . "' AND
				`curriculum_code`	=	'" . $_POST['curriculum_code'] . "' AND
				`SubjectCode`		=	'" . $_POST['subject_code_mapping'] . "'
			";
		$_ops = "แก้ไข";
	
		//echo $_sql_Operation;

		$_res = mysqli_query($_connection,$_sql_Operation);
		if($_res){
			$_processing_text  = "ระบบได้ทำการ " . $_ops . " ระดับชั้นที่เปิดสอนของวิชา: <b>" . $_POST['subject_name'] . "</b> ของหลักสูตร: ";
			$_processing_text .= $_POST['curriculum_name'] . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "รหัสวิชา: ";
			$_text .= trim($_POST['subject_search']) . " ถูก" . $_ops;
			$_text .= "ระดับชั้นที่เปิดสอน ของแผนการเรียน: " . $_POST['curriculum_name'] . " แล้ว ";
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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถแก้ไข วิชาเรียน ได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึก <b>รหัสวิชา</b> ซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ - " . mysqli_error($_connection);
		}

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
					<span class="normal"><font color="#0066FF"><strong>3.4 จัดการรายวิชาที่บรรจุในแผนการเรียน (แก้ไขเพิ่มเติม)</strong></font></span>
				</td>
				<td align="center">
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<br/><br/>
					<form method="post" action="index.php?option=module_curriculum/CurriculumMapping">
						<input type="hidden" name="subject_search" value="<?=isset($_POST['subject_search'])?$_POST['subject_search']:""?>" class="inputboxUpdate" size="6" maxlength="6" />
						<input type="submit" name="search" class="button" value="ย้อนกลับ" />
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
			
			<? if($_rows>0){ ?>


				<?php
				$_dat = mysqli_fetch_assoc($_resSearch); // search result subject details

					$_curriculum_level = "";
					if($_dat['SubjectLevel']=="0"){ 
						$_curriculum_level = "'4','3'";
					}else if($_dat['SubjectLevel']>3){
						$_curriculum_level = "'4'";
					}else{
						$_curriculum_level = "'3'";
					}

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
						curriculums c left join curriculum_subject_mappings m 
						on (c.curriculum_id = m.curriculum_id and m.SubjectCode = '". $_dat['SubjectCode']. "')
					WHERE
						c.curriculum_level in (" . $_curriculum_level . ") and
						c.curriculum_id = '" . $_POST['curriculum_id'] . "' 
					order by
						c.curriculum_level,c.curriculum_code
					";

					$_resC = mysqli_query($_connection,$_sqlCurr);
					$_rowC = mysqli_num_rows($_resC);
				?>

				<form method="post" action="">
					<div align="center">
						<? if($_rowC >0){ ?> 
							<table class="admintable">
								<tr height="35px">
									<td class="key" colspan="7"> &nbsp; หลักสูตรที่ตรงกับเงื่อนไขรายวิชา</td>
								</tr>
								<tr height="35px"> 
									<!--<td class="key" width="25px" align="center">-</td>-->
									<td class="key" width="90px" align="center">รหัสหลักสูตร</td>
									<td class="key" width="200px" align="center">ชื่อหลักสูตร (แผนการเรียน)</td>
									<td class="key" width="100px" align="center">ระดับการศึกษา</td>
									<td class="key" width="120px" align="center">แก้ไขระดับชั้น</td>
									<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
									<td class="key" width="80px" align="center">สถานะ<br/>หลักสูตร</td>
									<td class="key" width="120px" align="center">สถานะการบรรจุ<br/>ในแผนการเรียน</td>
								</tr>
								<? while($_datC = mysqli_fetch_assoc($_resC)){ ?>
									<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
										<!--<td align="center"></td>-->
										<td align="center"><?=$_datC['curriculum_code']?></td>
										<td><?=$_datC['curriculum_name']?></td>
										<td align="center"><?=displayEducationLevel($_datC['curriculum_level'])?></td>
										<td align="left">
										<? $_i=0; ?>
											<? $_select = ($_datC['SubjectCode']==$_dat['SubjectCode']?"checked":""); ?>
											<form method="post" action="">
												<input type="hidden" name="subject_code_mapping" value="<?=$_dat['SubjectCode']?>" />
												<input type="hidden" name="curriculum_id" value="<?=$_datC['curriculum_id']?>" />
												<input type="hidden" name="curriculum_code" value="<?=$_datC['curriculum_code']?>" />
												<input type="hidden" name="curriculum_name" value="<?=$_datC['curriculum_name']?>" />
												<input type="hidden" name="subject_name" value="<?=$_dat['SubjectName']?>" />
												<input type="hidden" name="subject_search" value="<?=$_dat['SubjectCode']?>" />
												<input type="hidden" name="curriculum_mapping_level" value="<?=$_datC['curriculum_mapping_level']!=""?$_datC['curriculum_mapping_level']:$_dat['SubjectLevel']?>" />
												<input type="hidden" name="search" class="button" value="ค้นหา" />
												<select name="curriculum_mapping_level" class="inputboxUpdate" onChange="this.form.submit();";>
													<option value="0" <?=($_datC['curriculum_mapping_level']=="0"?"selected":"")?> >เรียนได้อิสระ 0</option>
													<option value="1" <?=($_datC['curriculum_mapping_level']=="1"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 1</option>
													<option value="2" <?=($_datC['curriculum_mapping_level']=="2"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 2</option>
													<option value="3" <?=($_datC['curriculum_mapping_level']=="3"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 3</option>
													<option value="4" <?=($_datC['curriculum_mapping_level']=="4"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 4</option>
													<option value="5" <?=($_datC['curriculum_mapping_level']=="5"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 5</option>
													<option value="6" <?=($_datC['curriculum_mapping_level']=="6"?"selected":"")?> >ชั้นมัธยมศึกษาปีที่ 6</option>
												</select>
												<select name="curriculum_mapping_semester" class="inputboxUpdate" onChange="this.form.submit();";>
													<option value="0" <?=($_datC['curriculum_mapping_semester']=="0"?"selected":"")?> >ภาคเรียนที่ 0</option>
													<option value="1" <?=($_datC['curriculum_mapping_semester']=="1"?"selected":"")?> >ภาคเรียนที่ 1</option>
													<option value="2" <?=($_datC['curriculum_mapping_semester']=="2"?"selected":"")?> >ภาคเรียนที่ 2</option>
												</select>
											<form>
										</td>
										<td align="center"><?=$_datC['curriculum_start_year']?></td>
										<td align="center"><?=$_datC['curriculum_status']?></td>
										<td align="center">
											<?=($_datC['SubjectCode']!=""?"<font color='green'>บรรจุแล้ว</font>":"ยังไม่บรรจุ")?>
										</td>
									</tr>
								<? } ?>
							</table>
						<? } else { 
								echo "<br/><br/>";
								echo "<font color='red'> ไม่พบหลักสูตร (แผนการเรียน) ที่ตรงตามเงื่อนไขของรายวิชา ขอให้กลับไปตรวจสอบหลักสูตรที่เปิดสอนอีกครั้ง </font>";
								echo "<br/><br/>";
						} ?>
					</div>
				</form>


				<div align="center">
					<?php
						if($_processing_text != ""){
							if($_processing_result){
								echo "<font color='green'>";
							}else{
								echo "<font color='red'>";
							}
							echo "<br/>";
							echo $_processing_text;
							echo "</font>";
						}
					?>
				</div>

				<div align="center">
					<br/><br/><br/>
					<table class="admintable">
						<tr height="35px">
							<td class="key" colspan="2"> &nbsp; ข้อมูลรายวิชา</td>
						</tr>
						<tr>
							<td align="right" width="200px"> รหัสวิชา </td>
							<td width="400px">
								<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
								<font size="4" color='green'><b><?=$_dat['SubjectCode']?></b></font> 
							</td>
						</tr>
						<tr>
							<td align="right"> ชื่อวิชา </td>
							<td>
								<?=$_dat['SubjectName']?>
								<input type="hidden" name="subject_name" value="<?=$_dat['SubjectName']?>" />
							</td>
						</tr>
						<tr>
							<td align="right"> หน่วยการเรียน </td>
							<td> 
								<?=$_dat['SubjectUnit']?>
							</td>
						</tr>
						<tr>
							<td align="right"> จำนวนชั่วโมง </td>
							<td> <?=$_dat['SubjectHour']?></td>
						</tr>
						<tr>
							<td align="right"> ประเภทวิชา </td>
							<td> 
								<?=$_dat['SubjectType']?>
							</td>
						</tr>
						<tr>
							<td align="right"> กลุ่มสาระการเรียนรู้ </td>
							<td> 
								<?php
									$_sqlGroup = "
										select g.* 
										from 
											curriculum_subject_group g inner join curriculum_subjects c
											on (g.subject_group_id = c.SubjectGroup)
										where 
											c.SubjectCode = '" . $_dat['SubjectCode'] . "'
										order by subject_group_id";
									$_resGroup = mysqli_query($_connection,$_sqlGroup);
									while($_datG = mysqli_fetch_assoc($_resGroup)){ 
										echo $_datG['subject_group_name'];
										} // end while ?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right"> ระดับชั้น </td>
							<td> 
								<?php
									if($_dat['SubjectLevel']=="0"){
										echo "เรียนได้อิสระ";
									}else{
										echo "ระดับชั้นมัธยมศึกษาปีที่ " . $_dat['SubjectLevel'];
									}
								?>
							</td>
						</tr>
						<tr>
							<td align="right"> ภาคเรียนที่เปิดสอน </td>
							<td> 
								<?php
									if($_dat['SubjectSemester']==0){
										echo "เปิดทุกภาคเรียน";
									}else if($_dat['SubjectSemester']==1){
										echo "เปิดเฉพาะภาคเรียนที่ 1";
									}else{
										echo "เปิดเฉพาะภาคเรียนที่ 2";
									}
								?>
							</td>
						</tr>
						<tr>
							<td align="right"> การจัดกลุ่มเรียน </td>
							<td> 
								<?
									if($_dat['is_split_class']==0){
										echo "แบ่งกลุ่มตามห้องเรียน";
									}else if($_dat['is_split_class']==1){
										echo "แบ่งกลุ่มอิสระในระดับชั้น";
									}else{
										echo "แบ่งกลุ่มอิสระในระดับการศึกษา";
									}
								?>
							</td>
						</tr>
						<tr>
							<td align="right"> สถานะรายวิชา </td>
							<td> 
								<?=($_dat['subject_status']=="ACTIVE"?"<font color='green'>เปิดสอน</font>":"<font color='red'>หยุดทำการสอน</font>")?> 
							</td>
						</tr>
						<tr>
							<td align="right" valign="top"> คำอธิบายรายวิชา </td>
							<td><?=$_dat['subject_description']?></td>
						</tr>
						<tr>
							<td align="right" valign="top"> เวลาแก้ไขล่าสุด </td>
							<td><?=$_dat['updated_datetime']?> / โดย <?=getUserAccountName($_connection,$_dat['updated_user_id'])?></td>
						</tr>
					</table>
				</div>
			<? } //end-if check existing subject
			else{
				echo "<div align='center'><br/><br/>";
				echo "<font color='red'>";
				echo "ไม่พบข้อมูลรหัสวิชา:" . $_POST['subject_search'] ." ในระบบ";
				echo "</font>";
				echo "</div>";
			} ?>
	<?	} ?>

</div>
