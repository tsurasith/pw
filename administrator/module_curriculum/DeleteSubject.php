<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['confirmDelete']) && trim($_POST['subject_code']) != ""){

		$_sql_insert = "
			DELETE FROM `curriculum_subjects`
				WHERE `SubjectCode` = '" . $_POST['subject_code'] . "'
			";

		$_sqlDel_mapping = "
			DELETE FROM `curriculum_subject_mappings`
				WHERE `SubjectCode` = '" . $_POST['subject_code'] . "'
			";
		$_res2 = mysqli_query($_connection,$_sqlDel_mapping);
		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res && $_res2){
			$_processing_text = "ระบบได้ลบวิชา: <b>" . $_POST['subject_code'] . " " . $_POST['subject_name'] . "</b> ออกจากระบบเรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "รหัสวิชา: ";
			$_text .= trim($_POST['subject_code']) . " ถูกลบออกจากระบบ ";
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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถลบ วิชาเรียน ได้" . "<br/>";
			$_processing_text .= "แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "ขออภัยไม่พบข้อมูลที่ต้องการ";
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
					<span class="normal"><font color="#0066FF"><strong>3.3 ลบ รายวิชาที่เปิดสอน</strong></font></span>
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
			
			<? if($_rows>0){ ?>


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
						m.SubjectCode
					FROM
						curriculums c inner join curriculum_subject_mappings m 
						on (c.curriculum_id = m.curriculum_id and m.SubjectCode = '". trim($_POST['subject_search']) . "')
					order by
						c.curriculum_level,c.curriculum_code
					";

					$_resC = mysqli_query($_connection,$_sqlCurr);
					$_rowC = mysqli_num_rows($_resC);
					$_j = 1;
				?>

					<div align="center">
						<? if($_rowC >0){ ?> 
							<table class="admintable">
								<tr height="35px">
									<td class="key" colspan="6"> &nbsp; หลักสูตรที่รายวิชานี้บรรจุอยู่ในแผนการเรียน</td>
								</tr>
								<tr height="35px"> 
									<td class="key" width="30px" align="center">ที่</td>
									<td class="key" width="85px" align="center">รหัสหลักสูตร</td>
									<td class="key" width="210px" align="center">ชื่อหลักสูตร (แผนการเรียน)</td>
									<td class="key" width="95px" align="center">ระดับการศึกษา</td>
									<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
									<td class="key" width="80px" align="center">สถานะ<br/>หลักสูตร</td>
								</tr>
								<? while($_datC = mysqli_fetch_assoc($_resC)){ ?>
									<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
										<td align="center"><?=$_j++?></td>
										<td align="center"><?=$_datC['curriculum_code']?></td>
										<td> &nbsp; <?=$_datC['curriculum_name']?></td>
										<td align="center"><?=displayEducationLevel($_datC['curriculum_level'])?></td>
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
					<br/><br/><br/>
					<form action="" method="post" autocomplete="off">
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="2"> &nbsp; รายละเอียดของรายวิชาที่ต้องการลบ</td>
							</tr>
							<tr>
								<td align="right" width="160px"> รหัสวิชา </td>
								<td width="440px">
									<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
									<font size="4" color='red'><b><?=$_dat['SubjectCode']?></b></font> 
								</td>
							</tr>
							<tr>
								<td align="right" > ชื่อวิชา </td>
								<td>
									<?=$_dat['SubjectName']?>
									<input type="hidden" name="subject_name" value="<?=$_dat['SubjectName']?>" />
								</td>
							</tr>
							<tr>
								<td align="right" > หน่วยการเรียน </td>
								<td> 
									<?=$_dat['SubjectUnit']?>
								</td>
							</tr>
							<tr>
								<td align="right" > จำนวนชั่วโมง </td>
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
											echo "แบ่งกลุ่มตามห้องเรียน";
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
							<tr>
								<td></td>
								<td>
									<br/>
									<input type="submit" name="confirmDelete" class="button" value="ยืนยันลบ" /> 
								</td>
							</tr>
						</table>
					</form>
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

	<? if(isset($_POST['confirmDelete'])){ ?>
		<div align='center'>
			<br/><br/>
		<?	if($_processing_result){
				echo "<font color='green'>";
				echo $_processing_text;
				echo "</font>";
			}else{
				echo "<font color='red'>";
				echo $_processing_text;
				echo "</font>";
			}
		?>
		<br/><br/>
		<form>
		<input type='button' value='ดำเนินการต่อ' onClick="location.href='index.php?option=module_curriculum/DeleteSubject';" />
		</form><br/>
		</div>
	<? } ?>
</div>

