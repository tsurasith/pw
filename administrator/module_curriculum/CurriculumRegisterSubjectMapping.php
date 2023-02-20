<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear']))     { $acadyear = $_POST['acadyear'];}
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester'];}
?>

<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	$_subject_register_id = "";

	if(isset($_POST['SubjectCode'])){

		$_ops = "";
		$_sql_Operation = "";
		if(isset($_POST['mapping'])){
			$_subject_register_id = gen_uuid();

			$_sql_Operation = "
					INSERT INTO `register_subjects`(
						`subject_register_id`,
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
				        '" . $_subject_register_id . "' as subject_register_id,
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

		$_sql_subject_mapping  = "";
		$_sql_student_register = "";

		$_xlevel  = 0;
		$_xyearth = 0;

		$_xlevel  = $_POST['yearth']>3?4:3;
		if($_xlevel == 3){
			$_xyearth = $_POST['yearth'];
		}else{
			$_xyearth = $_POST['yearth']-3;
		}

		$_res = mysqli_query($_connection,$_sql_Operation);
		if($_res){
			if(isset($_POST['mapping'])){
				if($_POST['is_split_class']==0){
					$_sql_subject_mapping = "
							INSERT INTO `register_teachers`(
								`teacher_id`,
								`room_id`,
								`subject_level`,
								`SubjectCode`,
								`acadyear`,
								`acadsemester`,
								`is_split_class`,
								`created_datetime`,
								`created_user`,
								`updated_datetime`,
								`updated_user`,
								`subject_register_id`
							)
							select
								'00000000-0000-0000-0000-000000000000' as teacher_id,
								r.room_id,
								s.subject_level,
								s.SubjectCode,
								s.acadyear,
								s.acadsemester,
								s.is_split_class,
								CURRENT_TIMESTAMP as created_datetime,
								'". $_SESSION['user_account_id'] . "' as created_user,
								CURRENT_TIMESTAMP as updated_datetime ,
								'". $_SESSION['user_account_id'] . "' as updated_user,
								'" . $_subject_register_id . "' as subject_register_id
							from
								register_subjects s left join rooms r
								on (s.acadyear = r.acadyear and s.acadsemester = r.acadsemester)
							where
								s.SubjectCode   = '" . $_POST['SubjectCode'] ."' AND
								s.acadyear	    =	'" . $acadyear . "' AND
								s.acadsemester 	=	'" . $acadsemester . "' AND
								s.subject_level =   '" . $_POST['yearth'] . "' AND
								s.subject_level =   left(r.room_id,1)
							order by 
								r.room_id

							";
					$_sql_student_register = "
							INSERT INTO `register_students`(
								`student_id`,
								`SubjectCode`,
								`acadyear`,
								`acadsemester`,
								`created_datetime`,
								`created_user`,
								`updated_datetime`,
								`updated_user`,
								`subject_register_id`
							)
							SELECT 
								s.ID,
								r.SubjectCode,
								r.acadyear,
								r.acadsemester,
								CURRENT_TIMESTAMP as created_datetime,
								'". $_SESSION['user_account_id'] . "' as created_user,
								CURRENT_TIMESTAMP as updated_datetime ,
								'". $_SESSION['user_account_id'] . "' as updated_user,
								r.subject_register_id
							FROM
								register_subjects r left join students s
								on (r.acadyear = s.xedbe and r.SubjectCode = '" . $_POST['SubjectCode']. "')
							WHERE
								s.xlevel                  = '" . $_xlevel  . "'
								and s.xyearth             = '" . $_xyearth . "' 
								and xedbe                 = '" . $acadyear . "'
								and studstatus            = 1
								and r.subject_register_id = '" . $_subject_register_id . "'	
					";
				}else{
					$_sql_subject_mapping = "
							INSERT INTO `register_teachers`(
								`teacher_id`,
								`room_id`,
								`subject_level`,
								`SubjectCode`,
								`acadyear`,
								`acadsemester`,
								`is_split_class`,
								`created_datetime`,
								`created_user`,
								`updated_datetime`,
								`updated_user`,
								`subject_register_id`
							)
							select
								'00000000-0000-0000-0000-000000000000' as teacher_id,
								concat(s.subject_level,'00') as room_id,
								s.subject_level,
								s.SubjectCode,
								s.acadyear,
								s.acadsemester,
								s.is_split_class,
								CURRENT_TIMESTAMP as created_datetime,
								'". $_SESSION['user_account_id'] . "' as created_user,
								CURRENT_TIMESTAMP as updated_datetime ,
								'". $_SESSION['user_account_id'] . "' as updated_user,
								'" . $_subject_register_id . "' as subject_register_id
							from
								register_subjects s 
							where
								s.SubjectCode   = '" . $_POST['SubjectCode'] ."' AND
								s.acadyear	    =	'" . $acadyear . "' AND
								s.acadsemester 	=	'" . $acadsemester . "' AND
								s.subject_level =   '" . $_POST['yearth'] . "' 
						";
				}
			}else{
				$_sql_subject_mapping = "
						delete from register_teachers 
						where
								SubjectCode   = '" . $_POST['SubjectCode'] ."' AND
								acadyear	    =	'" . $acadyear . "' AND
								acadsemester 	=	'" . $acadsemester . "' AND
								subject_level =   '" . $_POST['yearth'] . "' 
				";
			}

			// insert register_teachers
			$_regis_teaecher = mysqli_query($_connection,$_sql_subject_mapping);

			// insert register_students when teaching by classroom
			if($_POST['is_split_class']==0 && isset($_POST['mapping'])){
				$_res_regis_student = mysqli_query($_connection,$_sql_student_register);
			}
			//echo $_sql_subject_mapping;
			//echo $_sql_student_register;


			$_processing_text  = "รายวิชา - " . $_POST['SubjectCode'] . ': ' . $_POST['SubjectName'] . " ";
			$_processing_text .= "ได้" . $_ops . "ให้ลงทะเบียนเรียนสำหรับนักเรียนชั้นมัธยมศึกษาปีที่ ";
			$_processing_text .= $_POST['yearth'] . " ภาคเรียนที่ " . $acadsemester . " ปีการศึกษา " . $acadyear . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= $_processing_text;
			$_text .= " " . "โดย - " . $_SESSION['shortname'];

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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึก ประมวลผลการเปิด/ยกเลิก รายวิชาสำหรับการลงทะเบียนเรียนได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึก <b>ซ้ำหลายรอบระหว่างระบบประมวลผล</b> หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ - " . mysqli_error($_connection);
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
				sr.SubjectCode    as SubjectCode_register,
				sr.is_split_class as r_is_split_class,
				rg.total_student
			from
				curriculum_subject_mappings m inner join register_curriculums r 
				on (m.curriculum_id = r.curriculum_id and m.curriculum_mapping_semester = r.acadsemester)
				inner join curriculum_subjects s 
				on (m.SubjectCode = s.SubjectCode)
				left join register_subjects sr 
				on (r.acadyear = sr.acadyear and r.acadsemester = sr.acadsemester and s.SubjectCode = sr.SubjectCode)
				left join
				    (
						select 
							SubjectCode,
							count(*) as 'total_student'
						from
							register_students
						where
							acadyear     = '" . $acadyear . "' and
							acadsemester = '" . $acadsemester . "' 
						group by
						    SubjectCode,acadyear,acadsemester
					) as rg
				on (m.SubjectCode = rg.SubjectCode)
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
							<td class="key" width="80px" align="center">รหัสวิชา</td>
							<td class="key" width="220px" align="center">ชื่อวิชา</td>
							<td class="key" width="75px" align="center">หน่วย<br/>การเรียน</td>
							<td class="key" width="60px" align="center">ชั่วโมง</td>
							<td class="key" width="130px" align="center">ประเภทวิชา</td>
							<td class="key" width="90px" align="center">รูปแบบ<br/>การสอน</td>
							<td class="key" width="70px" align="center">นักเรียน<br/>ลงทะเบียน</td>
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
										$_split_class = "";

										if($_dat['r_is_split_class']!=""){
											$_split_class = $_dat['r_is_split_class'];
										}else{
											$_split_class = $_dat['is_split_class'];
										}

										if($_split_class==0){
											echo "จัดตามห้อง";
										}else if($_split_class==1){
											echo "คละห้อง";
										}else{
											echo "คละระดับชั้น";
										}
									?>
								</td>
								<td align="center">
									<?=$_dat['total_student']?>
								</td>
								<td align="center">
									<?=($_dat['SubjectCode_register']!=""?"<font color='green'>เปิดลงทะเบียน</font>":"ไม่เปิดลงทะเบียน")?>
								</td>
								<td align="center">
								<? if($_dat['SubjectCode_register']!=""){ ?>
												<form method="post" action="index.php?option=module_curriculum/RegisterSubjectEdit">
													<input type="hidden" name="subject_name" value="<?=$_dat['SubjectName']?>" />
													<input type="hidden" name="yearth" value="<?=$_POST['yearth']?>" />
													<input type="hidden" name="SubjectCode" value="<?=$_dat['SubjectCode']?>" />
													<input type="hidden" name="acadyear" value="<?=$acadyear?>" />
													<input type="hidden" name="acadsemester" value="<?=$acadsemester?>" />
													<input type="submit" name="modify_curr" value="แก้ไขเพิ่มเติม" />
												</form>
											<? } ?>
								</td>
							</tr>
						<? } ?>
					</table>
				<? } else { 
						echo "<br/><br/>";
						echo "<font color='red'> ไม่พบรายวิชาที่สามารถเปิดลงทะเบียนเรียนได้ ขอให้กลับไปตรวจสอบหลักสูตรที่เปิดสอนอีกครั้ง </font>";
						echo "<br/><br/>";
				} ?>
			</div>
		</form>
	<? } ?>
</div>

