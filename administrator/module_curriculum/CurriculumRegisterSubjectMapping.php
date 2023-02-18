<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
?>

<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['SubjectCode'])){

		$_ops = "";
		$_sql_Operation = "";
		if(isset($_POST['mapping'])){
			$_sql_Operation = "
					INSERT INTO `register_subjects`(
						`subject_level`,
						`SubjectCode`,
						`acadyear`,
						`acadsemester`,
						`is_split_class`,
						`created_datetime`,
						`created_user`,
						`updated_datetime`,
						`updated_user`
					)
					SELECT distinct
						m.curriculum_mapping_level as subject_level,
						m.SubjectCode,
						r.acadyear,
						r.acadsemester,
						s.is_split_class,
						CURRENT_TIMESTAMP as created_datetime,
						'". $_SESSION['user_account_id'] . "' as created_user,
						CURRENT_TIMESTAMP as updated_datetime ,
						'". $_SESSION['user_account_id'] . "' as updated_user
					from
						curriculum_subject_mappings m inner join register_curriculums r 
						on (m.curriculum_id = r.curriculum_id and m.curriculum_mapping_semester = r.acadsemester)
						left join register_subjects sr 
						on (r.acadyear = sr.acadyear and r.acadsemester = sr.acadsemester)
						left join curriculum_subjects s 
						on (m.SubjectCode = s.SubjectCode)
					where
						m.curriculum_mapping_level = '" . $_POST['yearth'] . "' and
						r.acadyear      = '" . $acadyear . "' and
						r.acadsemester  = '" . $acadsemester . "' and
						m.SubjectCode   = '" . $_POST['SubjectCode'] . "' 
					
				";
			$_ops = "เปิด";
		}else{
			$_sql_Operation = "
				DELETE FROM `register_subjects`
				WHERE
					`SubjectCode`   = 	'" . $_POST['SubjectCode'] . "' AND
					`subject_level`	=	'" . $_POST['yearth'] . "' AND
					`acadyear`	    =	'" . $acadyear . "' AND
					`acadsemester`	=	'" . $acadsemester . "' 
				";
			$_ops = "ยกเลิก";
		}

		//echo $_sql_Operation . "<br/>";

		$_res = mysqli_query($_connection,$_sql_Operation);
		if($_res){
			$_processing_text  = "ระบบได้" .$_ops . "ให้ลงทะเบียนเรียนหลักสูตร: ";
			$_processing_text .= $_POST['SubjectUnit'] . " ภาคเรียนที่ " . $acadsemester . " ปีการศึกษา " . $acadyear . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "แผนการเรียน: " . $_POST['SubjectUnit'] . " ";
			$_text .= ($_ops=="เปิด"?"เปิดให้ลงทะเบียน":"ยกเลิกการลงทะเบียน"). " ภาคเรียนที่ " . $acadsemester . " ปีการศึกษา " . $acadyear . " เรียบร้อยแล้ว";
			$_text .= "" . "โดย - " . $_SESSION['shortname'];

			$message = $_text;
			//SendLineMessage($message,$_line_token);

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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึก ประมวลผลการเปิด/ยกเลิก การลงทะเบียนเรียนหลักสูตรได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึก <b>หลักสูตรที่ต้องการลงทะเบียน</b> ซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ - " . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "เกิดข้อผิดพลาด ไม่สามารถระบุสาเหตุได้ ขอให้แจ้งผู้ดูแลระบบ";
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
					<span class="normal"><font color="#0066FF"><strong>4.2 จัดการรายวิชาสำหรับลงทะเบียนเรียน</strong></font></span>
				</td>
				<td >
					<?php
						if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
						if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
					?>
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_curriculum/CurriculumRegisterSubjectMapping&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/CurriculumRegisterSubjectMapping&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/CurriculumRegisterSubjectMapping&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/CurriculumRegisterSubjectMapping&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
					?>
					<font size="2" color="#000000">
						<form name="level" method="post">
							<select name="yearth" class="inputboxUpdate" onchange="document.level.submit()">
								<option value=""></option>
								<option value="1" <?=isset($_POST['yearth']) && $_POST['yearth']=="1"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 1</option>
								<option value="2" <?=isset($_POST['yearth']) && $_POST['yearth']=="2"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 2</option>
								<option value="3" <?=isset($_POST['yearth']) && $_POST['yearth']=="3"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 3</option>
								<option value="4" <?=isset($_POST['yearth']) && $_POST['yearth']=="4"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 4</option>
								<option value="5" <?=isset($_POST['yearth']) && $_POST['yearth']=="5"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 5</option>
								<option value="6" <?=isset($_POST['yearth']) && $_POST['yearth']=="6"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 6</option>
							</select>
						</form>
					</font>
				</td>
			</tr>
	</table>
 
	<? if(isset($_POST['yearth']) && $_POST['yearth'] != ""){ ?> 
		<?php


			$_sqlCurr = "
			select distinct
				-- r.curriculum_id,
				m.SubjectCode,
				s.SubjectName,
				s.SubjectUnit,
				s.SubjectHour,
				s.SubjectType,
				s.SubjectGroup,
				m.curriculum_mapping_level,
				s.is_split_class,
				sr.SubjectCode as SubjectCode_register
			from
				curriculum_subject_mappings m inner join register_curriculums r 
				on (m.curriculum_id = r.curriculum_id and m.curriculum_mapping_semester = r.acadsemester)
				inner join curriculum_subjects s 
				on (m.SubjectCode = s.SubjectCode)
				left join register_subjects sr 
				on (r.acadyear = sr.acadyear and r.acadsemester = sr.acadsemester and s.SubjectCode = sr.SubjectCode)
				
			where
				m.curriculum_mapping_level = '" . $_POST['yearth'] . "' and
				r.acadyear      = '" . $acadyear . "' and
				r.acadsemester  = '" . $acadsemester . "' 
			order by
				FIELD(s.SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'),
				s.SubjectGroup,
				m.SubjectCode
			";

			//echo $_sqlCurr;

			$_resC = mysqli_query($_connection,$_sqlCurr);
			$_rowC = mysqli_num_rows($_resC);
			$_order = 1;
		?>

		<form method="post" action="">
			<div align="center">
				<? if($_rowC >0){ ?> 
					<table class="admintable">
						<tr height="35px">
							<td class="key" colspan="10"> &nbsp; รายวิชาเปิดให้ลงทะเบียนเรียนภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> </td>
						</tr>
						<tr height="35px"> 
							<td class="key" width="25px" align="center" colspan="2">-</td>
							<td class="key" width="90px" align="center">รหัสวิชา</td>
							<td class="key" width="250px" align="center">ชื่อวิชา</td>
							<td class="key" width="95px" align="center">หน่วยการเรียน</td>
							<td class="key" width="60px" align="center">ชั่วโมง</td>
							<td class="key" width="140px" align="center">ประเภทวิชา</td>
							<td class="key" width="100px" align="center">รูปแบบการสอน</td>
							<td class="key" width="120px" align="center">สถานะสำหรับ<br/>ลงทะเบียน</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
							<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
								<td align="right"><?=$_order++?></td>
								<td align="center">
									<? $_select = ($_dat['SubjectCode']==$_dat['SubjectCode_register']?"checked":""); ?>
									<form method="post" action="">
										<input type="checkbox" name="mapping" value="10"
											onChange="this.form.submit();"; <?=$_select?> />
										<input type="hidden" name="SubjectCode" value="<?=$_dat['SubjectCode']?>" />
										<input type="hidden" name="SubjectName" value="<?=$_dat['SubjectName']?>" />
										<input type="hidden" name="is_split_class" value="<?=$_dat['is_split_class']?>" />
										<input type="hidden" name="yearth" value="<?=$_POST['yearth']?>" />
									</form>
								</td>
								<td align="center"><?=$_dat['SubjectCode']?></td>
								<td align="left"><?=$_dat['SubjectName']?></td>
								<td align="center"><?=$_dat['SubjectUnit']?></td>
								<td align="center"><?=$_dat['SubjectHour']?></td>
								<td align="center"><?=$_dat['SubjectType']?></td>
								<td align="center">
									<?php
										if($_dat['is_split_class']==0){
											echo "จัดตามห้อง";
										}else if($_dat['is_split_class']==1){
											echo "คละห้อง";
										}else{
											echo "คละระดับชั้น";
										}
									?>
								</td>
								<td align="center">
									<?=($_dat['SubjectCode_register']!=""?"<font color='green'>เปิดลงทะเบียน</font>":"ไม่เปิดลงทะเบียน")?>
								</td>
								<td align="center">
								<? if($_dat['SubjectCode_register']!=""){ ?>
												<form method="post" action="index.php?option=module_curriculum/CurriculumMappingMoreEdit">
													<input type="hidden" name="subject_code_mapping" value="<?=$_dat['SubjectCode']?>" />
													<input type="hidden" name="curriculum_id" value="<?=$_datC['curriculum_id']?>" />
													<input type="hidden" name="curriculum_code" value="<?=$_datC['curriculum_code']?>" />
													<input type="hidden" name="curriculum_name" value="<?=$_datC['curriculum_name']?>" />
													<input type="hidden" name="subject_name" value="<?=$_dat['SubjectName']?>" />
													<input type="hidden" name="subject_search" value="<?=$_dat['SubjectCode']?>" />
													<input type="submit" name="modify_curr" value="แก้ไขเพิ่มเติม" />
												</form>
											<? } ?>
								</td>
							</tr>
						<? } ?>
					</table>
				<? } else { 
						echo "<br/><br/>";
						echo "<font color='red'> ไม่พบหลักสูตรและรายวิชาที่สามารถเปิดลงทะเบียนเรียนได้ ขอให้กลับไปตรวจสอบหลักสูตรที่เปิดสอนอีกครั้ง </font>";
						echo "<br/><br/>";
				} ?>
			</div>
		</form>
	<? } ?>
</div>

