<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear']))     { $acadyear = $_POST['acadyear'];}
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester'];}
?>



<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr> 
			<td align="center">
				<a href="index.php?option=module_curriculum/index"><img src="../images/graduation.png" alt="" width="48" height="48" border="0" /></a>
			</td>
			<td>
				<strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>1.6 รายชื่อนักเรียนตามชุมนุม/กิจกรรม</strong></font></span>
			</td>
			<td>
				<form method="post">
					<? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; } ?>
					ปีการศึกษา<?php  
							echo "<a href=\"index.php?option=module_curriculum/ReportStudentRegisteredClub&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo '<font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/ReportStudentRegisteredClub&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
						ภาคเรียนที่
							<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
									else {
										echo " <a href=\"index.php?option=module_curriculum/ReportStudentRegisteredClub&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
									}
									if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
									else {
										echo " <a href=\"index.php?option=module_curriculum/ReportStudentRegisteredClub&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
									}
								?><br/>
					<font  size="2" color="#000000">
					<?php 
							
							$_sql_club = "
										select 
											c.club_code,
											c.club_name,
											concat(club_code,'|',club_name) as club_info,
											(
												select count(*)
												from
													register_students s
												where
													s.club_code = c.club_code and
													s.acadsemester = '2' and
													s.acadyear = '2565'
											) as registered_students
										from
											curriculum_clubs c 
										where
											c.club_status = 'active'
										order by
											convert(c.club_name using tis620),c.club_code	";					
							
					?>
							<? $_res_club = mysqli_query($_connection,$_sql_club); ?>
							เลือกชุมนุม 
							<select name="club_info" class="inputboxUpdate" onchange="this.form.submit()">
								<option value=""></option>
								<? while($_club = mysqli_fetch_assoc($_res_club)) { ?>
									<? $_select = (isset($_POST['club_info']) && $_POST['club_info'] == $_club['club_info']?"selected":""); ?>
									<option value="<?=$_club['club_info']?>" <?=$_select?> >
										<?=$_club['club_code'] . ' ' . $_club['club_name'] . " (" . $_club['registered_students'] . ")"?>
									</option>
								<? } ?>
							</select>
					</font>
				</form>
			</td>
		</tr>
	</table>


<?
	  $_xlevel  = 0;
	  $_xyearth = 0;

 ?>
	<? if(isset($_POST['club_info']) && $_POST['club_info'] == ""){ ?>
		<center><br/><font color="#FF0000">กรุณาเลือกชุมนุม/กิจกรรมก่อน !</font></center>
	<? }//end if ตรวจสอบรายวิชา ?>

	<? if(isset($_POST['club_info']) && $_POST['club_info'] != ""){ ?>
		<?php

			$_club_info = explode("|",$_POST['club_info']);

			$_sql_student = "
							select 
								s.id,
								r.student_id,
								s.prefix,s.firstname,s.lastname,s.nickname,s.room,s.studstatus,
								s.xlevel,s.xyearth,
								concat((((xlevel-3)*3)+xyearth),'/',room) as class_room,
								r.club_code,
								r.SubjectCode,
								c.club_name,
								t.teacher_id,
								(
									select concat(firstname,' ',lastname)
									from teachers tt
									where
										t.teacher_id = tt.teacher_id
								) as teacher_name
							from 
								students s inner join register_students r
								on (
									 s.id           = r.student_id and
									 s.xedbe        = r.acadyear and
									 r.acadsemester = '" . $acadsemester . "' and
									 r.acadyear     = '" . $acadyear ."' and
									 r.club_code    = '" . $_club_info[0] . "' 
								    )
								inner join register_teachers t
								on (
									 r.acadsemester        = t.acadsemester and
									 r.acadyear            = t.acadyear and
									 r.subject_register_id = t. subject_register_id and
									 r.club_code           = t.club_code
								    )
								left join curriculum_clubs c
								on (
									 r.club_code = c.club_code
								    )
							where 
								s.xedbe   = '" . $acadyear . "' and 
								s.studstatus in (1,2) 
							
							order by 
								s.xlevel, s.xyearth, 
								s.room,s.sex, 
								convert(s.firstname using tis620),convert(s.lastname using tis620)";

			$_res_student = mysqli_query($_connection,$_sql_student);

			//echo $_sql_student."<br/>";
		?>


		<? if(mysqli_num_rows($_res_student)>0){ ?>
			<div align="center">
				<form method="post" id="frm1">
					<table class="admintable" >
						<tr>
							<td colspan="8" align="center">
								<img src="../images/school_logo.png" width="120px"><br/>
								<b>รายชื่อนักเรียนสังกัดชุมนุม/กิจกรรม 
								<font color='green'> <?=$_club_info[1]?></font> <br/>
								ภาคเรียนที่ <font color='green'><?=$acadsemester?></font>
								ปีการศึกษา <font color='green'><?=$acadyear?></font>
								</b>
							</td>
						</tr>
						<tr>
							<td width="40px"  align="center" class="key">เลขที่</td>
							<td width="75px"  align="center" class="key">เลขประจำตัว</td>
							<td width="220px" align="center" class="key">ชื่อ - นามสกุล</td>
							<td width="70px"  align="center" class="key">ชื่อเล่น</td>
							<td width="60px"  align="center" class="key">ห้อง</td>
							<td width="100px" align="center" class="key">สถานภาพปัจจุบัน</td>
							<td width="70px"  align="center" class="key">รหัสวิชา</td>
							<td width="190px" align="center" class="key">ครูผู้สอน</td>
						</tr>
						<? $_i = 1;?>
						<? while($_dat = mysqli_fetch_assoc($_res_student)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_i++?></td>
							<td align="center"><?=$_dat['id']?></td>
							<td><?=$_dat['prefix'].$_dat['firstname']. ' ' .$_dat['lastname']?></td>
							<td align="center"><?=$_dat['nickname']!=""?$_dat['nickname']:"-"?></td>
							<td align="center"><?=$_dat['class_room']?></td>
							<td align="center"><?=displayStudentStatusColor($_dat['studstatus'])?></td>
							<td align="center"><?=$_dat['SubjectCode']?></td>
							<td> &nbsp; <?=$_dat['teacher_name']?></td>
						</tr>
						<? } //end while?>
					</table>
				</form>
			</div>
		<? } else { //end check rows ?>
				<center><br/><font color="red">ไม่พบข้อมูลตามที่ค้นหา</font></center>
		<? } //end else ?>
	<? }//end if ?>

</div>


