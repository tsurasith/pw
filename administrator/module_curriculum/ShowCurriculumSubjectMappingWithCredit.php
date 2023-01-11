
<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_curriculum/index">	
						<img src="../images/graduation.png" alt="" width="48" height="48" />
				</a>
				</td>
				<td width="64%"><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>2.3 แสดงรายวิชาตามโครงสร้างแผนการเรียนและสรุปหน่วยการเรียน</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
				</td>
			</tr>
	</table>
  
	<div align="center">
		<form action="" method="post" autocomplete="off">
			<table class="admintable">
				<tr height="35px">
					<td class="key" colspan="3"> &nbsp; เลือกหลักสูตรและแผนการเรียนเพื่อแสดงข้อมูล</td>
				</tr>
				<tr>
					<td align="right"> เลือกแผนการเรียน </td>
					<td>
						<?php
							$_sql = "select * from curriculums order by curriculum_level,curriculum_code";
							$_resCurr = mysqli_query($_connection,$_sql);
							$_curr_token = "";
						?> 
						<select name="curriculum_token" class="inputboxUpdate">
							<option value=""    <?=(isset($_POST['curriculum_token']) && $_POST['curriculum_token']==""?"selected":"")?> ></option>
							<? while($_dat = mysqli_fetch_assoc($_resCurr)) { ?> 
								<? $_curr_token = $_dat['curriculum_id'] . '-' . $_dat['curriculum_level']; ?>
								<option value="<?=$_curr_token?>" <?=(isset($_POST['curriculum_token']) && $_POST['curriculum_token']==$_curr_token?"selected":"")?> >
									<?=$_dat['curriculum_code'] . ' ' . $_dat['curriculum_name']?>
								</option>
							<? } ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" name="search"  value="สืบค้น" /> 
					</td>
				</tr>
			</table>
		</form>
	</div>


	<?php

		if(isset($_POST['search'])){

			$_curr_condition 	= explode("-",$_POST['curriculum_token']);
			$_curriculum_id 	= $_curr_condition[0];
			$_curriculum_level	= $_curr_condition[1];

			$_year = array(1,2,3);
			if($_curriculum_level == "4"){
				$_year = array(4,5,6);
			}

			$_sql = array("","","");
			$_loop = 0;
			for($_i=0;$_i<3;$_i++){
				$_sql[$_loop] = "
					select
						m.SubjectCode,
						s.SubjectName,
						s.SubjectUnit,
						s.SubjectHour,
						s.SubjectType,
						m.curriculum_mapping_level as SubjectLevel,
						m.curriculum_mapping_semester as SubjectSemester,
						s.SubjectGroup
					from
						curriculum_subject_mappings m inner join curriculum_subjects s 
						on (s.SubjectCode = m.subjectcode) inner join curriculums c on (m.curriculum_id = c.curriculum_id)
					where
						c.curriculum_id = '" . $_curriculum_id . "' and m.curriculum_mapping_level = '" . $_year[$_i] . "' ";

				$_xx =	"order by 
						FIELD(s.SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'),
						s.SubjectGroup,
						s.SubjectCode
					";
				$_loop++;
			} // end for loop

			$_total_subject_type_1_unit_term_1 = array(0.0, 0.0 , 0.0);
			$_total_subject_type_1_unit_term_2 = array(0.0, 0.0 , 0.0);
			$_total_subject_type_2_unit_term_1 = array(0.0, 0.0 , 0.0);
			$_total_subject_type_2_unit_term_2 = array(0.0, 0.0 , 0.0);
			$_total_subject_type_3_unit_term_1 = array(0.0, 0.0 , 0.0);
			$_total_subject_type_3_unit_term_2 = array(0.0, 0.0 , 0.0);

			$_total_subject_type_1_hour_term_1 = array(0, 0, 0);
			$_total_subject_type_1_hour_term_2 = array(0, 0, 0);
			$_total_subject_type_2_hour_term_1 = array(0, 0, 0);
			$_total_subject_type_2_hour_term_2 = array(0, 0, 0);
			$_total_subject_type_3_hour_term_1 = array(0, 0, 0);
			$_total_subject_type_3_hour_term_2 = array(0, 0, 0);

			$_total_unit_term_1 = array(0.0, 0.0, 0.0);
			$_total_unit_term_2 = array(0.0, 0.0, 0.0);

			$_total_hour_term_1 = array(0, 0, 0);
			$_total_hour_term_2 = array(0, 0, 0);

			$_sql_search = "select * from curriculum_subject_mappings where curriculum_id = '" . $_curriculum_id . "' limit 2 ";
			$_resSearch = mysqli_query($_connection,$_sql_search);
			$_rows_result = mysqli_num_rows($_resSearch);
			$_loop = 0;
	?>

	<? if($_rows_result > 0){ ?>
		<div align="center">
			<table class="admintable">

				<?for($_i=0;$_i<3;$_i++){ ?>
					<tr height="35px">
						<td colspan="8" align="center">
							<br/>
							<font size="4">ชั้นมัธยมศึกษาปีที่ <?=$_year[$_i]?></font>
						</td>
					</tr>
					<tr>
						<td valign="top" class="key" align="center" colspan="4">ภาคเรียนที่ 1</td>
						<td valign="top" class="key" align="center" colspan="4">ภาคเรียนที่ 2</td>
					</tr>
					<tr>
						<td class="key" align="center" width="70px">รหัสวิชา</td>
						<td class="key" align="center" width="160px">ชื่อวิชา</td>
						<td class="key" align="center" width="50px">หน่วยกิต</td>
						<td class="key" align="center" width="50px">ชั่วโมง</td>
						<td class="key" align="center" width="70px">รหัสวิชา</td>
						<td class="key" align="center" width="160px">ชื่อวิชา</td>
						<td class="key" align="center" width="50px">หน่วยกิต</td>
						<td class="key" align="center" width="50px">ชั่วโมง</td>
					</tr>

					<!-- วิชาพื้นฐาน -->
					<tr>
						<td colspan="4" style="padding-left:85px;"><b>รายวิชาพื้นฐาน</b></td>
						<td colspan="4" style="padding-left:85px;"><b>รายวิชาพื้นฐาน</b></td>
					</tr>
					<tr>
						<td valign="top" colspan="4" >
							<table>
								<? $_sql_semester_1 = $_sql[$_i] . " and curriculum_mapping_semester = '1' and s.SubjectType = 'พื้นฐาน' " . $_xx; ?>
								<? $_res = mysqli_query($_connection,$_sql_semester_1); ?>
								<? while($_dat = mysqli_fetch_assoc($_res)){ ?> 
										<?php
											$_bg_color = "";
											if($_dat['SubjectType']=="พื้นฐาน"){
												$_bg_color = "bgcolor='#F4FFEB'";
											}else if($_dat['SubjectType']=="เพิ่มเติม"){
												$_bg_color = "bgcolor='#EBFAFF'";
											}else{
												$_bg_color = "bgcolor='#FEDFDF'";
											}
											$_bg_color = "";
											$_total_subject_type_1_unit_term_1[$_i] += $_dat['SubjectUnit'];
											$_total_subject_type_1_hour_term_1[$_i] += $_dat['SubjectHour'];
										?>

										<tr <?=$_bg_color?>>
											<td valign="top" width="70px" align="center"><?=$_dat['SubjectHour']!="0"?" ".$_dat['SubjectCode']:"<font color='red'>*</font>".$_dat['SubjectCode']?></td>
											<td valign="top" width="160px" ><?=$_dat['SubjectName']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectUnit']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectHour']?></td>
										</tr>
								<? } mysqli_free_result($_res); ?>
							</table>
						</td>
						<td valign="top" colspan="4">
							<table>
								<? $_sql_semester_2 = $_sql[$_i] . " and curriculum_mapping_semester = '2' and s.SubjectType = 'พื้นฐาน' " . $_xx; ?>
								<? $_res = mysqli_query($_connection,$_sql_semester_2); ?>
								<? while($_dat = mysqli_fetch_assoc($_res)){ ?> 
										<?php
											$_bg_color = "";
											if($_dat['SubjectType']=="พื้นฐาน"){
												$_bg_color = "bgcolor='#F4FFEB'";
											}else if($_dat['SubjectType']=="เพิ่มเติม"){
												$_bg_color = "bgcolor='#EBFAFF'";
											}else{
												$_bg_color = "bgcolor='#FEDFDF'";
											}
											$_bg_color = "";
											$_total_subject_type_1_unit_term_2[$_i] += $_dat['SubjectUnit'];
											$_total_subject_type_1_hour_term_2[$_i] += $_dat['SubjectHour'];
										?>

										<tr <?=$_bg_color?>>
											<td valign="top" width="70px" align="center"><?=$_dat['SubjectHour']!="0"?" ".$_dat['SubjectCode']:"<font color='red'>*</font>".$_dat['SubjectCode']?></td>
											<td valign="top" width="160px" ><?=$_dat['SubjectName']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectUnit']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectHour']?></td>
										</tr>
								<? }  mysqli_free_result($_res);?>
							</table>
						</td>
					</tr>
					<tr bgcolor='#F4FFEB'>
						<td colspan="2" style="padding-left:22px;"><b>รวมสาระการเรียนรู้พื้นฐาน</b></td>
						<td align="center"><b><?=number_format($_total_subject_type_1_unit_term_1[$_i],1)?></b></td>
						<td align="center"><b><?=$_total_subject_type_1_hour_term_1[$_i]?></b></td>
						<td colspan="2" style="padding-left:22px;"><b>รวมสาระการเรียนรู้พื้นฐาน</b></td>
						<td align="center"><b><?=number_format($_total_subject_type_1_unit_term_2[$_i],1)?></b></td>
						<td align="center"><b><?=$_total_subject_type_1_hour_term_2[$_i]?></b></td>
					</tr>

					<!-- วิชาเพิ่มเติม -->
					<tr>
						<td colspan="4" style="padding-left:85px;"><b>รายวิชาเพิ่มเติม</b></td>
						<td colspan="4" style="padding-left:85px;"><b>รายวิชาเพิ่มเติม</b></td>
					</tr>
					<tr>
						<td valign="top" colspan="4" >
							<table>
								<? $_sql_semester_1 = $_sql[$_i] . " and curriculum_mapping_semester = '1' and s.SubjectType = 'เพิ่มเติม' " . $_xx; ?>
								<? $_res = mysqli_query($_connection,$_sql_semester_1); ?>
								<? while($_dat = mysqli_fetch_assoc($_res)){ ?> 
										<?php
											$_bg_color = "";
											if($_dat['SubjectType']=="พื้นฐาน"){
												$_bg_color = "bgcolor='#F4FFEB'";
											}else if($_dat['SubjectType']=="เพิ่มเติม"){
												$_bg_color = "bgcolor='#EBFAFF'";
											}else{
												$_bg_color = "bgcolor='#FEDFDF'";
											}
											$_bg_color = "";
											$_total_subject_type_2_unit_term_1[$_i] += $_dat['SubjectUnit'];
											$_total_subject_type_2_hour_term_1[$_i] += $_dat['SubjectHour'];
										?>

										<tr <?=$_bg_color?>>
											<td valign="top" width="70px" align="center"><?=$_dat['SubjectHour']!="0"?" ".$_dat['SubjectCode']:"<font color='red'>*</font>".$_dat['SubjectCode']?></td>
											<td valign="top" width="160px" ><?=$_dat['SubjectName']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectUnit']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectHour']?></td>
										</tr>
								<? } mysqli_free_result($_res)?>
							</table>
						</td>
						<td valign="top" colspan="4">
							<table>
								<? $_sql_semester_2 = $_sql[$_i] . " and curriculum_mapping_semester = '2' and s.SubjectType = 'เพิ่มเติม' " . $_xx; ?>
								<? $_res = mysqli_query($_connection,$_sql_semester_2); ?>
								<? while($_dat = mysqli_fetch_assoc($_res)){ ?> 
										<?php
											$_bg_color = "";
											if($_dat['SubjectType']=="พื้นฐาน"){
												$_bg_color = "bgcolor='#F4FFEB'";
											}else if($_dat['SubjectType']=="เพิ่มเติม"){
												$_bg_color = "bgcolor='#EBFAFF'";
											}else{
												$_bg_color = "bgcolor='#FEDFDF'";
											}
											$_bg_color = "";
											$_total_subject_type_2_unit_term_2[$_i] += $_dat['SubjectUnit'];
											$_total_subject_type_2_hour_term_2[$_i] += $_dat['SubjectHour'];
										?>

										<tr <?=$_bg_color?>>
											<td valign="top" width="70px" align="center"><?=$_dat['SubjectHour']!="0"?" ".$_dat['SubjectCode']:"<font color='red'>*</font>".$_dat['SubjectCode']?></td>
											<td valign="top" width="160px" ><?=$_dat['SubjectName']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectUnit']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectHour']?></td>
										</tr>
								<? } mysqli_free_result($_res);?>
							</table>
						</td>
					</tr>
					<tr bgcolor='#EBFAFF'>
						<td colspan="2" style="padding-left:22px;"><b>รวมสาระการเรียนรู้เพิ่มเติม</b></td>
						<td align="center"><b><?=number_format($_total_subject_type_2_unit_term_1[$_i],1)?></b></td>
						<td align="center"><b><?=$_total_subject_type_2_hour_term_1[$_i]?></b></td>
						<td colspan="2" style="padding-left:22px;"><b>รวมสาระการเรียนรู้เพิ่มเติม</b></td>
						<td align="center"><b><?=number_format($_total_subject_type_2_unit_term_2[$_i],1)?></b></td>
						<td align="center"><b><?=$_total_subject_type_2_hour_term_2[$_i]?></b></td>
					</tr>

					<!-- วิชากิจกรรมพัฒนาผู้เรียน -->
					<tr>
						<td colspan="4" style="padding-left:85px;"><b>กิจกรรมพัฒนาผู้เรียน</b></td>
						<td colspan="4" style="padding-left:85px;"><b>กิจกรรมพัฒนาผู้เรียน</b></td>
					</tr>
					<tr>
						<td valign="top" colspan="4" >
							<table>
								<? $_sql_semester_1 = $_sql[$_i] . " and curriculum_mapping_semester = '1' and s.SubjectType = 'กิจกรรมพัฒนาผู้เรียน' " . $_xx; ?>
								<? $_res = mysqli_query($_connection,$_sql_semester_1); ?>
								<? while($_dat = mysqli_fetch_assoc($_res)){ ?> 
										<?php
											$_bg_color = "";
											if($_dat['SubjectType']=="พื้นฐาน"){
												$_bg_color = "bgcolor='#F4FFEB'";
											}else if($_dat['SubjectType']=="เพิ่มเติม"){
												$_bg_color = "bgcolor='#EBFAFF'";
											}else{
												$_bg_color = "bgcolor='#FEDFDF'";
											}
											$_bg_color = "";
											$_total_subject_type_3_unit_term_1[$_i] += $_dat['SubjectUnit'];
											$_total_subject_type_3_hour_term_1[$_i] += $_dat['SubjectHour'];
										?>

											<tr <?=$_bg_color?>>
											<td valign="top" width="70px" align="center"><?=$_dat['SubjectHour']!="0"?" ".$_dat['SubjectCode']:"<font color='red'>*</font>".$_dat['SubjectCode']?></td>
											<td valign="top" width="160px" ><?=$_dat['SubjectName']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectUnit']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectHour']?></td>
										</tr>
								<? } ?>
							</table>
						</td>
						<td valign="top" colspan="4">
							<table>
								<? $_sql_semester_2 = $_sql[$_i] . " and curriculum_mapping_semester = '2' and s.SubjectType = 'กิจกรรมพัฒนาผู้เรียน' " . $_xx; ?>
								<? $_res = mysqli_query($_connection,$_sql_semester_2); ?>
								<? while($_dat = mysqli_fetch_assoc($_res)){ ?> 
										<?php
											$_bg_color = "";
											if($_dat['SubjectType']=="พื้นฐาน"){
												$_bg_color = "bgcolor='#F4FFEB'";
											}else if($_dat['SubjectType']=="เพิ่มเติม"){
												$_bg_color = "bgcolor='#EBFAFF'";
											}else{
												$_bg_color = "bgcolor='#FEDFDF'";
											}
											$_bg_color = "";
											$_total_subject_type_3_unit_term_2[$_i] += $_dat['SubjectUnit'];
											$_total_subject_type_3_hour_term_2[$_i] += $_dat['SubjectHour'];
										?>

										<tr <?=$_bg_color?>>
											<td valign="top" width="70px" align="center"><?=$_dat['SubjectHour']!="0"?" ".$_dat['SubjectCode']:"<font color='red'>*</font>".$_dat['SubjectCode']?></td>
											<td valign="top" width="160px" ><?=$_dat['SubjectName']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectUnit']?></td>
											<td valign="top" width="50px" align="center"><?=$_dat['SubjectHour']?></td>
										</tr>
								<? } ?>
							</table>
						</td>
					</tr>
					<tr bgcolor='#FEDFDF'>
						<td colspan="2" style="padding-left:22px;"><b>รวมสาระกิจกรรมพัฒนาผู้เรียน</b></td>
						<td align="center"><b><?=number_format($_total_subject_type_3_unit_term_1[$_i],1)?></b></td>
						<td align="center"><b><?=$_total_subject_type_3_hour_term_1[$_i]?></b></td>
						<td colspan="2" style="padding-left:22px;"><b>รวมสาระกิจกรรมพัฒนาผู้เรียน</b></td>
						<td align="center"><b><?=number_format($_total_subject_type_3_unit_term_2[$_i],1)?></b></td>
						<td align="center"><b><?=$_total_subject_type_3_hour_term_2[$_i]?></b></td>
					</tr>
					<tr>
						<td class="key" colspan="2" style="padding-left:22px;">รวมทั้งหมด</td>
						<td align="center" class="key">
							<b>
								<?
									$_total_unit_term_1[$_i] = $_total_subject_type_1_unit_term_1[$_i]+$_total_subject_type_2_unit_term_1[$_i]+$_total_subject_type_3_unit_term_1[$_i];
									echo number_format($_total_unit_term_1[$_i]);
								?>
							</b>
						</td>
						<td align="center" class="key">
							<b>
								<?php
									$_total_hour_term_1[$_i] = $_total_subject_type_1_hour_term_1[$_i]+$_total_subject_type_2_hour_term_1[$_i]+$_total_subject_type_3_hour_term_1[$_i];
									echo $_total_hour_term_1[$_i];
								?>
							</b>
						</td>
						<td class="key" colspan="2" style="padding-left:22px;">รวมทั้งหมด</td>
						<td align="center" class="key">
							<b>
								<?
									$_total_unit_term_2[$_i] = $_total_subject_type_1_unit_term_2[$_i]+$_total_subject_type_2_unit_term_2[$_i]+$_total_subject_type_3_unit_term_2[$_i];
									echo number_format($_total_unit_term_2[$_i]);
								?>
							</b>
						</td>
						<td align="center" class="key">
							<b>
								<?php
									$_total_hour_term_2[$_i] = $_total_subject_type_1_hour_term_2[$_i]+$_total_subject_type_2_hour_term_2[$_i]+$_total_subject_type_3_hour_term_2[$_i];
									echo $_total_hour_term_2[$_i];
								?>
							</b>
						</td>
					</tr>
					<tr></tr>
				<? } ?>
			</table>
			<br/>
			<div align="center"><font color='red'>*</font> หมายถึง รายวิชาที่เรียนกนอกตารางเรียน</div>
		</div>
	<? } else { ?>

		<div align="center">
			<br/><br/>
			<font color="red">
				ไม่พบข้อมูล ตามเงื่อนไขที่สืบค้น
			</font>
			<br/><br/>
		</div>

	<? } ?>
<? } //end if click search button ?>
</div>

