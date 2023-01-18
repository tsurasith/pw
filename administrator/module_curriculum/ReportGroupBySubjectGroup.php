
<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_curriculum/index">	
						<img src="../images/graduation.png" alt="" width="48" height="48" />
				</a>
				</td>
				<td width="64%"><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.1 รายงานสรุปหน่วยการเรียน แยกตามกลุ่มสาระการเรียนรู้</strong></font></span>
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
					<td align="right" valign="top"> เลือกแผนการเรียน </td>
					<td>
						<?php
							$_sql = "select * from curriculums order by curriculum_level,curriculum_code";
							$_resCurr = mysqli_query($_connection,$_sql);
							$_curr_token = "";
						?> 
						<select name="curriculum_token" class="inputboxUpdate">
							<option value="-"    <?=(isset($_POST['curriculum_token']) && $_POST['curriculum_token']==""?"selected":"")?> ></option>
							<? while($_dat = mysqli_fetch_assoc($_resCurr)) { ?> 
								<? $_curr_token = $_dat['curriculum_id'] . '-' . $_dat['curriculum_level']; ?>
								<option value="<?=$_curr_token?>" <?=(isset($_POST['curriculum_token']) && $_POST['curriculum_token']==$_curr_token?"selected":"")?> >
									<?=$_dat['curriculum_code'] . ' ' . $_dat['curriculum_name']?>
								</option>
							<? } ?>
						</select>
						<br/>
						<input type="checkbox" name="hide_act" value="9" class="inputboxUpdate" <?=isset($_POST['hide_act'])=="9"?"checked":""?> /> ไม่แสดงกิจกรรมพัฒนาผู้เรียน
					</td>
					<td colspan="2" valign="top">
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
			$_act_condition = "";
			if(isset($_POST['hide_act']) && $_POST['hide_act']=="9"){
				$_act_condition = "and g.subject_group_id != 9 ";
			}

			$_sql_subject_group = "
						select
								g.subject_group_name,
								sum(if(s.SubjectType = 'พื้นฐาน',s.SubjectUnit,0)) as 'base_units',
								sum(if(s.SubjectTYpe = 'เพิ่มเติม',s.SubjectUnit,0)) as 'additional_units',
								sum(if(s.SubjectType = 'พื้นฐาน',s.SubjectHour,0)) as 'base_hours',
								sum(if(s.SubjectTYpe = 'เพิ่มเติม',s.SubjectHour,0)) as 'additional_hours',
								sum(if(s.SubjectType = 'กิจกรรมพัฒนาผู้เรียน',s.SubjectUnit,0)) as 'act_units',
								sum(if(s.SubjectTYpe = 'กิจกรรมพัฒนาผู้เรียน',s.SubjectHour,0)) as 'act_hours'
						from
							curriculum_subject_mappings m left join curriculum_subjects s 
							on (m.SubjectCode = s.SubjectCode) left join curriculum_subject_group g
							on (s.SubjectGroup = g.subject_group_id)
						where
							curriculum_id = '" . $_curriculum_id . "' " . $_act_condition . " 
						group by
							g.subject_group_name
						order by 
							g.subject_group_id
						;
				";
			$_res = mysqli_query($_connection,$_sql_subject_group);
			$_rows_result = mysqli_num_rows($_res);

			$_total = array(0.0, 0, 0.0, 0, 0.0, 0);

			$_total_level = array(0.0, 0, 0.0, 0, 0.0, 0);
	?>

	<? if($_rows_result > 0){ ?>
		<div align="center">

		<?php
			$_sql_curr_info = "select * from curriculums where curriculum_id = '" . $_curriculum_id . "'";
			$_res_curr_info = mysqli_query($_connection,$_sql_curr_info);
			$_dat_curr_info = mysqli_fetch_assoc($_res_curr_info);
			$_curr_display_name = $_dat_curr_info['curriculum_name'];
		?>
		<br/><br/>
			
			<table class="admintable">
				<tr height="45px">
					<td colspan="7" class="key" align="center">
						<font size="4">
						สรุปโครงสร้างแผนการเรียน <?=$_curr_display_name?>
						</font>
					</td>
				</tr>
				<tr>
					<td class="key" align="center" width="220px" rowspan="2">กลุ่มสาระการเรียนรู้</td>
					<td valign="top" class="key" align="center" colspan="3">หน่วยการเรียน</td>
					<td valign="top" class="key" align="center" colspan="3">ชั่วโมงเรียน</td>
				</tr>
					<tr>
						<td class="key" align="center" width="60px">พื้นฐาน</td>
						<td class="key" align="center" width="60px">เพิ่มเติม</td>
						<td class="key" align="center" width="60px">รวม</td>
						<td class="key" align="center" width="60px">พื้นฐาน</td>
						<td class="key" align="center" width="60px">เพิ่มเติม</td>
						<td class="key" align="center" width="60px">รวม</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res)) { ?>
				<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
						<td style="padding-left:22px;"><?=$_dat['subject_group_name']?></td>
						<td align="right" style="padding-right:10px;"><?=$_dat['base_units']>0.0?$_dat['base_units']:"-"?></td>
						<td align="right" style="padding-right:10px;"><?=$_dat['additional_units']>0.0?$_dat['additional_units']:"-"?></td>
						<td align="right" style="padding-right:10px;">
							<?php
								if($_dat['base_units']+$_dat['additional_units']>0){
									echo "<b>";
									echo number_format($_dat['base_units']+$_dat['additional_units'],1,'.','');
									echo "</b>";
								}else{
									echo "-";
								}
							?>
						</td>
						<td align="right" style="padding-right:10px;"><?=$_dat['base_hours']>0?$_dat['base_hours']:"-"?></td>
						<td align="right" style="padding-right:10px;"><?=$_dat['additional_hours']>0?$_dat['additional_hours']:"-"?></td>
						<td align="right" style="padding-right:10px;">
							<?php
								if($_dat['subject_group_name'] == 'กิจกรรมพัฒนาผู้เรียน'){

									echo $_dat['act_hours'];
									$_total[5] += $_dat['act_hours'];

								}else{
									echo "<b>";
									echo $_dat['base_hours']+$_dat['additional_hours'];
									echo "</b>";

									$_total[4] += ($_dat['base_units']+$_dat['additional_units']);
									$_total[5] += ($_dat['base_hours']+$_dat['additional_hours']);
								}
							?>
						</td>
						<?php

							$_total[0] += $_dat['base_units'];
							$_total[1] += $_dat['base_hours'];
							$_total[2] += $_dat['additional_units'];
							$_total[3] += $_dat['additional_hours'];
							
						?>
				</tr>
				<? } ?>
				<tr>
					<td class="key" align="center" height="30px">รวม</td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[0]>0.0?number_format($_total[0],1,'.',''):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[2]>0.0?number_format($_total[2],1,'.',''):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[4]>0.0?number_format($_total[4],1,'.',''):"-"?></td>

					<td class="key" align="right" style="padding-right:10px;"><?=$_total[1]>0.0?number_format($_total[1],0,'',','):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[3]>0.0?number_format($_total[3],0,'',','):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[5]>0.0?number_format($_total[5],0,'',','):"-"?></td>
				</tr>
			</table>

			<?php

				$_sql_subject_group_level = "
						select
								g.subject_group_id,
								g.subject_group_name,
								m.curriculum_mapping_level,
								sum(if(s.SubjectType = 'พื้นฐาน',s.SubjectUnit,0)) as 'base_units',
								sum(if(s.SubjectTYpe = 'เพิ่มเติม',s.SubjectUnit,0)) as 'additional_units',
								sum(if(s.SubjectType = 'พื้นฐาน',s.SubjectHour,0)) as 'base_hours',
								sum(if(s.SubjectTYpe = 'เพิ่มเติม',s.SubjectHour,0)) as 'additional_hours',
								sum(if(s.SubjectType = 'กิจกรรมพัฒนาผู้เรียน',s.SubjectUnit,0)) as 'act_units',
								sum(if(s.SubjectTYpe = 'กิจกรรมพัฒนาผู้เรียน',s.SubjectHour,0)) as 'act_hours'
						from
							curriculum_subject_mappings m left join curriculum_subjects s 
							on (m.SubjectCode = s.SubjectCode) left join curriculum_subject_group g
							on (s.SubjectGroup = g.subject_group_id)
						where
							curriculum_id = '" . $_curriculum_id . "' " . $_act_condition . " 
						group by
							g.subject_group_name, m.curriculum_mapping_level
						order by 
							g.subject_group_id, m.curriculum_mapping_level
					";	
				$_res_level = mysqli_query($_connection,$_sql_subject_group_level);			

				$_init_group = "TEST";
				$_change_bg  = "";
			?>

			<table class="admintable">
				<tr>
					<td class="key" align="center" width="195px" rowspan="2">กลุ่มสาระการเรียนรู้</td>
					<td class="key" align="center" width="40px" rowspan="2">ชั้น</td>
					<td valign="top" class="key" align="center" colspan="3">หน่วยการเรียน</td>
					<td valign="top" class="key" align="center" colspan="3">ชั่วโมงเรียน</td>
				</tr>
					<tr>
						
						<td class="key" align="center" width="55px">พื้นฐาน</td>
						<td class="key" align="center" width="55px">เพิ่มเติม</td>
						<td class="key" align="center" width="55px">รวม</td>

						<td class="key" align="center" width="55px">พื้นฐาน</td>
						<td class="key" align="center" width="55px">เพิ่มเติม</td>
						<td class="key" align="center" width="60px">รวม</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res_level)) { ?>
				<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF" >
						<td style="padding-left:22px;">
							<?php
								if($_init_group!=$_dat['subject_group_name']){
									echo $_dat['subject_group_name'];
								}
								
								$_init_group = $_dat['subject_group_name'];
							?>
						</td>
						<td align="center">ม. <?=$_dat['curriculum_mapping_level']?></td>
						<td align="right" style="padding-right:10px;"><?=$_dat['base_units']>0.0?number_format($_dat['base_units'],1,'.',''):"-"?></td>
						<td align="right" style="padding-right:10px;"><?=$_dat['additional_units']>0.0?number_format($_dat['additional_units'],1,'.',''):"-"?></td>
						<td align="right" style="padding-right:10px;">
							<?php
								if($_dat['base_units']+$_dat['additional_units']>0){
									echo "<b>";
									echo number_format($_dat['base_units']+$_dat['additional_units'],1,'.','');
									echo "</b>";
								}else{
									echo "<b>";
									echo "-";
									echo "</b>";
								}
							?>
						</td>

						<td align="right" style="padding-right:10px;"><?=$_dat['base_hours']>0?  $_dat['base_hours']:"-"?></td>
						<td align="right" style="padding-right:10px;"><?=$_dat['additional_hours']>0?$_dat['additional_hours']:"-"?></td>
						<td align="right" style="padding-right:10px;">
							<?php
								if($_dat['subject_group_name'] == 'กิจกรรมพัฒนาผู้เรียน'){
									echo "<b>";
									echo $_dat['act_hours'];
									echo "</b>";
									$_total_level[5] += $_dat['act_hours'];

								}else{
									echo "<b>";
									echo $_dat['base_hours']+$_dat['additional_hours'];
									echo "</b>";

									$_total_level[4] += ($_dat['base_units']+$_dat['additional_units']);
									$_total_level[5] += ($_dat['base_hours']+$_dat['additional_hours']);
								}
							?>
						</td>
						<?php

							$_total_level[0] += $_dat['base_units'];
							$_total_level[1] += $_dat['base_hours'];
							$_total_level[2] += $_dat['additional_units'];
							$_total_level[3] += $_dat['additional_hours'];
							
						?>
				</tr>
				<? } ?>
				<tr>
					<td class="key" align="center" height="30px" colspan="2">รวม</td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[0]>0.0?number_format($_total[0],1,'.',''):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[2]>0.0?number_format($_total[2],1,'.',''):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[4]>0.0?number_format($_total[4],1,'.',''):"-"?></td>

					<td class="key" align="right" style="padding-right:10px;"><?=$_total[1]>0.0?number_format($_total[1],0,'',','):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[3]>0.0?number_format($_total[3],0,'',','):"-"?></td>
					<td class="key" align="right" style="padding-right:10px;"><?=$_total[5]>0.0?number_format($_total[5],0,'',','):"-"?></td>
				</tr>
			</table>

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

