<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear']))     { $acadyear = $_POST['acadyear'];}
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester'];}
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
					<span class="normal"><font color="#0066FF"><strong>1.4 รายชื่อครูผู้สอนและภาระการสอน</strong></font></span>
				</td>
				<td >
					<?php
						if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
						if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
					?>
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_curriculum/ReportTeachingSubjects&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/ReportTeachingSubjects&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportTeachingSubjects&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportTeachingSubjects&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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
												acadsemester = '" . $acadsemester . "'
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
						<form name="teachers" method="post">
							<select name="teacher_id" class="inputboxUpdate" onchange="document.teachers.submit()">
								<option value=""></option>
								<? while($_datT = mysqli_fetch_assoc($_res_teacher)) { ?> 
									<option value="<?=$_datT['teacher_id']?>" <?=isset($_POST['teacher_id']) && $_POST['teacher_id']==$_datT['teacher_id']?"selected":""?> >
										<?=$_datT['firstname'] . ' ' . $_datT['lastname'] . ' (' . $_datT['total_subjects'] . ')'?>
									</option>
								<? } ?>
							</select>
						</form>
					</font>
				</td>
			</tr>
	</table>
 
	<? if(isset($_POST['teacher_id']) && $_POST['teacher_id'] != ""){ ?> 
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
					) as total_students2
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
					rt.teacher_id = '" . $_POST['teacher_id'] . "' and
					concat(s.SubjectType,rt.club_code,rt.is_split_class) != 'กิจกรรมพัฒนาผู้เรียน00001' 
					
				order by
					FIELD(s.SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'),
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

		<form method="post" action="">
			<div align="center">
				<? if($_rowC >0){ ?> 
					<table class="admintable">
						<tr height="35px">
							<td colspan="10" align="center">
								<img src="../images/school_logo.png" width="120px"><br/>
								รายวิชาที่สอนของ <b><?="ครู" . getUserAccountName($_connection,$_POST['teacher_id'])?> </b><br/>
								ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> 
							</td>
						</tr>
						<tr height="35px"> 
							<td class="key" width="25px" align="center" >-</td>
							<td class="key" width="80px" align="center">รหัสวิชา</td>
							<td class="key" width="240px" align="center">ชื่อวิชา</td>
							<td class="key" width="60px" align="center">ห้อง</td>
							<td class="key" width="75px" align="center">หน่วย<br/>การเรียน</td>
							<td class="key" width="60px" align="center">ชั่วโมง</td>
							<td class="key" width="130px" align="center">ประเภทวิชา</td>
							<td class="key" width="90px" align="center">รูปแบบ<br/>การสอน</td>
							<td class="key" width="70px" align="center">นักเรียน<br/>ลงทะเบียน</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
							<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
								<td align="right"><?=$_order++?></td>
								<td align="center">
									<?php
										if($_init_subject_code != $_dat['SubjectCode']){
											echo $_dat['SubjectCode'];
										}else{
											echo "";
										}
										
									?>
								</td>
								<td align="left">
									<?php
										//echo $_dat['SubjectName'] . "<br/>";

										if($_init_subject_code != $_dat['SubjectCode'] || $_init_club_name != $_dat['club_name']){
											if($_dat['SubjectType']=='กิจกรรมพัฒนาผู้เรียน'){
												echo $_dat['SubjectName'];
												if($_dat['club_name'] != ""){
													echo "<br/>(" . $_dat['club_name'] . ")";
												}
											}
											else{
												echo $_dat['SubjectName'];
											}
										}
										$_init_club_name = $_dat['club_name'];
										$_init_subject_code = $_dat['SubjectCode'];
									?>
								</td>
								<td align="center"><?=getFullRoomFormat($_dat['room_id'])?></td>
								<td align="center"><?=$_dat['SubjectUnit']?></td>
								<td align="center"><?=$_dat['SubjectHour']?></td>
								<td align="center"><?=$_dat['SubjectType']?></td>
								<td align="center">
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
								<td align="center">
									<?php
										if($_dat['is_split_class'] == 0){
											echo $_dat['total_students'];
										}else{
											echo $_dat['total_students2'];
										}
									?>
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

