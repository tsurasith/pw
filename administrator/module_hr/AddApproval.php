
<style>
	#image1 {
		display:none;
		width:700px;
	}
	#image2 {
		display:none;
		width:700px;
	}
</style>

<script>
	function show1(){
		if(document.getElementById('btn1').value == "แสดงรูปภาพ"){
			document.getElementById('image1').style.display = "block";
			document.getElementById('btn1').value = "ซ่อนรูปภาพ";
		}else{
			document.getElementById('image1').style.display = "none";
			document.getElementById('btn1').value = "แสดงรูปภาพ";
		}
	}

	function show2(){
		if(document.getElementById('btn2').value == "แสดงรูปภาพ"){
			document.getElementById('image2').style.display = "block";
			document.getElementById('btn2').value = "ซ่อนรูปภาพ";
		}else{
			document.getElementById('image2').style.display = "none";
			document.getElementById('btn2').value = "แสดงรูปภาพ";
		}
	}
</script>

<?php
	if(isset($_POST['delete'])){
		$_sql_del = "delete from hr_staff_absent_approval where approve_id = '" . $_POST['approve_id'] . "'";
		$_res_del = mysqli_query($_connection,$_sql_del);

		$_sql_check = "select * from hr_staff_absent_approval where approve_id = '" . $_POST['approve_id'] . "'";
		$_res_check = mysqli_query($_connection,$_sql_check);
		if(mysqli_num_rows($_res_check)<1){
			$_sql_update_status = "
					update hr_staff_absent
					set
						request_status = 'ส่งต่องานบุคลากรแล้ว',
						updated_datetime = CURRENT_TIMESTAMP,
						updated_user     = '" . $_SESSION['user_account_id'] . "' 
					where
						absent_id = '" . $_POST['absent_id'] . "' 
				";
			$_res_status = mysqli_query($_connection,$_sql_update_status);
		}
	}
?>

<?php

	$_processing_text = "";
	$_processing_result = "";

	if(isset($_POST['add']) && $_POST['approval_id'] != "") {
		$_sql_insert_approval = "
		
					INSERT INTO `hr_staff_absent_approval`(
						`approve_id`,
						`absent_id`,
						`approved_user`,
						`created_datetime`,
						`created_user`,
						`updated_datetime`,
						`updated_user`,
						`sort_order`
					)
					VALUES(
						NULL,
						'" . $_POST['absent_id'] . "',
						'" . $_POST['approval_id'] . "',
						CURRENT_TIMESTAMP,
						'" . $_SESSION['user_account_id'] . "',
						CURRENT_TIMESTAMP,
						'" . $_SESSION['user_account_id'] . "',
						'" . $_POST['sort_order'] . "'
					)
				";
		$_res_insert_approval = mysqli_query($_connection,$_sql_insert_approval);
		if($_res_insert_approval){
			$_sql_update_status = "
					update hr_staff_absent
					set
						request_status = 'รอการอนุมัติ',
						updated_datetime = CURRENT_TIMESTAMP,
						updated_user     = '" . $_SESSION['user_account_id'] . "' 
					where
						absent_id = '" . $_POST['absent_id'] . "' 
				";
			$_res_status = mysqli_query($_connection,$_sql_update_status);
		}
	}


?>




<?php


	$_absent_id = "";
	$_row_data  = 0;
	if(isset($_REQUEST['absent_id'])){
		$_absent_id = $_REQUEST['absent_id'];
	}
	if(isset($_POST['absent_id'])){
		$_absent_id = $_POST['absent_id'];
	}
?>



<?php
	$_sql_job = "
		select 
			h.prefix,
			h.firstname,
			h.lastname,
			d.department_name,
			s.department_subtype_name,
			m.acadyear,
			m.acadsemester
		from
			hr_staff h inner join hr_staff_department_mapping m 
			on (h.staff_id = m.staff_id) inner join department_subtypes s
			on (m.department_id = s.department_id and m.sub_department_id = s.department_subtype_id) inner join departments d 
			on (d.department_id = m.department_id) 
		where
			1=1
			and acadyear 	 = '" . $acadyear . "'
			and acadsemester = '" . $acadsemester . "' 
	";

	$_sql_approval = "
		SELECT 
			h.prefix,
			h.firstname,
			h.lastname,
			h.position,
			a.*
		FROM 
			`hr_staff_absent_approval` a left join hr_staff h
			on (a.approved_user = h.staff_id)
		WHERE
			absent_id = '" . $_absent_id . "'
		order by sort_order
	";
	$_res_approval = mysqli_query($_connection,$_sql_approval);
	$_approval_count = 0;

	$_approval_count = mysqli_num_rows($_res_approval);

?>

<?php
		$_sql_initial = "
				select 
				s.prefix,
				s.firstname,
				s.lastname,
				s.position,
				h.*
			from
				hr_staff_absent h inner join hr_staff s
				on (
					h.staff_id = s.staff_id
				) 
			where
				h.absent_id = '" . $_absent_id . "'
			";
		if($_absent_id != ""){
			$_res_initial = mysqli_query($_connection,$_sql_initial);
			$_row_data = mysqli_num_rows($_res_initial);
			$_dat = mysqli_fetch_assoc($_res_initial); 
		}
		
	?>



<div id="content">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
			<tr>
				<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48px" border="0" /></a></td>
				<td >
					<strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.4 บันทึกผู้อนุมัติคำขอลา/ไปราชการ</strong></font></span></td>
				<td width="330px">
					<?php

						$_sql_absent_list = "
								select 
									s.prefix,
									s.firstname,
									s.lastname,
									s.position,
									h.*
								from
									hr_staff_absent h inner join hr_staff s
									on (
										h.staff_id = s.staff_id
									) 
								where 
									h.request_status != 'ส่งคำขอแล้ว'
								order by
									convert(s.firstname using tis620)
								 ";
						$_res_list = mysqli_query($_connection,$_sql_absent_list);

					?>
					<font size="2" color="#000000">
						<form name="absent_list" method="post">
							เลือกคำขอ 
							<select name="absent_id" class="inputboxUpdate" onChange="document.absent_list.submit();">
								<option value=""></option>
								<? while ($_datL = mysqli_fetch_assoc($_res_list)) { ?>
									<option value="<?=$_datL['absent_id']?>" <?=$_absent_id==$_datL['absent_id']?"selected":""?>>
										<?=$_datL['absent_type'].' - '.$_datL['firstname'].' '.$_datL['lastname']?>
									</option>
								<? } ?>
							</select>
						</form>
					</font>
				</td>
			</tr>
		</table>

	

	
    <? if($_row_data>0){  ?>

	<div align="center">
		<form name="approval" method="post" >
			<table class="admintable">
				<tr>
					<td class="key" colspan="2" height="35px" align="center">ส่วนบันทึกเพิ่มผู้อนุมัติคำขอ</td>
				</tr>
				<tr>
					<td align="right" valign="top">เลือกผู้อนุมัติคนที่ <?=$_approval_count+1?> :</td>
					<td>
						<?php
							$_sql_staff = "
									select * 
									from   hr_staff
									where  
										staff_status = 'ACTIVE' and
										staff_id not in 
											(select approved_user from hr_staff_absent_approval where absent_id = '" . $_absent_id . "')
									order by 
										convert(firstname using tis620), convert(lastname using tis620)
								";
							$_res_staff = mysqli_query($_connection,$_sql_staff);

						?>
						<!-- onChange="document.approval.submit();" -->
						<select name="approval_id" class="inputboxUpdate">
							<option value=""></option>
							<? while($_datA = mysqli_fetch_assoc($_res_staff)) { ?>
								<option value="<?=$_datA['staff_id']?>" 
										<?=isset($_POST['staff_id'])&&$_POST['staff_id']==$_datA['staff_id']?"selected":""?> >
										<?=$_datA['firstname']. ' ' .$_datA['lastname']?>
								</option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="hidden" name="sort_order" value="<?=$_approval_count+1?>" />
						<input type="hidden" name="absent_id" value="<?=$_absent_id?>" />
						<input type="submit" name="add" value="เพิ่มผู้อนุมัติ" class="button" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
			</table>
		</form>
	</div>

	<div align="center">
			<table class="admintable">
				<tr>
					<td class="key" colspan="2" height="35px" align="center">
						รายละเอียดบันทึกขออนุญาต<?=$_dat['absent_type']?> 
						(สถานะคำร้อง: <font color='green'><?=$_dat['request_status']?></font>)
					</td>
				</tr>
				<tr>
					<td align="right" width="200px">ชื่อบุคลากร :</td>
					<td width="500px"><?=$_dat['prefix'].$_dat['firstname'].' '.$_dat['lastname']?></td>
				<tr>
					<td align="right">ตำแหน่ง :</td>
					<td><?=$_dat['position']?></td>
				</tr>
				<tr >
					<td valign="top" align="right">สังกัดฝ่าย และงานที่รับผิดชอบ : </td>
					<td valign="top">
						<?php
							$_sql_job .= " and h.staff_id = '" . $_dat['staff_id'] . "'";
							$_res_job = mysqli_query($_connection,$_sql_job);
							$_init_dep = "";
							if(mysqli_num_rows($_res_job)>0){
								echo "<table cellspacing='0'>";
								while($_job = mysqli_fetch_assoc($_res_job)){
									echo "<tr>";
									echo "<td>" . $_job['department_subtype_name'] ."/";
									//echo "<td>";
									//if($_init_dep != $_job['department_name']){
										echo $_job['department_name'];
									//}
									echo "</td>";
									echo "</tr>";
									$_init_dep = $_job['department_name'];
								}
								echo "</table>";
							}else{
								echo "ไม่ระบุ";
							}
						?>
					</td>
				</tr>
				<tr>
					<td align="right">การขออนุญาต<?=$_dat['absent_type']?> :</td>
					<td>
						<?=$_dat['absent_subtype']?>
					</td>
				</tr>
				<tr>
					<td align="right">วันที่เริ่ม<?=$_dat['absent_type']?> :</td>
					<td>
						วัน<?=displayDayofWeek(date('w',strtotime($_dat['start_absent_date'])))?> 
						ที่ <?=displayFullDate($_dat['start_absent_date'])?> 
						เวลา <?=$_dat['start_absent_time']?>
					</td>
				</tr>
				<tr>
					<td align="right">วันที่สิ้นสุด<?=$_dat['absent_type']?> :</td>
					<td>
					วัน<?=displayDayofWeek(date('w',strtotime($_dat['end_absent_date'])))?> 
						ที่ <?=displayFullDate($_dat['end_absent_date'])?> 
						เวลา <?=$_dat['end_absent_time']?>
					</td>
				</tr>
				<tr>
					<td align="right">รวมระยะการ<?=$_dat['absent_type']?> :</td>
					<td><b><?=$_dat['total_absent']?></b> วัน <font color="red">*</font></td>
				</tr>
				<tr>
					<td align="right" valign="top">รายละเอียดประกอบการลา :</td>
					<td><?=$_dat['absent_details']?>
				</tr>
				<tr>
					<td align="right">
						<?php 
							if($_dat['absent_type']=="ไปราชการ"){
								echo "บันทึกข้อความเลขที่:";
							}else{
								echo "ชื่อผู้ติดต่อระหว่างลา:";
							}
						?>
					</td>
					<td><?=$_dat['contact_information']?></td>
				</tr>
				<tr>
					<td class="key" height="28px" align="center" colspan="2">รายนามผู้อนุมัติ</td>
				</tr>
				<? if($_approval_count>0){ ?>
					<? $_a_count = 1; ?>
					<? while($_datA = mysqli_fetch_assoc($_res_approval)) { ?>
					<tr>
						<td align="right" >ผู้อนุมัติคนที่ <?=$_a_count++?> :</td>
						<td>
							<table cellspacing="0">
								<tr>
									<td width="180px"><?=$_datA['prefix'].$_datA['firstname']. ' ' . $_datA['lastname'];?></td>
									<td>
										<form method="post">
											<input type="hidden" name="absent_id"      value="<?=$_datA['absent_id']?>" />
											<input type="hidden" name="approval_user"  value="<?=$_datA['approval_user']?>" />
											<input type="hidden" name="approve_id"     value="<?=$_datA['approve_id']?>" />
											<input type="submit" name="delete"         value="ลบ" />
										</form>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<? } //end while ?>
				<? }else { ?>
				<tr>
					<td></td>
					<td>ยังไม่ระบุ <br/><br/></td>
				</tr>
				<? } ?>
				<tr>
					<td class="key" colspan="2"> </td>
				</tr>
				<tr>
					<td align="right">วันที่ส่งคำร้อง:</td>
					<td><?=$_dat['created_datetime']?></td>
				</tr>
				<tr>
					<td align="right">แก้ไขล่าสุด:</td>
					<td><?=$_dat['updated_datetime']?> โดย <?=getUserAccountName($_connection,$_dat['updated_user'])?></td>
				</tr>

				<?php
					$_img_1  = ".." . $_hr_img_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_img_1 .= $_dat['file_attached_ext1'];

					$_full_path_1  = $_target . $_hr_absent_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_full_path_1 .= $_dat['file_attached_ext1'];



					$_img_2  = ".." . $_hr_img_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_img_2 .= $_dat['file_attached_ext2'];

					$_full_path_2  = $_target . $_hr_absent_folder . "/" . $_dat['acadyear'] . "/" . $_dat['acadsemester'] . "/";
					$_full_path_2 .= $_dat['file_attached_ext2'];



				?>
				<? if($_dat['file_attached_ext1']!="" && file_exists($_full_path_1)){ ?> 
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 1</td>
					<td><input type="button" id="btn1" name="button1" value="แสดงรูปภาพ" onClick="show1()" /></td>
				</tr>
				<tr>
					<td align="center" valign="top" colspan="2">
						<img id="image1" src="<?=$_img_1?>" width="85%" />
					</td>
				</tr>
				<? }else{ ?>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 1</td>
					<td>ไม่มี</td>
				</tr>
				<? } ?>
				<? if($_dat['file_attached_ext2']!="" && file_exists($_full_path_2)){ ?> 
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 2</td>
					<td><input type="button" id="btn2" name="button2" value="แสดงรูปภาพ" onClick="show2()" /></td>
				</tr>
				<tr>
					<td align="center" valign="top" colspan="2">
						<img id="image2" src="<?=$_img_2?>" width="85%" />
					</td>
				</tr>
				<? }else{ ?>
				<tr>
					<td align="right" valign="top">ไฟล์แนบ 2</td>
					<td>ไม่มี</td>
				</tr>
				<? } ?>
			</table>
	</div>
	<? } //end if ?>
</div>


