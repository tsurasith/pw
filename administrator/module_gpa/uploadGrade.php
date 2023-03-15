

  <?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear']))     { $acadyear = $_POST['acadyear'];}
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester'];}

	$_teacher_id = "";

	if(
		$_SESSION['username'] == "admin" || 
		$_SESSION['username'] == "tc100" || 
		$_SESSION['username'] == "tc101" || 
		$_SESSION['username'] == "tc102" || 
		$_SESSION['username'] == "tc103" ||
		$_SESSION['username'] == "tc114" ||
		$_SESSION['username'] == "tc110" ||
		$_SESSION['username'] == "tc137" || $_SESSION['username'] == "tc111" ||
		$_SESSION['username'] == "tc144" || $_SESSION['username'] == "tc113" 
	) {
		if(isset($_REQUEST['teacher_id'])){
			$_teacher_id = $_REQUEST['teacher_id'];
		}
	}else{
		$_teacher_id = $_SESSION['user_account_id'];
	}
?>


<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      			<td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        			<span class="normal"><font color="#0066FF"><strong>3.4 อัปโหลดผลกาาเรียน (วิชาพื้นฐาน,เพิ่มเติม)</strong></font></span></td>
				<td >
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_gpa/uploadGrade&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_gpa/uploadGrade&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_gpa/uploadGrade&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_gpa/uploadGrade&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
					?>
					<?php
						$_sql_teacher = "
								(
									select 
										'00000000-0000-0000-0000-000000000000' as teacher_id,
										'' as prefix,
										'- ยังไม่ระบุ -' as firstname,
										'' as lastname,
										(
											select count(*) from register_teachers
											where 
												teacher_id = '00000000-0000-0000-0000-000000000000' and 
												acadyear     = '" . $acadyear . "' and 
												acadsemester = '" . $acadsemester . "' and
												concat(left(SubjectCode,1), is_split_class, club_code) != 'ก10000'
										) as total_subjects
								)
								union
								(
									select 
											t.teacher_id,
											t.prefix,
											t.firstname,
											t.lastname,
											(
												select count(distinct rt.room_id,rt.SubjectCode) from register_teachers rt
												where
													t.teacher_id        = rt.teacher_id
													and rt.acadyear     = '" . $acadyear . "'
													and rt.acadsemester = '" . $acadsemester . "'
											) as total_subjects
									from 
										teachers t 
									where
										t.teacher_id != '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16' and 
										t.type != 'xcancel'		
								)
								order by convert(firstname using tis620), convert(lastname using tis620)	
							";
						$_res_teacher = mysqli_query($_connection,$_sql_teacher);
						//echo $_sql_teacher . "<br/>";
					?>
					<font size="2" color="#000000">
						<form name="teachers" method="get">
							<input type="hidden" name="option"       value="module_gpa/uploadGrade" />
							<input type="hidden" name="acadyear"     value="<?=$acadyear?>" />
							<input type="hidden" name="acadsemester" value="<?=$acadsemester?>" />
							<select name="teacher_id" class="inputboxUpdate" onchange="document.teachers.submit()">
								<option value=""></option>
								<? while($_datT = mysqli_fetch_assoc($_res_teacher)) { ?> 
									<option value="<?=$_datT['teacher_id']?>" <?=isset($_REQUEST['teacher_id']) && $_REQUEST['teacher_id']==$_datT['teacher_id']?"selected":""?> >
										<?=$_datT['firstname'] . ' ' . $_datT['lastname'] . ' (' . $_datT['total_subjects'] . ')'?>
									</option>
								<? } ?>
							</select>
						</form>
					</font>
				</td>
			</tr>
	</table>
 
	<? if($_teacher_id != ""){ ?> 
		<?php


			$_sqlCurr = "
				select 
					t.teacher_id,
					t.prefix,
					t.firstname,
					t.lastname,
					rt.teacher_register_id,
					rt.room_id,
					rt.subject_level,
					rt.SubjectCode,
					rt.club_code,
					rt.subject_register_id,
					rt.is_split_class,
					s.SubjectName,
					s.SubjectUnit,
					s.SubjectHour,
					s.SubjectType,
					g.subject_group_name,
					c.club_name,
					(
						select count(*) 
						from
							register_students rs inner join students ss
							on (rs.student_id = ss.id and rs.acadyear = ss.xedbe)
						where
							rs.SubjectCode    = rt.SubjectCode and
							rs.acadyear       = rt.acadyear and 
							rs.acadsemester   = rt.acadsemester and
							rs.club_code      = rt.club_code and
							rs.subject_register_id = rt.subject_register_id and
							concat((((ss.xlevel-3)*3)+ss.xyearth),'0',room) = rt.room_id
					) as total_students,
					(
						select count(*) 
						from
							register_students rs inner join students ss
							on (rs.student_id = ss.id and rs.acadyear = ss.xedbe)
						where
							rs.SubjectCode    = rt.SubjectCode and
							rs.acadyear       = rt.acadyear and 
							rs.acadsemester   = rt.acadsemester and
							rs.club_code      = rt.club_code and
							rs.subject_register_id = rt.subject_register_id and
							concat((((ss.xlevel-3)*3)+ss.xyearth),'00') = rt.room_id
					) as total_students2,
					(
						select 
							f.import_status
						from
							register_teachers_upload_files f
						where
							rt.teacher_register_id = f.teacher_register_id
						limit 1
					) as grade_status
				from 
					register_teachers rt LEFT join teachers t
					on (
						 t.teacher_id    = rt.teacher_id and 
						 rt.acadyear     = '" . $acadyear . "' and 
						 rt.acadsemester = '" . $acadsemester . "'
						)
					left join curriculum_subjects s 
					on (rt.SubjectCode = s.SubjectCode)
					left join curriculum_subject_group g 
					on (s.SubjectGroup = g.subject_group_id)
					left join curriculum_clubs c 
					on (rt.club_code = c.club_code)
				where
					rt.teacher_id = '" . $_teacher_id . "' and
					concat(s.SubjectType,rt.club_code,rt.is_split_class) != 'กิจกรรมพัฒนาผู้เรียน00001' and 
					concat(left(rt.SubjectCode,1), rt.is_split_class, rt.club_code) != 'ก10000' and
					rt.acadyear     = '" . $acadyear . "' and 
					rt.acadsemester = '" . $acadsemester . "' and
					s.SubjectType  != 'กิจกรรมพัฒนาผู้เรียน'
				order by
					FIELD(s.SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'),
					rt.club_code,
					s.SubjectGroup,
					rt.SubjectCode,
					rt.room_id
			";

			//echo $_sqlCurr;

			$_resC = mysqli_query($_connection,$_sqlCurr);
			$_rowC = mysqli_num_rows($_resC);
			$_order = 1;

			$_init_subject_code = "";
			$_init_club_name    = "";
		?>

			<div align="center">
				<? if($_rowC >0){ ?> 
					<table class="admintable">
						<tr height="35px">
							<td colspan="10" align="center">
								<img src="../images/school_logo.png" width="120px"><br/>
								รายวิชาที่สอนของ <b><?="ครู" . getUserAccountName($_connection,$_teacher_id)?> </b><br/>
								ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> 
							</td>
						</tr>
						<tr height="35px"> 
							<td class="key" width="25px" align="center" >-</td>
							<td class="key" width="80px" align="center">รหัสวิชา</td>
							<td class="key" width="200px" align="center">ชื่อวิชา</td>
							<td class="key" width="40px" align="center">ห้อง</td>
							<td class="key" width="40px" align="center">นก.</td>
							<td class="key" width="40px" align="center">ชั่วโมง</td>
							<td class="key" width="80px" align="center">ประเภทวิชา</td>
							<td class="key" width="80px" align="center">รูปแบบ<br/>การสอน</td>
							<td class="key" width="40px" align="center">นร</td>
							<td class="key" width="180px" align="center">สถานะผลการเรียน</td>
							<td class="key" width="60px" align="center">-</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
							<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
								<td align="center" valign="top"><?=$_order++?></td>
								<td align="center" valign="top">
									<?php
										//if($_init_subject_code != $_dat['SubjectCode']){
											echo $_dat['SubjectCode'];
										//}else{
										//	echo "";
										//}
										
									?>
								</td>
								<td align="left">
									<?php
										//echo $_dat['SubjectName'] . "<br/>";

										//if($_init_subject_code != $_dat['SubjectCode'] || $_init_club_name != $_dat['club_name']){
											if($_dat['SubjectType']=='กิจกรรมพัฒนาผู้เรียน'){
												echo $_dat['SubjectName'];
												if($_dat['club_name'] != ""){
													echo "<br/>(" . $_dat['club_name'] . ")";
												}
											}
											else{
												echo $_dat['SubjectName'];
											}
										//}
										$_init_club_name = $_dat['club_name'];
										$_init_subject_code = $_dat['SubjectCode'];
									?>
								</td>
								<td align="center" valign="top"><?=getFullRoomFormat($_dat['room_id'])?></td>
								<td align="center" valign="top"><?=$_dat['SubjectUnit']?></td>
								<td align="center" valign="top"><?=$_dat['SubjectHour']?></td>
								<td align="center" valign="top"><?=strlen($_dat['SubjectType'])>30?"กิจกรรมฯ":$_dat['SubjectType']?></td>
								<td align="center" valign="top">
									<?php
										$_split_class = $_dat['is_split_class'];

										if($_split_class==0){
											echo "จัดตามห้อง";
										}else if($_split_class==1){
											echo "คละห้อง";
										}else{
											echo "คละระดับชั้น";
										}
									?>
								</td>
								<td align="center" valign="top">
									<?php
										if($_dat['is_split_class'] == 0){
											echo $_dat['total_students'];
										}else{
											echo $_dat['total_students2'];
										}
									?>
								</td>
								<td valign="top">
									<?php
										if($_dat['grade_status']=='ยืนยันผลการเรียนแล้ว'){
											echo "<font color='green'>";
											echo $_dat['grade_status'];
											echo "</font>";
										}else{
											echo $_dat['grade_status'];
										}
									?>
								</td>
								<td align="center" valign="top">
									<form method="post" action="index.php?option=module_gpa/uploadGradePerSubject">
										<input type="hidden" name="SubjectCode"         value="<?=$_dat['SubjectCode']?>" />
										<input type="hidden" name="SubjectName"         value="<?=$_dat['SubjectName']?>" />
										<input type="hidden" name="teacher_register_id" value="<?=$_dat['teacher_register_id']?>" />
										<input type="hidden" name="teacher_id"          value="<?=$_dat['teacher_id']?>" />
										<input type="hidden" name="room_id"             value="<?=$_dat['room_id']?>" />
										<input type="hidden" name="club_code"           value="<?=$_dat['club_code']?>" />
										<input type="hidden" name="acadyear"            value="<?=$acadyear?>" />
										<input type="hidden" name="acadsemester"        value="<?=$acadsemester?>" />
										<input type="hidden" name="subject_register_id" value="<?=$_dat['subject_register_id']?>" />
										<input type="submit" name="go_to_upload"        value="จัดการผลการเรียน" />
									</form>
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
	<? } ?>
</div>

