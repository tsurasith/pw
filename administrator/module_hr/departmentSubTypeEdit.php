

<SCRIPT language="javascript" type="text/javascript">
	
	  function deleteClick(){
		document.getElementById('action').value = "delete";
		console.log(document.getElementById('action').value);
		checkValue();
	  }

	  function checkValue(){
		
		if(document.getElementById('department_subtype_name').value == "")
			{ alert('กรุณาป้อนข้อมูล ชื่อส่วนงาน ก่อน'); document.getElementById('department_subtype_name').focus(); return; }
		
		
		if(document.getElementById('department_id').value == "")
		{ 
			alert('กรุณาเลือกฝ่ายงานก่อน'); 
			document.getElementById('department_id').focus(); return;  
		}
		else 
		{ 
			document.myform.submit(); 
		}
	  }
</SCRIPT>
 
 <?php
	$_processing_message = "";
	$_processing_result  = false;

	$_staff_list = "";


	if(isset($_POST['action']) && $_POST['action'] == "delete"){
		$_sql_validate = "
				select
					h.prefix,
					h.firstname,
					h.lastname,
					h.staff_status,
					m.acadyear,
					m.acadsemester
				from
					hr_staff_department_mapping m left join hr_staff h
					on (m.staff_id = h.staff_id)
				where
					m.sub_department_id = '" . trim($_POST['department_subtype_id']) . "' 
				order by acadyear,acadsemester

			";
		$_total_staff = 0;
		$_res_validate = mysqli_query($_connection,$_sql_validate);
		$_total_staff = mysqli_num_rows($_res_validate);

		if($_total_staff < 1){
			$_sql_delete = "delete from `department_subtypes` WHERE `department_subtype_id` = '" . $_POST['department_subtype_id'] . "' ";
			$_res_delete = mysqli_query($_connection,$_sql_delete);
			if($_res_delete){
				$_processing_message = "ระบบได้ทำการลบส่วนงานเรียบร้อยแล้ว";
				$_processing_result = true;
			}
		}else{
			$_processing_message = "<br/>ไม่สามารถลบส่วนงานนี้ได้เนื่องจากมีการใช้งานในฐานข้อมูล โดยมีบุคลากรปฏิบัตหน้าที่ในตำแหน่งดังนี้ <br/>";
			while($_dat = mysqli_fetch_assoc($_res_validate)){
				$_staff_list .= "ภาคเรียนที่ ";
				$_staff_list .= $_dat['acadsemester'].'/'.$_dat['acadyear']. ' ';
				$_staff_list .= $_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname'];
				// $_staff_list .= " " . $_dat['staff_status'];
				$_staff_list .= "<br/>";
			}
			$_processing_result = false;
		}
	}


	if(isset($_POST['addnew']) && $_POST['action'] == ""){
		$_sql_insert = "
				UPDATE `department_subtypes`
				SET
					`department_subtype_name` 	= '" . $_POST['department_subtype_name'] . "' ,
					`department_id`				= '" . $_POST['department_id'] . "' ,
					`updated_datetime` 			= CURRENT_TIMESTAMP,
					`updated_user` 				= '" .$_SESSION['user_account_id'] . "' 
				WHERE
					`department_subtype_id` = '" . $_POST['department_subtype_id'] . "' 
				";
		$_resInsert = mysqli_query($_connection,$_sql_insert);
			if($_resInsert){
				
				$_processing_message = "ระบบได้ทำการแก้ไขส่วนงาน: " . trim($_POST['department_subtype_name']) . " เรียบร้อยแล้ว <br/>";

				$_text  = "";
				$_text .= "ระบบได้ทำการแก้ไขส่วนงาน " . trim($_POST['department_subtype_name']) . "";

				$_processing_message = $_text;

				$_text .=  "\nโดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token);
				$_processing_result = true;

				/*
				$_event_details = "";
				$_event_details .= "มีการบันทึกการจัดสอนแทน วันที่ " . displayFullDate($_POST['date']);

				$_event_key = hash("sha256",$_event_details.date("H:i:s"));

				$_event_user_id = $_SESSION['user_account_id'];
				if(checkDuplicateEventLog($_connection,$_event_key)){
					event_log($_connection,1,2,4,
					$_event_key,
					$_event_details,
					$_event_user_id,$acadyear,$acadsemester);
				}
				*/

			}else{
				$_processing_message  = "ไม่สามารถเพิ่มส่วนงานได้ เนื่องจากเกิดการบันทึกซ้ำ <br/>";
				$_processing_message .= "หรือใช้ข้อความต่อไปนี้แจ้งผู้ดูแลระบบ : " . mysqli_error($_connection);
			}
	} // end save data process

?>


<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>3.5 เพิ่ม/แก้ไข ภาระงานสังกัดฝ่าย (แก้ไข)</strong></font></span></td>
			<td >
					<input	type="button" name="yyyy" class="button" 
							value="ย้อนกลับ" onclick="location.href='index.php?option=module_hr/departmentSubTypeAdd'" /> 
			</td>
			</tr>
		</table>
	  
		<? if(!(isset($_POST['action']) && $_POST['action'] == "delete")){ ?>

					<?php
						$_row = 0;
						$_sql = "select * from department_subtypes where department_subtype_id = '" . trim($_REQUEST['department_subtype_id']) . "'";
						$_res = mysqli_query($_connection,$_sql);
						$_row = mysqli_num_rows($_res);
					?>
					<? if($_row>0){ ?> 
					<form action="" name="myform" method="post" autocomplete="off">
						<? $_dat = mysqli_fetch_assoc($_res); ?>
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="2"> &nbsp; ส่วนเพิ่ม/แก้ไขภาระงาน</td>
							</tr>
							<tr>
								<td align="right" width="100px"> เลือกฝ่าย </td>
								<td>
									<?php
										$_sql = "select * from departments order by 1";
										$_resSemester = mysqli_query($_connection,$_sql);
									?> 
									<select name="department_id" id="department_id" class="inputboxUpdate">
										<option value="" > ทั้งหมด </option>
										<? while($_datf = mysqli_fetch_assoc($_resSemester)) { ?> 
											<option value="<?=$_datf['department_id']?>" <?=$_dat['department_id']==$_datf['department_id']?"selected":""?>  >
												<?=$_datf['department_name']?>
											</option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td  align="right"> ชื่อส่วนงาน </td>
								<td> 
									<input type="text" name="department_subtype_name" id="department_subtype_name" 
											value="<?=$_dat['department_subtype_name']?>"
											size="40" class="inputboxUpdate" maxlength="150" /> 
									<input type="hidden" name="department_subtype_id" value="<?=$_dat['department_subtype_id']?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><br/>
									<?php
										if(isset($_POST['addnew'])){
											if($_processing_result){
												echo "<font color='green'>";
												echo $_processing_message;
												echo "</font>";
											}else{
												echo "<font color='red'>";
												echo $_processing_message;
												echo "</font>";
											}
										}
									?>
									<br/><br/>
									<input type="hidden" name="addnew" value="add"/>
									<input type="hidden" id="action" name="action" value="" />
									<input type="button" name="xxxx" class="button" value="แก้ไข" onclick="checkValue();" /> 
									<input type="button" id='yyyyy' name="yyyy" class="button" value="ลบ" onclick="deleteClick();" /> 
								</td>
							</tr>
						</table>
					</form>
					<? }else{ echo "ไม่เจอข้อมูล"; } ?>
		<? }else{
				if($_processing_result){
					header("Location: index.php?option=module_hr/departmentSubTypeAdd");
				}else{ ?>
				<table align="center">
					<tr>
						<td colspan="2"><?=$_processing_message?></td>
					</tr>
					<tr>
						<td width="100px"></td>
						<td><?=$_staff_list?></td>
					</tr>
				</table>
		<? }} ?>
	</div>
</div>

