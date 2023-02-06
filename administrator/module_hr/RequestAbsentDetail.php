
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


	$_absent_id = "";
	if(isset($_REQUEST['absent_id'])){
		$_absent_id = $_REQUEST['absent_id'];
	}

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
		$_res_initial = mysqli_query($_connection,$_sql_initial);
		$_row_data = mysqli_num_rows($_res_initial);
		$_dat = mysqli_fetch_assoc($_res_initial); 
	?>



<div id="content">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
			<tr>
				<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48px" border="0" /></a></td>
				<td >
					<strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>รายละเอียดการยื่นคำขออนุญาต</strong></font></span></td>
				<td width="360px">
					<table>
						<tr>
							<td>
								<form method="post" action="index.php?option=module_hr/EditAbsent">
									<input type="submit" class="button" value="แก้ไข" />
									<input type="hidden" name="absent_id" value="<?=$_absent_id?>" />
								</form>
							</td>
							<? if($_dat['request_status']=="ส่งคำขอแล้ว") { ?>
							<td>
								<form method="post" action="index.php?option=module_hr/HrArrpove">
									<input type="submit" class="button" value="ส่งต่อ" />
									<input type="hidden" name="absent_id" value="<?=$_absent_id?>" />
								</form>
							</td>
							<? } ?>
							<? if($_dat['request_status']=="ส่งต่องานบุคลากรแล้ว") { ?>
							<td>
								<form method="post" action="index.php?option=module_hr/AddApproval">
									<input type="submit" class="button" value="เพิ่มผู้อนุมัติ" />
									<input type="hidden" name="absent_id" value="<?=$_absent_id?>" />
								</form>
							</td>
							<? } ?>
						</tr>
					</table>
				</td>
			</tr>
		</table>

	

	
    <? if($_row_data>0){  ?>

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
					<tr>
						<td valign="top" colspan="2" align="center">
							<table class="admintable" style="margin-top:0px">
								<tr>
									
									<td class="key" align="center" width="180px">ความเห็น</td>
									<td class="key" align="center" width="75px">สถานะ</td>
									<td class="key" align="center" width="180px">ชื่อ-สกุล</td>
									<td class="key" align="center" width="100px">วันที่อนุมัติ</td>
									<td class="key" align="center" width="100px">วันที่ขออนุมัติ</td>
								</tr>
								<? while($_datA = mysqli_fetch_assoc($_res_approval)) { ?>
									<tr>
										
										<td valign="top"><?=$_datA['approval_comment']?></td>
										<td valign="top" align="center"><?=$_datA['approved_status']?></td>
										<td valign="top"><?=$_datA['prefix'].$_datA['firstname']. ' ' . $_datA['lastname'];?></td>
										<td valign="top">
											<?=$_datA['approved_datetime']==""?"":(substr($_datA['approved_datetime'],0,strlen($_datA['approved_datetime'])-3))?>
										</td>
										<td valign="top">
											<?=$_datA['created_datetime']==""?"":(substr($_datA['created_datetime'],0,strlen($_datA['created_datetime'])-3))?>
										</td>
									</tr>
								<? } //end while ?>
							</table>
						</td>
					</tr>
				<? }else { ?>
				<tr>
					<td colspan="2" align="center">
						<font color='red'>
							<?php
								if($_dat['request_status']=="ส่งคำขอแล้ว"){
									echo "ยังไม่ระบุ หากงานบุคลากรตรวจสอบความถูกต้องของคำขอแล้ว โปรดดำเนินการ \"ส่งต่อ\" เพื่อเพิ่มผู้อนุมัติคำขอ";
								}
								if($_dat['request_status']=="ส่งต่องานบุคลากรแล้ว"){
									echo "ยังไม่ระบุ กรุณาเพิ่มผู้อนุมัตคำร้องขอ";
								}
								echo "<br/><br/>";
							?>
						</font>
					</td>
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


