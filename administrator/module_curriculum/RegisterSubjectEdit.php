<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";


	// รูปการการจัดการสอน (จัดตามห้องเรียน,คละห้อง,คละระดับชั้น)
	if(isset($_POST['r_is_split_class']) && $_POST['r_is_split_class']!=""){
		$_sql_teacher_register  = "";
		$_sql_student_register  = "";

		$_sql_subject_register = "
				UPDATE 
					register_subjects 
				SET 
					is_split_class = '" . $_POST['r_is_split_class'] . "'
				WHERE
					subject_register_id = '" . $_POST['subject_register_id'] . "'
			";
		$_res_subject = mysqli_query($_connection,$_sql_subject_register);

		if($_res_subject){
			$_sql_teacher_register = "delete from register_teachers where subject_register_id = '" . $_POST['subject_register_id'] . "'";
			$_res_teacher = mysqli_query($_connection,$_sql_teacher_register);

			if($_POST['r_is_split_class']=="0"){
				$_xlevel  = 0;
				$_xyearth = 0;

				$_xlevel  = $_POST['yearth']>3?4:3;
				$_xyearth = $_POST['yearth']%3;

				$_sql_delete = "
					delete from register_students
						where
							subject_register_id = '" . $_POST['subject_register_id'] . "'
				";
				mysqli_query($_connection,$_sql_delete);

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
							and xedbe                 = '" . $_POST['acadyear'] . "'
							and studstatus            = 1
							and r.subject_register_id = '" . $_POST['subject_register_id'] . "'			
					";
				$_sql_teacher_register = "
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
							'" . $_POST['r_is_split_class']. "',
							CURRENT_TIMESTAMP as created_datetime,
							'". $_SESSION['user_account_id'] . "' as created_user,
							CURRENT_TIMESTAMP as updated_datetime ,
							'". $_SESSION['user_account_id'] . "' as updated_user,
							'" . $_POST['subject_register_id'] . "' as subject_register_id
						from
							register_subjects s left join rooms r
							on (s.acadyear = r.acadyear and s.acadsemester = r.acadsemester)
						where
							s.SubjectCode   = '" . $_POST['SubjectCode'] ."' AND
							s.acadyear	    = '" . $_POST['acadyear'] . "' AND
							s.acadsemester 	= '" . $_POST['acadsemester'] . "' AND
							s.subject_level = '" . $_POST['yearth'] . "' AND
							s.subject_level = left(r.room_id,1)
						order by 
							r.room_id
					";
			}else{
				$_sql_teacher_register = "
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
							'" . $_POST['r_is_split_class']. "',
							CURRENT_TIMESTAMP as created_datetime,
							'". $_SESSION['user_account_id'] . "' as created_user,
							CURRENT_TIMESTAMP as updated_datetime ,
							'". $_SESSION['user_account_id'] . "' as updated_user,
							'" . $_POST['subject_register_id'] . "' as subject_register_id
						from
							register_subjects s 
						where
							s.SubjectCode   = '" . $_POST['SubjectCode'] ."' AND
							s.acadyear	    = '" . $_POST['acadyear'] . "' AND
							s.acadsemester 	= '" . $_POST['acadsemester'] . "' AND
							s.subject_level = '" . $_POST['yearth'] . "' 
					";

				$_sql_student_register = "
						delete from register_students
						where
							subject_register_id = '" . $_POST['subject_register_id'] . "'
					";
			}
		}
		//echo $_sql_teacher_register . "<br/>";

		$_res_teacher = mysqli_query($_connection,$_sql_teacher_register);
		$_res_student = mysqli_query($_connection,$_sql_student_register);
		
		//echo $_sql_student_register ."<br/>";

		if($_res_teacher && $_res_student){
			$_processing_result = true;
			$_processing_text   = "บันทึกแก้ไขรูปแบบการสอนสำหรับการลงทะเบียนเรียยร้อยแล้ว";
		}else{
			$_processing_result = false;
			$_processing_text   = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง หรือแจ้งข้อความนี้ต่อผู้ดูแลระบบ - " . mysqli_error($_connection);
		}

	}


	// การจัดครูผู้สอน teacher mapping on table: register_teachers
	if(isset($_POST['teacher_id']) && $_POST['teacher_id'] != ""){

		$_ops = "";
		$_sql_Operation = "";
		
		$_sql_Operation = "
			UPDATE `register_teachers`
			SET
				`teacher_id`		   = '" . $_POST['teacher_id'] . "',
				`updated_datetime`     = CURRENT_TIMESTAMP,
				`updated_user`         = '". $_SESSION['user_account_id'] . "'
			WHERE
				`teacher_register_id`  = '" . $_POST['teacher_register_id'] . "' AND
				`subject_register_id`  = '" . $_POST['subject_register_id'] . "'
			";
		$_ops = "เพิ่มข้อมูล";
	
		//echo $_sql_Operation;

		$_res = mysqli_query($_connection,$_sql_Operation);
		if($_res){
			$_processing_text  = "ระบบได้ทำการ " . $_ops . "ครูผู้สอนรายวิชา " . $_POST['SubjectCode'] . " - " . $_POST['subject_name'];
			$_processing_text .= " ห้อง " . $_POST['room'] . " ภาคเรียนที่ " . $_POST['acadsemester'] . '/' . $_POST['acadyear'] . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= $_processing_text;
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
				<td ><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>4.2 จัดการรายวิชาสำหรับลงทะเบียนเรียน (แก้ไขเพิ่มเติม)</strong></font></span>
				</td>
				<td align="right" style="padding-right:60px;">
					ปีการศึกษา <?=$_POST['acadyear']?>
					ภาคเรียนที่ <?=$_POST['acadsemester']?>
					<br/><br/>
					<form method="post" action="index.php?option=module_curriculum/CurriculumRegisterSubjectMapping&acadyear=<?=$_POST['acadyear']?>&acadsemester=<?=$_POST['acadsemester']?>">
						<input type="hidden" name="yearth" value="<?=isset($_POST['yearth'])?$_POST['yearth']:""?>"/>
						<input type="submit" name="search" class="button" value="ย้อนกลับ" />
					</form>
				</td>
			</tr>
	</table>
  
	<?php
		if(isset($_POST['SubjectCode']) && trim($_POST['SubjectCode']) != ""){ ?>
		<?php
			$_sqlSearch = "
				select
					r.subject_register_id,
					r.subject_level,
					r.acadyear,
					r.acadsemester,
					r.is_split_class as r_is_split_class,
					s.*
				from 
					register_subjects r inner join curriculum_subjects s
					on (r.SubjectCode = s.SubjectCode)
				where 
					r.SubjectCode  = '" . trim($_POST['SubjectCode']) . "' and
					r.acadyear     = '" . $_POST['acadyear'] . "' and
					r.acadsemester = '" . $_POST['acadsemester'] . "'
				 ";
			$_resSearch = mysqli_query($_connection,$_sqlSearch);
			$_rows = mysqli_num_rows($_resSearch);

			//echo $_sqlSearch . "<br/>";

		?>
			
			<? if($_rows>0){ ?>


				<?php
				$_dat = mysqli_fetch_assoc($_resSearch); // search result subject details

				$_sqlRegisSubject = "";

				if($_dat['r_is_split_class']==0){ 

					$_sqlRegisSubject = "
							SELECT
								rg.registered,
								rt.*
							FROM
								register_subjects rs
								INNER JOIN register_teachers rt ON
									(
										rs.SubjectCode  = rt.SubjectCode AND 
										rs.acadyear     = rt.acadyear AND
										rs.acadsemester = rt.acadsemester AND
										rs.subject_register_id = rt.subject_register_id
									)
								LEFT JOIN
									(
										select 
											concat(((s.xlevel-3)*3)+s.xyearth,'0',s.room) as 'class_id',
											count(*) as 'registered'
										from 
											register_students r inner join students s
											on (
												r.student_id  = s.ID and 
												r.acadyear    = s.xEDBE and 
												r.subject_register_id = '" . $_dat['subject_register_id'] . "'
												)
										group by
											concat(((s.xlevel-3)*3)+s.xyearth,'0',s.room)
									) rg
								ON (rt.room_id = rg.class_id)

							WHERE
								rs.subject_register_id = '" . $_dat['subject_register_id'] . "'
							ORDER BY
								rt.room_id
					";
				}else{
					$_sqlRegisSubject = "
							SELECT
								rg.registered,
								rt.*
							FROM
								register_subjects rs
								INNER JOIN register_teachers rt ON
									(
										rs.SubjectCode  = rt.SubjectCode AND 
										rs.acadyear     = rt.acadyear AND
										rs.acadsemester = rt.acadsemester AND
										rs.subject_register_id = rt.subject_register_id
									)
								LEFT JOIN
									(
										select 
											concat(((s.xlevel-3)*3)+s.xyearth,'00') as 'class_id',
											count(*) as 'registered'
										from 
											register_students r inner join students s
											on (
												r.student_id  = s.ID and 
												r.acadyear    = s.xEDBE and 
												r.subject_register_id = '" . $_dat['subject_register_id'] . "'
												)
										group by
											concat(((s.xlevel-3)*3)+s.xyearth,'00')
									) rg
								ON (rt.room_id = rg.class_id)

							WHERE
								rs.subject_register_id = '" . $_dat['subject_register_id'] . "'
							ORDER BY
								rt.room_id
					";
				}

					$_resC = mysqli_query($_connection,$_sqlRegisSubject);
					$_rowC = mysqli_num_rows($_resC);

					//echo $_sqlRegisSubject;
				?>

				<form method="post" action="">
					<div align="center">
						<? if($_rowC >0){ ?> 
							<table class="admintable">
								<tr height="45px">
									<td class="key" colspan="2" align="center">
										<font size="5">ข้อมูลรายวิชา</font>
									</td>
								</tr>
								<tr>
									<td align="right" width="150px">การจัดการเรียน:</td>
									<td width="400px">
										<font size="4">ภาคเรียนที่ <?=$_dat['acadsemester'].'/'.$_dat['acadyear']?></font>
									</td>
								</tr>
								<tr>
									<td align="right">รหัสวิชา:</td>
									<td>
										<font size="4"><?=$_dat['SubjectCode']?></font>
									</td>
								</tr>
								<tr>
									<td align="right">ชื่อวิชา:</td>
									<td>
										<font size="4"><?=$_dat['SubjectName']?></font>
									</td>
								</tr>
								<tr>
									<td align="right"></td>
									<td>
										<font size="4">
											หน่วยการเรียน <?=$_dat['SubjectUnit']?> 
											ชั่วโมงเรียน <?=$_dat['SubjectHour']?>
										</font>
									</td>
								</tr>
								<tr>
									<td align="right">การจัดการเรียน:</td>
									<td>
										<form method="post">
											<select name="r_is_split_class" class="inputboxUpdate" onChange="this.form.submit();";>
												<option value=""> - ไม่มีข้อมูล - </option>
												<option value="0" <?=$_dat['r_is_split_class']=="0"?"selected":""?> >จัดตามห้องเรียน</option>
												<option value="1" <?=$_dat['r_is_split_class']=="1"?"selected":""?> >คละห้อง</option>
											</select>
											<input type="hidden" name="subject_register_id" value="<?=$_dat['subject_register_id']?>" />
											<input type="hidden" name="subject_name"        value="<?=$_dat['SubjectName']?>" />
											<input type="hidden" name="yearth"              value="<?=$_POST['yearth']?>" />
											<input type="hidden" name="SubjectCode"         value="<?=$_dat['SubjectCode']?>" />
											<input type="hidden" name="acadyear"            value="<?=$_dat['acadyear']?>" />
											<input type="hidden" name="acadsemester"        value="<?=$_dat['acadsemester']?>" />
										</form>
									</td>
								</tr>
							</table>
							<br/>

							<table class="admintable">
								<tr height="35px">
									<td class="key" colspan="7">
										 &nbsp; 
										 รายละเอียดครูผู้สอนและการลงทะเบียนเรียนของนักเรียน
									</td>
								</tr>
								<tr height="35px"> 
									<!--<td class="key" width="25px" align="center">-</td>-->
									<td class="key" width="90px" align="center">ห้อง</td>
									<td class="key" width="210px" align="center">ครูผู้สอน</td>
									<td class="key" width="100px" align="center">จำนวนนักเรียน<br/>ลงทะเบียน</td>
									<td class="key" width="220px" align="center">สถานะ<br/>การเรียนการสอน</td>
								</tr>
								<? while($_datC = mysqli_fetch_assoc($_resC)){ ?>
									<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
										<td align="center">
											<?php
												echo substr($_datC['room_id'],0,1) . '/' . (int) substr($_datC['room_id'],-2,2);	
											?>
										</td>
										<td align="left"  >
											<form method="post">
												<?php 
													$sql_teacher = " 
														SELECT
															t.teacher_id,
															t.firstname,
															t.lastname
														FROM
															teachers t
														WHERE
															t.type != 'xcancel'
														ORDER BY
															CONVERT(t.firstname using tis620),
															CONVERT(t.lastname  using tis620)
														" ;
														//echo $sql_Room ;
														$resTeacher = mysqli_query($_connection,$sql_teacher);	
												?>
												<select name="teacher_id" class="inputboxUpdate" onChange="this.form.submit();">
													<option value="00000000-0000-0000-0000-000000000000"> - ไม่ระบุ - </option>
													<?php
														$_select = "";
														while($datSelect = mysqli_fetch_assoc($resTeacher))
														{
															$_select = ($datSelect['teacher_id'] == $_datC['teacher_id']?"selected":"");
															echo "<option value=" . $datSelect['teacher_id'] . " $_select>";
															echo $datSelect['firstname']. ' ' . $datSelect['lastname'];
															echo "</option>";
														}
													?>
												</select>
												<input type="hidden" name="subject_register_id" value="<?=$_dat['subject_register_id']?>" />
												<input type="hidden" name="teacher_register_id" value="<?=$_datC['teacher_register_id']?>" />
												<input type="hidden" name="subject_name"        value="<?=$_dat['SubjectName']?>" />
												<input type="hidden" name="yearth"              value="<?=$_POST['yearth']?>" />
												<input type="hidden" name="SubjectCode"         value="<?=$_dat['SubjectCode']?>" />
												<input type="hidden" name="acadyear"            value="<?=$_dat['acadyear']?>" />
												<input type="hidden" name="acadsemester"        value="<?=$_dat['acadsemester']?>" />
												<input type="hidden" name="room"                value="<?=substr($_datC['room_id'],0,1) . '/' . (int) substr($_datC['room_id'],-2,2)?>" />
											</form>
										</td>
										<td align="center"><?=$_datC['registered']?></td>
										<td align="center">
											<?php
												if($_datC['teacher_id'] == "00000000-0000-0000-0000-000000000000"){
													echo "<font color='red'>ยังไม่ระบุครูผู้สอน</font>";
												}else if(trim($_datC['registered'])==""){
													echo "<font color='red'>ยังไม่มีนักเรียนลงทะเบียนเรียน</font>";
												}else{
													echo "<font color='green'>ครบถ้วนสมบูรณ์</font>";
												}

												$_subject_info = $_POST['yearth'] . "|" . $_dat['SubjectCode'] . "|" . $_dat['subject_register_id'] . "|" . $_datC['teacher_id'] . "|" . $_dat['SubjectName'];
												
											?>
										</td>
										<td align="center">
											<?php
												if(substr($_datC['room_id'],-2,2)=="000"){
													//concat(r.subject_level,'|',r.SubjectCode,'|',r.subject_register_id,'|',r.teacher_id,'|',s.SubjectName) as subject_info
													?>
													<form method="post" action="index.php?option=module_curriculum/RegisterStudentByLevel">
														<input type="hidden" name="subject_info" value="<?=$_subject_info?>"/>
														<input type="hidden" name="acadyear"     value="<?=$_dat['acadyear']?>"/>
														<input type="hidden" name="acadsemester" value="<?=$_dat['acadsemester']?>"/>
														<input type="submit" name="search" value="ไปหน้าลงทะเบียน" />
													</form>
												<? } ?>
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
				<br/>

				<div align="center">
					<br/>
								<?php
									if (((isset($_POST['r_is_split_class']) && $_POST['r_is_split_class']!="")) || ((isset($_POST['teacher_id']) && $_POST['teacher_id'] != ""))) {
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
				</div>

				<div align="center">
					<br/><br/><br/>
					<table class="admintable">
						<tr height="35px">
							<td class="key" colspan="2"> &nbsp; ข้อมูลพื้นฐานรายวิชาจากฐานข้อมูลหลักสูตร</td>
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
				echo "ไม่พบข้อมูลรหัสวิชา:" . $_POST['SubjectCode'] ." ในระบบ";
				echo "</font>";
				echo "</div>";
			} ?>
	<?	} ?>

</div>

