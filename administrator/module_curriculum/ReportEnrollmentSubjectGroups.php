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
					<span class="normal"><font color="#0066FF"><strong>1.2.1 รายวิชาที่เปิดลงทะเบียนรายภาคเรียน (กลุ่มสาระการเรียนรู้)</strong></font></span>
				</td>
				<td >
					<?php
						if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
						if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
					?>
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjectGroups&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjectGroups&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjectGroups&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/ReportEnrollmentSubjectGroups&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
					?>
					<font size="2" color="#000000">
						<form name="level" method="post">
							<?php
								$_sql_subject_group = "SELECT * FROM `curriculum_subject_group` where subject_group_id != 0";
								$_res_subject_group = mysqli_query($_connection,$_sql_subject_group);
							?>
							
							กลุ่มสาระ 
							<select name="subject_group" class="inputboxUpdate">
								<option value="">ทั้งหมด</option>
								<? while($_group = mysqli_fetch_assoc($_res_subject_group)){ ?>
									<option value=<?=$_group['subject_group_id']?> <?=isset($_POST['subject_group']) && $_POST['subject_group']==$_group['subject_group_id']?"selected":""?> >
										<?=$_group['subject_group_name']?>
									</option>
								<? } ?>
							</select>
							<br/>
							ระดับชั้น 
							<select name="yearth" class="inputboxUpdate">
								<option value="">ทั้งหมด</option>
								<option value="1" <?=isset($_POST['yearth']) && $_POST['yearth']=="1"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 1</option>
								<option value="2" <?=isset($_POST['yearth']) && $_POST['yearth']=="2"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 2</option>
								<option value="3" <?=isset($_POST['yearth']) && $_POST['yearth']=="3"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 3</option>
								<option value="4" <?=isset($_POST['yearth']) && $_POST['yearth']=="4"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 4</option>
								<option value="5" <?=isset($_POST['yearth']) && $_POST['yearth']=="5"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 5</option>
								<option value="6" <?=isset($_POST['yearth']) && $_POST['yearth']=="6"?"selected":""?> >ชั้นมัธยมศึกษาปีที่ 6</option>
							</select>
							<br/>
							<input type="checkbox" name="display_room" value="yes" <?=isset($_POST['display_room'])?"checked":""?> /> 
							แสดงห้องเรียนและครูผู้สอน 
							<input type="submit" name="search" value="เรียกดู" class="button"/>
						</form>
					</font>
				</td>
			</tr>
	</table>
 
	<? if(isset($_POST['search'])){ ?> 
		<?php

			$_xlevel  = 0;
			$_xyearth = 0;

			$_xlevel  = $_POST['yearth']>3?4:3;
			if($_xlevel == 3){
				$_xyearth = $_POST['yearth'];
			}else{
				$_xyearth = $_POST['yearth']-3;
			}

			$_sqlCurr = "";
			
			if(isset($_POST['display_room'])){
				$_sqlCurr = "
					SELECT * FROM nl_registered_subjects_group_teachers	
					WHERE
						acadyear     = '" . $acadyear . "' and 
						acadsemester = '" . $acadsemester . "' 
				";
				if($_POST['yearth'] != ""){
					$_sqlCurr .= " and subject_level = '" . $_POST['yearth'] . "'";
				}

				if($_POST['subject_group'] != ""){
					$_sqlCurr .= " and SubjectGroup = '" . $_POST['subject_group'] . "'";
				}

				$_sqlCurr .= "order by subject_level, FIELD(SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'), SubjectGroup, SubjectCode, room_id";
			}else{
				$_sqlCurr = "
				SELECT * FROM nl_registered_subjects	
				WHERE
					acadyear     = '" . $acadyear . "' and 
					acadsemester = '" . $acadsemester . "' 
				";
				if($_POST['yearth'] != ""){
					$_sqlCurr .= " and subject_level = '" . $_POST['yearth'] . "'";
				}

				if($_POST['subject_group'] != ""){
					$_sqlCurr .= " and SubjectGroup = '" . $_POST['subject_group'] . "'";
				}

				$_sqlCurr .= "order by subject_level, FIELD(SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'), SubjectGroup, SubjectCode";
			}

			//echo $_sqlCurr . "<br/>";

			$_resC = mysqli_query($_connection,$_sqlCurr);
			$_rowC = mysqli_num_rows($_resC);
			$_order = 1;
		?>

			<div align="center">
				<? if($_rowC >0){ ?> 
					<? if(isset($_POST['display_room'])){  // display class room and teacher ?>
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="10"> &nbsp; รายวิชาเปิดให้ลงทะเบียนเรียนภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> </td>
							</tr>
							<tr height="35px"> 
								<td class="key" width="25px" align="center" >-</td>
								<td class="key" width="65px" align="center">รหัสวิชา</td>
								<td class="key" width="210px" align="center">ชื่อวิชา</td>
								<td class="key" width="50px" align="center">นก.</td>
								<td class="key" width="50px" align="center">ชั่วโมง</td>
								<td class="key" width="100px" align="center">ประเภทวิชา</td>
								<td class="key" width="150px" align="center">กลุ่มสาระการเรียนรู้</td>
								<td class="key" width="40px" align="center">ห้อง</td>
								<td class="key" width="70px" align="center">นักเรียน<br/>ลงทะเบียน</td>
								<td class="key" width="130px" align="center">ครูผู้สอน</td>
							</tr>
							<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
								<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
									<td valign="top" align="right"><?=$_order++?></td>
									<td valign="top" align="center"><?=$_dat['SubjectCode']?></td>
									<td valign="top" align="left">
										<?php
											if($_dat['club_name'] != ""){
												echo $_dat['SubjectName'];
												echo " (" . $_dat['club_name'] . ")";
											}else{
												echo $_dat['SubjectName'];
											}
										?>
									</td>
									<td valign="top" align="center"><?=$_dat['SubjectUnit']?></td>
									<td valign="top" align="center"><?=$_dat['SubjectHour']?></td>
									<td valign="top" align="center"><?=$_dat['SubjectType']=="กิจกรรมพัฒนาผู้เรียน"?"กิจกรรมฯ":$_dat['SubjectType']?></td>
									<td valign="top" align="center">
										<?=$_dat['subject_group_name']?>
									</td>
									<td valign="top" align="center">
										<?=getFullRoomFormat($_dat['room_id'])?>
									</td>
									<td valign="top" align="center">
										<?php
											if($_dat['class_registered']!=0){
												echo $_dat['class_registered'];
											}else{
												echo $_dat['level_registered'];
											}
										?>
									</td>
									<td valign="top" align="left">
										<?=$_dat['teacher_name']?>
									</td>
								</tr>
							<? } ?>
						</table>
					<? } else {  // display summary without classroom?>
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="10"> &nbsp; รายวิชาเปิดให้ลงทะเบียนเรียนภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> </td>
							</tr>
							<tr height="35px"> 
								<td class="key" width="25px" align="center" >-</td>
								<td class="key" width="65px" align="center">รหัสวิชา</td>
								<td class="key" width="210px" align="center">ชื่อวิชา</td>
								<td class="key" width="50px" align="center">นก.</td>
								<td class="key" width="50px" align="center">ชั่วโมง</td>
								<td class="key" width="100px" align="center">ประเภทวิชา</td>
								<td class="key" width="150px" align="center">กลุ่มสาระการเรียนรู้</td>
								<td class="key" width="120px" align="center">ระดับชั้น</td>
								<td class="key" width="70px" align="center">นักเรียน<br/>ลงทะเบียน</td>
							</tr>
							<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
								<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
									<td valign="top" align="right"><?=$_order++?></td>
									<td valign="top" align="center"><?=$_dat['SubjectCode']?></td>
									<td valign="top" align="left"><?=$_dat['SubjectName']?></td>
									<td valign="top" align="center"><?=$_dat['SubjectUnit']?></td>
									<td valign="top" align="center"><?=$_dat['SubjectHour']?></td>
									<td valign="top" align="center"><?=$_dat['SubjectType']=="กิจกรรมพัฒนาผู้เรียน"?"กิจกรรมฯ":$_dat['SubjectType']?></td>
									<td valign="top" align="center">
										<?=$_dat['subject_group_name']?>
									</td>
									<td valign="top" align="center">
										<?="ชั้นมัธยมศึกษาปีที่ " . $_dat['subject_level']?>
									</td>
									<td valign="top" align="center">
										<?=$_dat['total_registered']?>
									</td>
								</tr>
							<? } ?>
						</table>
					<? } ?>
				<? } else { 
						echo "<br/><br/>";
						echo "<font color='red'> ไม่พบรายวิชาที่สามารถเปิดลงทะเบียนเรียนได้ ขอให้กลับไปตรวจสอบหลักสูตรที่เปิดสอนอีกครั้ง </font>";
						echo "<br/><br/>";
				} ?>
			</div>
	<? } ?>
</div>

