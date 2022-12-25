
<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_curriculum/index">	
						<img src="../images/graduation.png" alt="" width="48" height="48" />
				</a>
				</td>
				<td width="64%"><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>2.1 สืบค้นรายวิชา</strong></font></span>
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
					<td class="key" colspan="3"> &nbsp; ตัวเลือกในการสืบค้นรายวิชา</td>
				</tr>
				<tr>
					<td align="right" > รหัสวิชา </td>
					<td>
						<input type="text" name="subject_code" value="<?=(isset($_POST['subject_code'])?trim($_POST['subject_code']):"")?>" class="inputboxUpdate" size="5" maxlength="6" /> 
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right"  width="85px"> ชื่อวิชา </td>
								<td > <input type="text" name="subject_name" value="<?=(isset($_POST['subject_name'])?trim($_POST['subject_name']):"")?>" size="20" class="inputboxUpdate" maxlength="100" /> 
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right"> หน่วยการเรียน </td>
					<td>
						<?php
							$_sql = "select distinct SubjectUnit from curriculum_subjects order by 1";
							$_resUnit = mysqli_query($_connection,$_sql);
						?> 
						<select name="subject_unit" class="inputboxUpdate">
							<option value=""    <?=(isset($_POST['subject_unit']) && $_POST['subject_unit']==""?"selected":"")?> ></option>
							<? while($_dat = mysqli_fetch_assoc($_resUnit)) { ?> 
								<option value="<?=$_dat['SubjectUnit']?>" <?=(isset($_POST['subject_unit']) && $_POST['subject_unit']==$_dat['SubjectUnit']?"selected":"")?> >
									<?=$_dat['SubjectUnit']?>
								</option>
							<? } ?>
						</select>
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right"  width="85px"> ประเภทวิชา </td>
								<td> 
									<?php
										$_sql = "select distinct SubjectType from curriculum_subjects order by 1";
										$_resSubjectType = mysqli_query($_connection,$_sql);
									?> 
									<select name="subject_type" class="inputboxUpdate">
										<option value="" <?=(isset($_POST['subject_type']) && $_POST['subject_type']==""?"selected":"")?>>ทั้งหมด</option>
										<? while($_dat = mysqli_fetch_assoc($_resSubjectType)){ ?> 
												<option value="<?=$_dat['SubjectType']?>" <?=(isset($_POST['subject_type']) && $_POST['subject_type']==$_dat['SubjectType']?"selected":"")?>>
													<?=$_dat['SubjectType']?>
												</option>
										<? } ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
					
				</tr>
				<tr>
					<td align="right"> ระดับชั้น </td>
					<td> 
						<?php
							$_sql = "select distinct SubjectLevel from curriculum_subjects order by 1";
							$_resLevel = mysqli_query($_connection,$_sql);
						?> 
						<select name="subject_level" class="inputboxUpdate">
							<option value=""    <?=(isset($_POST['subject_level']) && $_POST['subject_level']==""?"selected":"")?> >ทั้งหมด</option>
							<? while($_dat = mysqli_fetch_assoc($_resLevel)) { ?> 
								<option value="<?=$_dat['SubjectLevel']?>" <?=(isset($_POST['subject_level']) && $_POST['subject_level']==$_dat['SubjectLevel']?"selected":"")?> >
									ชั้นมัธยมศึกษาปีที่ <?=$_dat['SubjectLevel']?>
								</option>
							<? } ?>
						</select>
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right"  width="85px"> กลุ่มสาระ</td>
								<td> 
									<?php
										$_sqlGroup = "
											select c.SubjectGroup,g.subject_group_name
											from curriculum_subjects c left join curriculum_subject_group g
												on (c.SubjectGroup = g.subject_group_id)
											group by c.SubjectGroup
											order by 1";
										$_resGroup = mysqli_query($_connection,$_sqlGroup);

									?>
									<select name="subject_group" class="inputboxUpdate">
										<option value="" <?=(isset($_POST['subject_group']) && $_POST['subject_group']==""?"selected":"")?>>ทั้งหมด</option>	
										<? while($_datG = mysqli_fetch_assoc($_resGroup)){ ?>
											<option value="<?=$_datG['SubjectGroup']?>" <?=(isset($_POST['subject_group']) && $_POST['subject_group']==$_datG['SubjectGroup']?"selected":"")?>>
												<?=$_datG['SubjectGroup'] . ' - ' . $_datG['subject_group_name']?>
											</option>
										<? } // end while ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right"> ภาคเรียนที่เปิดสอน </td>
					<td> 
						<?php
							$_sql = "select distinct SubjectSemester from curriculum_subjects order by 1";
							$_resSemester = mysqli_query($_connection,$_sql);
						?> 
						<select name="subject_semester" class="inputboxUpdate">
							<option value="" <?=(isset($_POST['subject_semester']) && $_POST['subject_semester']==""?"selected":"")?> >ทั้งหมด</option>
							<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
								<option value="<?=$_dat['SubjectSemester']?>" <?=(isset($_POST['subject_semester']) && $_POST['subject_semester']==$_dat['SubjectSemester']?"selected":"")?> >
									ภาคเรียนที่ <?=$_dat['SubjectSemester']?>
								</option>
							<? } ?>
						</select>
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right" width="85px"> การจัดกลุ่มเรียน </td>
								<td> 
									<?php
										$_sqlSplit = "select distinct is_split_class from curriculum_subjects order by 1";
										$_resSplit = mysqli_query($_connection,$_sqlSplit);

									?>
									<select name="is_split_class" class="inputboxUpdate">
										<option value="" <?=(isset($_POST['is_split_class']) && $_POST['is_split_class']==""?"selected":"")?>>ทั้งหมด</option>	
										<? while($_datG = mysqli_fetch_assoc($_resSplit)){ ?>
											<option value="<?=$_datG['is_split_class']?>" <?=(isset($_POST['is_split_class']) && $_POST['is_split_class']==$_datG['is_split_class']?"selected":"")?>>
												<?=$_datG['is_split_class'] . ' - ' . displayIsSplitClass($_datG['is_split_class'])?>
											</option>
										<? } // end while ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right"> สถานะรายวิชา </td>
					<td> 
						<?php
							$_sql = "select distinct subject_status from curriculum_subjects order by 1";
							$_resSemester = mysqli_query($_connection,$_sql);
						?> 
						<select name="subject_status" class="inputboxUpdate">
						<option value="" <?=(isset($_POST['subject_status']) && $_POST['subject_status']==""?"selected":"")?> >ทุกสถานะ</option>
							<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
								<option value="<?=$_dat['subject_status']?>" <?=(isset($_POST['subject_status']) && $_POST['subject_status']==$_dat['subject_status']?"selected":"")?> >
									<?=($_dat['subject_status']=="ACTIVE"?"เปิดสอน":"หยุดทำการสอน")?>
								</option>
							<? } ?>
						</select>
					</td>
					<td colspan="2">
						<input type="submit" name="search"  value="สืบค้น" /> 
						<input type="reset" name="reset" value="ล้างข้อมูล" /> 
					</td>
				</tr>
			</table>
		</form>
	</div>


	<?php
		$_processing_text = "";
		$_processing_result = false;
		$_text = "";
		$_rows_result = 0;

		if(isset($_POST['search'])){

			$_sql_search = "
					select
						s.*,
						g.subject_group_name 
					from 
						curriculum_subjects s left join curriculum_subject_group g
						on (s.SubjectGroup = g.subject_group_id)
					where 1=1 
				";
			if(trim($_POST['subject_code']) != ""){
				$_sql_search .= " AND SubjectCode like '%" . trim($_POST['subject_code']) . "%' ";
			}

			if(trim($_POST['subject_name']) != ""){
				$_sql_search .= " AND SubjectName like '%" . trim($_POST['subject_name']) . "%' ";
			}

			if(trim($_POST['subject_unit']) != ""){
				$_sql_search .= " AND SubjectUnit = '" . $_POST['subject_unit'] . "' ";
			}

			if(trim($_POST['subject_type']) != ""){
				$_sql_search .= " AND SubjectType = '" . $_POST['subject_type'] . "' ";
			}

			if(trim($_POST['subject_group']) != ""){
				$_sql_search .= " AND SubjectGroup = '" . $_POST['subject_group'] . "' ";
			}

			if(trim($_POST['subject_level']) != ""){
				
				if($_POST['subject_level']=="0"){
					$_sql_search .= " AND SubjectLevel = '" . $_POST['subject_level'] . "' ";
				}else{
					$_sql_search .= " AND SubjectLevel IN (0,'" . $_POST['subject_level'] . "') ";
				}
			}

			if(trim($_POST['subject_semester']) != ""){
				$_sql_search .= " AND SubjectSemester = '" . $_POST['subject_semester'] . "' ";
			}

			if(trim($_POST['is_split_class']) != ""){
				$_sql_search .= " AND is_split_class = '" . $_POST['is_split_class'] . "' ";
			}

			if(trim($_POST['subject_status']) != ""){
				$_sql_search .= " AND subject_status = '" . $_POST['subject_status'] . "' ";
			}


			$_sql_search .= "order by SubjectGroup,SubjectLevel,SubjectType,SubjectSemester";
			$_resSearch = mysqli_query($_connection,$_sql_search);
			$_rows_result = mysqli_num_rows($_resSearch);
			$_ii = 1;
	?>

	<? if($_rows_result > 0){ ?>
		<div align="center">
			<table class="admintable">
				<tr height="35px">
					<td colspan="10" align="right">
						<font color='green'><b>พบข้อมูลรายวิชาที่ตรงตามเงื่อนไขสืบค้นทั้งหมด <?=$_rows_result?> วิชา</b></font>
					</td>
				</tr>
				<tr>
					<td class="key" align="center" width="25px" >ที่</td>
					<td class="key" align="center" width="70px">รหัสวิชา</td>
					<td class="key" align="center" width="210px">ชื่อวิชา</td>
					<td class="key" align="center" width="50px">หน่วย<br/>กิต</td>
					<td class="key" align="center" width="40px">ชม.<br/>เรียน</td>
					<td class="key" align="center" width="110px">ประเภทวิชา</td>
					<td class="key" align="center" width="110px">กลุ่มสาระ</td>
					<td class="key" align="center" width="80px">ระดับชั้น</td>
					<td class="key" align="center" width="50px">ภาค<br/>เรียน</td>
					<td class="key" align="center" width="100px">จัดกลุ่ม<br/>เรียน</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_resSearch)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_ii++?></td>
							<td align="center"><?=$_dat['SubjectCode']?></td>
							<td><?=$_dat['SubjectName']?></td>
							<td align="center"><?=$_dat['SubjectUnit']?></td>
							<td align="center"><?=$_dat['SubjectHour']?></td>
							<td align="center">
								<?
									// ประเภทวิชา
									if($_dat['SubjectType']=="กิจกรรมพัฒนาผู้เรียน"){
										echo "กก.พัฒนาผู้เรียน";
									} else{
										echo $_dat['SubjectType'];
									}
								?>
							</td>
							<td align="center">
								<?
									if(strlen($_dat['subject_group_name'])>40){
										echo displaySubjectGroupShort($_dat['SubjectGroup']);
									}else{
										echo $_dat['subject_group_name'];
									}
								?>
							</td>
							<td align="center">ม.<?=$_dat['SubjectLevel']?></td>
							<td align="center"><?=$_dat['SubjectSemester']?></td>
							<td align="center">
								<?
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
				<? } // end while loop display result ?>
			</table>
		</div>
	<? } else { ?>

		<div align="center">
			<br/><br/>
			<font color="red">
				ไม่พบข้อมูลรายวิชา ตามเงื่อนไขที่สืบค้น
			</font>
			<br/><br/>
		</div>

	<? } ?>
<? } //end if click search button ?>
</div>

