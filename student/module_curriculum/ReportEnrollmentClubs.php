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
					<span class="normal"><font color="#0066FF"><strong>1.3 รายชื่อชุมนุม/กิจกรรมที่เปิดลงทะเบียน</strong></font></span>
				</td>
				<td >
					<?php
						if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
						if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
					?>
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjects&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjects&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjects&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjects&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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


			$_sql_Club = "
					select 
						s.SubjectCode,
						cs.SubjectName,
						cs.SubjectHour,
						s.is_split_class,
						s.subject_level,
						t.teacher_id,
						tt.prefix,
						tt.firstname,
						tt.lastname,
						t.room_id,
						t.acadyear,
						t.acadsemester,
						t.club_code,
						cc.club_name,
						t.teacher_register_id,
						(
							select count(*) from register_students ss
							where
								ss.SubjectCode  = t.SubjectCode and
								ss.acadyear     = t.acadyear and 
								ss.acadsemester = t.acadsemester and 
								ss.club_code    = t.club_code 
						) as student_group,
						(
							select count(*) 
							from register_students ss inner join students st 
								on (ss.student_id = st.id and ss.acadyear = st.xedbe)
							where
								ss.SubjectCode     = t.SubjectCode and
								ss.acadyear        = t.acadyear and 
								ss.acadsemester    = t.acadsemester and 
								ss.club_code       = t.club_code and
								right(t.room_id,1) = st.room
						) as student_room
					from 
						register_subjects s inner join register_teachers t
						on (
							s.SubjectCode = t.SubjectCode and 
							s.acadyear    = t.acadyear and
							s.acadsemester= t.acadsemester
						)
						left join curriculum_subjects cs on (s.SubjectCode = cs.SubjectCode)
						left join curriculum_clubs cc on (t.club_code = cc.club_code)
						left join teachers tt on (t.teacher_id = tt.teacher_id)
					where
						s.subject_level    = '" . $_POST['yearth'] . "'
						and cs.SubjectType = 'กิจกรรมพัฒนาผู้เรียน' 
						and t.acadyear     = '" . $acadyear . "' 
						and t.acadsemester = '" . $acadsemester . "'
						and concat(s.is_split_class,t.club_code) != '10000'
					order by
						s.SubjectCode,
						t.room_id,
						convert(cc.club_name using tis620)

			";

			//echo $_sql_Club;

			$_resC = mysqli_query($_connection,$_sql_Club);
			$_rowC = mysqli_num_rows($_resC);
			$_order = 1;
		?>

		<form method="post" action="">
			<div align="center">
				<? if($_rowC >0){ ?> 
					<table class="admintable">
						<tr height="35px">
							<td class="key" colspan="10"> &nbsp; กิจกรรมพัฒนาผู้เรียนที่เปิดให้ลงทะเบียนเรียนภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> </td>
						</tr>
						<tr height="35px"> 
							<td class="key" width="25px" align="center" >-</td>
							<td class="key" width="70px" align="center">รหัสวิชา</td>
							<td class="key" width="140px" align="center">ชื่อวิชา</td>
							<td class="key" width="50px" align="center">ชั่วโมง</td>
							<td class="key" width="220px" align="center">ชุมนุม/กิจกรรม</td>
							<td class="key" width="90px" align="center">จำนวนนักเรียนลงทะเบียน</td>
							<td class="key" width="140px" align="center">ครูผู้สอน</td>
							<td class="key" width="90px" align="center">รูปแบบการสอน</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
							<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
								<td valign="top" align="right"><?=$_order++?></td>
								<td valign="top" align="center"><?=$_dat['SubjectCode']?></td>
								<td valign="top" align="left"><?=$_dat['SubjectName']?></td>
								<td valign="top" align="center"><?=$_dat['SubjectHour']?></td>
								<td valign="top" align="left">
									<?php
										if($_dat['club_code'] != "000"){
											echo $_dat['club_code'] . ' ' . $_dat['club_name'];
										}else{
											echo $_dat['club_code'] . ' ' . $_dat['SubjectName'];
										}
									?>
								</td>
								<td valign="top" align="center">
									<?php
										if(substr($_dat['room_id'],1,2)=="00"){
											echo $_dat['student_group'];
											echo " (คละ)";
										}else{
											echo $_dat['student_room'];
											echo  " (" . getFullRoomFormat($_dat['room_id']) . ")";
										}
									?>
								</td>
								<td valign="top">
									<?php
										if($_dat['teacher_id']!="00000000-0000-0000-0000-000000000000"){
											echo $_dat['firstname'] . ' ' . $_dat['lastname'];
										}else{
											echo " -";
										}
									?>
								</td>
								<td valign="top" align="center">
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

