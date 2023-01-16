
<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_reg/index">	
						<img src="../images/color.png" alt="" width="48" height="48" />
				</a>
				</td>
				<td width="64%"><strong><font color="#990000" size="4">Student registration management</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.1 สืบค้นข้อมูลนักเรียนที่นำเข้าจากฐานข้อมูลระบบ LEC</strong></font></span>
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
					<td class="key" colspan="3"> &nbsp; ตัวเลือกในการสืบค้นข้อมูลนักเรียน</td>
				</tr>
				<tr>
					<td align="right" > เลขประจำตัว </td>
					<td>
						<input type="text" name="student_id" value="<?=(isset($_POST['student_id'])?trim($_POST['student_id']):"")?>" class="inputboxUpdate" size="5" maxlength="5" /> 
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right"  width="85px"> เพศ </td>
								<td> 
									<?php
										$_sql = "select distinct student_gender from students_lec_system order by 1";
										$_resstudent_gender = mysqli_query($_connection,$_sql);
									?> 
									<select name="student_gender" class="inputboxUpdate">
										<option value="" <?=(isset($_POST['student_gender']) && $_POST['student_gender']==""?"selected":"")?>>ทั้งหมด</option>
										<? while($_dat = mysqli_fetch_assoc($_resstudent_gender)){ ?> 
												<option value="<?=$_dat['student_gender']?>" <?=(isset($_POST['student_gender']) && $_POST['student_gender']==$_dat['student_gender']?"selected":"")?>>
													<?=$_dat['student_gender']?>
												</option>
										<? } ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" > ชื่อ </td>
					<td>
						<input type="text" name="student_firstname" value="<?=(isset($_POST['student_firstname'])?trim($_POST['student_firstname']):"")?>" class="inputboxUpdate" size="15" maxlength="30" /> 
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right" width="85px"> ระดับชั้น </td>
								<td> 
									<?php
										$_sql = "select distinct student_class_level from students_lec_system order by 1";
										$_resLevel = mysqli_query($_connection,$_sql);
									?> 
									<select name="student_class_level" class="inputboxUpdate">
										<option value=""    <?=(isset($_POST['student_class_level']) && $_POST['student_class_level']==""?"selected":"")?> >ทั้งหมด</option>
										<? while($_dat = mysqli_fetch_assoc($_resLevel)) { ?> 
											<option value="<?=$_dat['student_class_level']?>" <?=(isset($_POST['student_class_level']) && $_POST['student_class_level']==$_dat['student_class_level']?"selected":"")?> >
												ชั้นมัธยมศึกษาปีที่ <?=$_dat['student_class_level']?>
											</option>
										<? } ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right"  width="85px"> นามสกุล </td>
					<td >
						<input type="text" name="student_lastname" value="<?=(isset($_POST['student_lastname'])?trim($_POST['student_lastname']):"")?>" size="15" class="inputboxUpdate" maxlength="30" /> 
					</td>
					<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td align="right"  width="85px"> ห้อง</td>
								<td> 
									<?php
										$_sqlGroup = "select distinct student_room from students_lec_system order by 1";
										$_resGroup = mysqli_query($_connection,$_sqlGroup);

									?>
									<select name="student_room" class="inputboxUpdate">
										<option value="" <?=(isset($_POST['student_room']) && $_POST['student_room']==""?"selected":"")?>>ทั้งหมด</option>	
										<? while($_datG = mysqli_fetch_assoc($_resGroup)){ ?>
											<option value="<?=$_datG['student_room']?>" <?=(isset($_POST['student_room']) && $_POST['student_room']==$_datG['student_room']?"selected":"")?>>
												<?=$_datG['student_room']?>
											</option>
										<? } // end while ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right"> ปีการศึกษา </td>
					<td> 
						<?php
							$_sql = "select distinct acadyear from students_lec_system order by 1";
							$_resSemester = mysqli_query($_connection,$_sql);
						?> 
						<select name="acadyear" class="inputboxUpdate">
						<option value="" <?=(isset($_POST['acadyear']) && $_POST['acadyear']==""?"selected":"")?> >ทั้งหมด</option>
							<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
								<option value="<?=$_dat['acadyear']?>" <?=(isset($_POST['acadyear']) && $_POST['acadyear']==$_dat['acadyear']?"selected":"")?> >
									<?=$_dat['acadyear']?>
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
						*
					from 
						students_lec_system s 
					where 1=1 
				";
			if(trim($_POST['student_id']) != ""){
				$_sql_search .= " AND student_id like '" . trim($_POST['student_id']) . "' ";
			}

			if(trim($_POST['student_firstname']) != ""){
				$_sql_search .= " AND student_firstname like '%" . trim($_POST['student_firstname']) . "%' ";
			}

			if(trim($_POST['student_lastname']) != ""){
				$_sql_search .= " AND student_lastname like '%" . trim($_POST['student_lastname']) . "%' ";
			}

			if(trim($_POST['student_gender']) != ""){
				$_sql_search .= " AND student_gender = '" . $_POST['student_gender'] . "' ";
			}

			if(trim($_POST['student_class_level']) != ""){
				$_sql_search .= " AND student_class_level = '" . $_POST['student_class_level'] . "' ";
			}

			if(trim($_POST['student_room']) != ""){
				$_sql_search .= " AND student_room = '" . $_POST['student_room'] . "' ";
			}

			if(trim($_POST['acadyear']) != ""){
				$_sql_search .= " AND acadyear = '" . $_POST['acadyear'] . "' ";
			}

			$_sql_search .= "order by student_class_level, student_room, student_gender, convert(student_firstname using tis620) ";
			$_resSearch = mysqli_query($_connection,$_sql_search);
			$_rows_result = mysqli_num_rows($_resSearch);
			$_ii = 1;
	?>

	<? if($_rows_result > 0){ ?>
		<div align="center">
			<table class="admintable">
				<tr height="35px">
					<td colspan="7" align="right">
						<font color='green'><b>พบข้อมูลรายวิชาที่ตรงตามเงื่อนไขสืบค้นทั้งหมด <?=$_rows_result?> รายการ</b></font>
					</td>
				</tr>
				<tr>
					<td class="key" align="center" width="25px" >ที่</td>
					<td class="key" align="center" width="85px">เลขประจำตัว</td>
					<td class="key" align="center" width="190px">ชื่อ - สกุล</td>
					<td class="key" align="center" width="60px">เพศ</td>
					<td class="key" align="center" width="95px">ระดับชั้น</td>
					<td class="key" align="center" width="50px">ห้อง</td>
					<td class="key" align="center" width="100px">ปีการศึกษา</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_resSearch)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_ii++?></td>
							<td align="center"><?=$_dat['student_id']?></td>
							<td><?=$_dat['student_prefix'].$_dat['student_firstname']. ' ' . $_dat['student_lastname']?></td>
							<td align="center"><?=$_dat['student_gender']?></td>
							<td align="center"><?=$_dat['student_class_level']?></td>
							<td align="center"><?=$_dat['student_room']?></td>
							<td align="center"><?=$_dat['acadyear']?></td>
						</tr>
				<? } // end while loop display result ?>
			</table>
		</div>
	<? } else { ?>

		<div align="center">
			<br/><br/>
			<font color="red">
				ไม่พบข้อมูล นักเรียน ตามเงื่อนไขที่สืบค้น
			</font>
			<br/><br/>
		</div>

	<? } ?>
<? } //end if click search button ?>
</div>

