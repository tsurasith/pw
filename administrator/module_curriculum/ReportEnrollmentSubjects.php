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
					<span class="normal"><font color="#0066FF"><strong>1.2 รายวิชาที่เปิดลงทะเบียนรายภาคเรียน</strong></font></span>
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

			$_xlevel  = 0;
			$_xyearth = 0;

			$_xlevel  = $_POST['yearth']>3?4:3;
			if($_xlevel == 3){
				$_xyearth = $_POST['yearth'];
			}else{
				$_xyearth = $_POST['yearth']-3;
			}

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
				on (
					 m.curriculum_id = r.curriculum_id and 
					 m.curriculum_mapping_semester = r.acadsemester
					)
				inner join curriculum_subjects s 
				on (m.SubjectCode = s.SubjectCode)
				left join register_subjects sr 
				on (
					 r.acadyear = sr.acadyear and 
					 r.acadsemester = sr.acadsemester and 
					 s.SubjectCode = sr.SubjectCode and
					 m.curriculum_mapping_level = sr.subject_level
					)
				left join
				    (
						select 
							SubjectCode,
							count(*) as 'total_student'
						from
							register_students
						where
							acadyear     = '" . $acadyear . "' and
							acadsemester = '" . $acadsemester . "' and
							student_id in 
								(
									select id from students 
									where xedbe = '" . $acadyear . "' and xlevel = '" . $_xlevel . "' and xyearth = '" . $_xyearth . "' 
								)
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
							<td class="key" width="25px" align="center" >-</td>
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

