
<?php

if(isset($_REQUEST['acadyear']))
{
	$acadyear = $_REQUEST['acadyear'];
}
if(isset($_REQUEST['acadsemester']))
{
	$acadsemester = $_REQUEST['acadsemester'];
}

?>

<?php
	$_processing_message = "";
	$_processing_result  = false;

	if(isset($_POST['save'])){

		$_dep = explode("|",$_POST['department_id']);

		$_sql_insert = "
				INSERT INTO `hr_staff_department_mapping`(
					`staff_id`,
					`department_id`,
					`sub_department_id`,
					`acadyear`,
					`acadsemester`,
					`created_datetime`,
					`created_user`,
					`updated_datetime`,
					`updated_user`
				)
				VALUES(
					'". $_POST['staff_id'] . "',
					'" . $_dep[0] ."',
					'" . $_dep[1] ."',
					'" . $acadyear . "',
					'" . $acadsemester . "',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "'
				)
				";
		$_resInsert = mysqli_query($_connection,$_sql_insert);
			if($_resInsert){


				$_sql_processing = "
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
						s.department_subtype_id = '" . $_dep[1] . "' 
						and h.staff_id          = '" . $_POST['staff_id'] . "' 
				";

				//echo $_sql_processing;

				$_res_processing = mysqli_query($_connection,$_sql_processing);
				$_dat_processing = mysqli_fetch_assoc($_res_processing);
				
				$_processing_message  = "ระบบได้ทำการบันทึกการมอบหมายภาระงานของ " . $_dat_processing['prefix'] . $_dat_processing['firstname'] . ' ' . $_dat_processing['lastname'] ;
				$_processing_message .= "<br/>เพื่อปฏิบัติหน้าที่";
				$_processing_message .= $_dat_processing['department_subtype_name'] . " " . $_dat_processing['department_name'] . " ";
				$_processing_message .= "ประจำภาคเรียนที่ " . $_dat_processing['acadsemester'] . '/' . $_dat_processing['acadyear'] . " เรียบร้อยแล้ว";

				$_text   = "";
				$_text  .= "ระบบได้ทำการบันทึกการมอบหมายภาระงานของ" . $_dat_processing['prefix'] . $_dat_processing['firstname'] . ' ' . $_dat_processing['lastname'] ;
				$_text  .= " เพื่อปฏิบัติหน้าที่";
				$_text  .= $_dat_processing['department_subtype_name'] . " " . $_dat_processing['department_name'] . " ";
				$_text  .= "ประจำภาคเรียนที่ " . $_dat_processing['acadsemester'] . '/' . $_dat_processing['acadyear'] . " เรียบร้อยแล้ว";

				$_text .=  "\nโดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token_module_hr);
				SendLineMessage($message,$_line_token);
				$_processing_result = true;

				
				$_event_details = "";
				$_event_details .= str_replace("<br/>","",$_processing_message);

				$_event_key = hash("sha256",$_event_details.date("H:i:s"));

				$_event_user_id = $_SESSION['user_account_id'];
				if(checkDuplicateEventLog($_connection,$_event_key)){
					event_log($_connection,1,2,4,
					$_event_key,
					$_event_details,
					$_event_user_id,$acadyear,$acadsemester);
				}

			}else{
				$_processing_message  = "เกิดข้อผิดพลาด อาจจะมีสาเหตุจากการพยายามบันทึกซ้ำ <br/>";
				$_processing_message .= "หรือใช้ข้อความต่อไปนี้แจ้งผู้ดูแลระบบ : " . mysqli_error($_connection);
			}
	} // end save data process
?>


<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr> 
		<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" border="0" /></a></td>
		<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>3.6 จัดบุคลากรลงงาน</strong></font></span></td>
		<td align="right">

			ปีการศึกษา <?php  
						echo "<a href=\"index.php?option=module_hr/StaffAndDepartmentMapping&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
						echo ' <font color=\'blue\'>' .$acadyear . '</font>';
						echo " <a href=\"index.php?option=module_hr/StaffAndDepartmentMapping&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
					?>
			ภาคเรียนที่   <?php 
						if($acadsemester == 1)
						{
							echo "<font color='blue'>1</font> , ";
						}
						else
						{
							echo " <a href=\"index.php?option=module_hr/StaffAndDepartmentMapping&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
						}
						if($acadsemester == 2)
						{
							echo "<font color='blue'>2</font>";
						}
						else
						{
							echo " <a href=\"index.php?option=module_hr/StaffAndDepartmentMapping&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
						}
					?>
		</td>
		</tr>
	</table>
	
	<div align="center">
		<table class="admintable" align="center" width="550px">
			<tr>
				<td>เลือกบุคลากร </td>
				<td>
					<?php
						$_sql_staff = "
								select * 
								from   hr_staff
								where  staff_status = 'ACTIVE'
								order by 
									   convert(firstname using tis620), convert(lastname using tis620)
							";
						$_res_staff = mysqli_query($_connection,$_sql_staff);

					?>
					<form name="staff" method="post" action="" onChange="document.staff.submit();">
						<select name="staff_id" class="inputboxUpdate">
							<option value=""></option>
							<? while($_dat = mysqli_fetch_assoc($_res_staff)) { ?>
								<option value="<?=$_dat['staff_id']?>" 
										<?=isset($_POST['staff_id'])&&$_POST['staff_id']==$_dat['staff_id']?"selected":""?> >
										<?=$_dat['firstname']. ' ' .$_dat['lastname']?>
								</option>
							<? } ?>
						</select>
					</form>
				</td>
			</tr>
			<? if(isset($_POST['staff_id']) && $_POST['staff_id'] != "") { ?>  
			<tr>
				<td>เลือกฝ่าย/งาน </td>
				<td>
					<?php
						$_sql_dep = "
							select 
									s.department_name,
									d.*
							from
								department_subtypes d left join departments s
								on (d.department_id = s.department_id) 
							where
								1=1
								and d.department_subtype_id not in
									(
										select sub_department_id from hr_staff_department_mapping h
										where
											1=1
											and h.acadyear		= '" . $acadyear . "'
											and h.acadsemester	= '" . $acadsemester ."'
											and h.staff_id		= '" . $_POST['staff_id'] ."'
									)
							order by
								convert(department_name using tis620),
								convert(department_subtype_name using tis620)
				
							";
						$_res_dep = mysqli_query($_connection,$_sql_dep);

					?>
					<form name="department" method="post" action="" onChange="document.department.submit();">
						<?php
							$_dep_info = "";
						?>
						<select name="department_id" class="inputboxUpdate">
							<option value=""></option>
							<? while($_dat = mysqli_fetch_assoc($_res_dep)) { ?>
								<? $_id = $_dat['department_id'].'|'.$_dat['department_subtype_id']; ?>
								<option value="<?=$_id?>"
										<?=isset($_POST['department_id'])&&$_POST['department_id']==$_id?"selected":""?> >
										<?=$_dat['department_name'] . ' - ' . $_dat['department_subtype_name']?>
								</option>
							<? } ?>
						</select>
						<input type="hidden" name="staff_id" value="<?=$_POST['staff_id']?>" />
					</form>
				</td>
			</tr>
			<? } ?>

			<? if(isset($_POST['save'])){ ?>
			<tr>
				<td colspan="2" align="center">
					<? 
						echo "<br/>";
							if($_processing_result){
								echo "<font color='green'>";
								echo $_processing_message;
								echo "</font>";
							}else{
								echo "<font color='red'>";
								echo $_processing_message;
								echo "</font>";
							}
					?>
				</td>
			</tr>
			<? } ?>

			<? if(isset($_POST['department_id']) && $_POST['department_id'] != "" && !isset($_POST['save'])) { ?>  
			<tr>
				<td align="center" colspan="2">
					<br/><br/>
					หากต้องการเพิ่มงานที่คุณเลือกให้บุคลากรที่เลือก กรุณาคลิกที่ปุ่ม "บันทึก" เพื่อยืนยัน
					<form name="confirm" method="post" action="">
						<input type="hidden" name="staff_id" value="<?=$_POST['staff_id']?>" />
						<input type="hidden" name="department_id" value="<?=$_POST['department_id']?>" />
						<br/>
						<input type="submit" value="บันทึก" class="button" name="save" /> 
						<input type="button" value="ยกเลิก" class="button" onClick="location.href='index.php?option=module_hr/StaffAndDepartmentMapping'"/>
					</form>
				</td>
			</tr>
			<? } ?>
		</table>
	</div>

	<div align="center">
		<table class="admintable">
			<tr>
				
			</tr>
		</table>
	</div>

</div>

