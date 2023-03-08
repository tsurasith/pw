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
					<span class="normal"><font color="#0066FF"><strong>1.5 ตรวจสอบการลงทะเบียนเรียนของนักเรียน</strong></font></span>
				</td>
				<td >
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_curriculum/ReportStudentRegisteredIndividaul&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/ReportStudentRegisteredIndividaul&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportStudentRegisteredIndividaul&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportStudentRegisteredIndividaul&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
					?>
					<?php
						$_room     = "";
						$_class_id = "";

						$_student_id = $_SESSION['username'];

						$_sql  = "select id,xlevel,xyearth,room from students where id = '" . $_student_id . "' and xedbe = '" . $acadyear . "'";
						$_resStudent = mysqli_query($_connection,$_sql);

						if(mysqli_num_rows($_resStudent)>0){
							$_datS = mysqli_fetch_assoc($_resStudent);

							$_room = $_datS['room'];

							if($_datS['xlevel']==3){
								$_class_id = $_datS['xyearth'] . "0" . $_datS['room'];
							}else{
								$_class_id = (3+$_datS['xyearth']) . "0" . $_datS['room'];
							}
						}
						
					?>
					
				</td>
			</tr>
	</table>
 
	
		<?php


			$_sqlCurr = "
			select 
				rs.SubjectCode,
				s.SubjectName,
				s.SubjectType,
				s.SubjectHour,
				s.SubjectUnit,
				s.SubjectType,
				rs.point_1,
				rs.point_mid,
				rs.point_2,
				rs.point_final,
				rs.club_code,
				c.club_name,
				(
					select concat(ts.firstname,' ',ts.lastname)
					from
						register_teachers t left join teachers ts on (t.teacher_id = ts.teacher_id)
					where
						t.acadyear = rs.acadyear and 
						t.acadsemester = rs.acadsemester and
						t.SubjectCode  = rs.SubjectCode and 
						t.club_code    = rs.club_code and
						t.subject_register_id = rs.subject_register_id and 
						t.room_id = '" . $_class_id. "'
					limit 1
				) as teacher_1,
					(
					select concat(ts.firstname,' ',ts.lastname)
					from
						register_teachers t left join teachers ts on (t.teacher_id = ts.teacher_id)
					where
						t.acadyear = rs.acadyear and 
						t.acadsemester = rs.acadsemester and
						t.SubjectCode  = rs.SubjectCode and 
						t.club_code    = rs.club_code and
						t.subject_register_id = rs.subject_register_id and 
						t.room_id = '" . substr($_class_id,0,1) . "00'
					limit 1
				) as teacher_2
			from 
				register_students rs inner join curriculum_subjects s 
				on (rs.SubjectCode = s.SubjectCode)
				left join curriculum_clubs c 
				on (rs.club_code = c.club_code)
			where
				rs.student_id   = '" . $_student_id . "' and
				rs.acadyear     = '" . $acadyear . "' and
				rs.acadsemester = '" . $acadsemester . "'
			ORDER BY
				FIELD(
					s.SubjectType,
					'พื้นฐาน',
					'เพิ่มเติม',
					'กิจกรรมพัฒนาผู้เรียน'
				),
				s.SubjectGroup,
				rs.SubjectCode
		
			";

			//echo $_sqlCurr;

			$_resC = mysqli_query($_connection,$_sqlCurr);
			$_rowC = mysqli_num_rows($_resC);
			$_order = 1;

			$_total_unit = array(0.0,0.0,0.0);
			$_total_hour = array(0,0,0);
		?>

		<form method="post" action="">
			<div align="center">
				<? if($_rowC >0){ ?> 
					<table class="admintable">
						<tr height="35px">
							<td colspan="10" align="center">
								<img src="../images/school_logo.png" width="120px"><br/>
								<?php
									$_st = displayStudent($_connection,$_student_id,$acadyear);
								?>
								ผลการลงทะเบียนเรียน <b><?=$_st['prefix'].$_st['firstname']. ' ' . $_st['lastname']?> </b><br/>
								ชั้นมัธยมศึกษาปีที่ <?=getFullRoomFormat($_class_id)?>
								ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> 
							</td>
						</tr>
						<tr height="35px"> 
							<td class="key" width="25px" align="center" >-</td>
							<td class="key" width="80px" align="center">รหัสวิชา</td>
							<td class="key" width="240px" align="center">ชื่อวิชา</td>
							<td class="key" width="75px" align="center">หน่วย<br/>การเรียน</td>
							<td class="key" width="60px" align="center">ชั่วโมง</td>
							<td class="key" width="130px" align="center">ประเภทวิชา</td>
							<td class="key" width="120px" align="center">ครูผู้สอน</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
							<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
								<td valign="top" align="right"><?=$_order++?></td>
								<td valign="top" align="center">
									<?php
										echo $_dat['SubjectCode'];
									?>
								</td>
								<td valign="top" align="left">
									<?php
										//echo $_dat['SubjectName'] . "<br/>";

											if($_dat['SubjectType']=='กิจกรรมพัฒนาผู้เรียน'){
												echo $_dat['SubjectName'];
												if($_dat['club_name'] != ""){
													echo "<br/>(" . $_dat['club_name'] . ")";
												}
											}
											else{
												echo $_dat['SubjectName'];
											}

										if($_dat['SubjectType']=="พื้นฐาน"){
											$_total_unit[0] += $_dat['SubjectUnit'];
											$_total_hour[0] += $_dat['SubjectHour'];
										}
										if($_dat['SubjectType']=="เพิ่มเติม"){
											$_total_unit[1] += $_dat['SubjectUnit'];
											$_total_hour[1] += $_dat['SubjectHour'];
										}
										if($_dat['SubjectType']=="กิจกรรมพัฒนาผู้เรียน"){
											$_total_unit[2] += $_dat['SubjectUnit'];
											$_total_hour[2] += $_dat['SubjectHour'];
										}
									?>
								</td>
								<td valign="top" align="center"><?=$_dat['SubjectUnit']?></td>
								<td valign="top" align="center"><?=$_dat['SubjectHour']?></td>
								<td valign="top" align="center"><?=$_dat['SubjectType']?></td>
								<td valign="top" align="left">
									<?php
										if($_dat['teacher_1'] != ""){
											echo $_dat['teacher_1'];
										}
										if($_dat['teacher_2'] != ""){
											echo $_dat['teacher_2'];
										}
									?>
								</td>
							</tr>
						<? } ?>
						<tr>
							<td align="center"></td>
							<td align="center"></td>
							<td align="left"><b>วิชาพื้นฐาน</b></td>
							<td align="center"><b><?=number_format($_total_unit[0],1)?></b></td>
							<td align="center"><b><?=$_total_hour[0]?></b></td>
						</tr>
						<tr>
							<td align="center"></td>
							<td align="center"></td>
							<td align="left"><b>วิชาเพิ่มเติม</b></td>
							<td align="center"><b><?=number_format($_total_unit[1],1)?></b></td>
							<td align="center"><b><?=$_total_hour[1]?></b></td>
						</tr>
						<tr>
							<td align="center"></td>
							<td align="center"></td>
							<td align="left"><b>กิจกรรมพัฒนาผู้เรียน</b></td>
							<td align="center"><b><?=number_format($_total_unit[2],1)?></b></td>
							<td align="center"><b><?=$_total_hour[2]?></b></td>
						</tr>
						<tr height="30px">
							<td align="center"></td>
							<td class="key" align="center" colspan="2">รวม</td>
							<td class="key" align="center"><?=array_sum($_total_unit)?></td>
							<td class="key" align="center"><?=array_sum($_total_hour)?></td>
						</tr>
					</table>
				<? } else { 
						echo "<br/><br/>";
						echo "<font color='red'> ไม่พบรายวิชาที่ลงทะเบียนเรียนของนักเรียน ขอให้กลับไปตรวจสอบการลงทะเบียนเรียนอีกครั้ง </font>";
						echo "<br/><br/>";
				} ?>
			</div>
		</form>
</div>

