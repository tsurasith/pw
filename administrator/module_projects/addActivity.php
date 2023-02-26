<SCRIPT language="Javascript" type="text/javascript">
      function isNumberKey2(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode;
         if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57 ))
            return false;

         return true;
      }
	  function checkFormValue()
	  {
	  	if(document.getElementById('activity_number').value == '' )
		{ 
			alert('กรุณาป้อนข้อมูล รหัสกิจกรรมให้ถูกต้อง'); 
			document.getElementById('activity_number').focus(); 
			return;
		}

		if(document.getElementById('activity_name').value == '' || document.getElementById('activity_name').value.length < 6)
		{ 
			alert('กรุณาป้อนข้อมูล ชื่อกิจกรรมก่อน'); 
			document.getElementById('activity_name').focus(); 
			return;
		}

		if(document.getElementById('activity_details').value == '' || document.getElementById('activity_details').value.length < 6)
		{ 
			alert('กรุณาป้อนข้อมูล วัตถุประสงค์ก่อน'); 
			document.getElementById('activity_details').focus(); 
			return;
		}
		
		if(document.getElementById('start_date').value == '' || document.getElementById('start_date').value.length < 10)
		{ 
			alert('กรุณาป้อนข้อมูล วันที่เริ่มกิจกรรมก่อน'); 
			document.getElementById('start_date').focus(); 
			return;
		}
		
		if(document.getElementById('finish_date').value == '' || document.getElementById('finish_date').value.length < 10)
		{ 
			alert('กรุณาป้อนข้อมูล วันที่สิ้นสุดกิจกรรมก่อน'); 
			document.getElementById('finish_date').focus(); 
			return;
		}

		const start_date   = new Date(document.getElementById('start_date').value);
		const finish_date  = new Date(document.getElementById('finish_date').value);

		if(start_date > finish_date)
		{
			alert('คุณป้อนข้อมูลไม่ถูกต้อง วันที่เริ่มกิจกรรม มากกว่า วันสิ้นสุดกิจกรรม กรุณาตรวจสอบอีกครั้ง'); 
			document.getElementById('start_date').focus(); return;
		}


		if(document.getElementById('income_id').value == '')
		{ 
			alert('กรุณาเลือก ประเภทเงินงบประมาณก่อน'); 
			document.getElementById('income_id').focus(); 
			return;
		}

		if(document.getElementById('request_budget').value == '' || document.getElementById('request_budget').value*0 != 0)
		{ 
			alert('กรุณาป้อนข้อมูล เงินงบประมาณที่ขอก่อน'); 
			document.getElementById('request_budget').focus(); 
			return;
		}

		if(document.getElementById('acadsemester').value == '')
		{ 
			alert('กรุณาป้อนข้อมูล ภาคเรียน'); 
			document.getElementById('acadsemester').focus(); 
			return;
		}

		if(document.getElementById('acadyear').value == '')
		{ 
			alert('กรุณาป้อนข้อมูล ภาคเรียน'); 
			document.getElementById('acadyear').focus(); 
			return;
		}
		
		if(document.getElementById('approve_budget').value == '' || document.getElementById('approve_budget').value*0 != 0)
		{ 
			alert('กรุณาป้อนข้อมูล เงินงบประมาณที่อนุมัติก่อน'); 
			document.getElementById('approve_budget').focus(); 
			return;
		}

		if(document.getElementById('department_id').value == '')
		{ 
			alert('กรุณาเลือก ฝ่ายที่รับผิดชอบกิจกรรมก่อน'); 
			document.getElementById('department_id').focus(); 
			return;
		}

		if(document.getElementById('activity_owner').value == '')
		{ 
			alert('กรุณาเลือก ผู้รับผิดชอบกิจกรรมก่อน'); 
			document.getElementById('activity_owner').focus(); 
			return;
		} 
		else
		{ 
			document.myform.submit();
		}
	  }

</SCRIPT>

<link rel="stylesheet" type="text/css" href="module_projects/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_projects/js/calendar.js"></script>


<?php

	$_activity_id = "";
	if(isset($_POST['action'])){
		$_activity_id = $_POST['activity_id'];
	}else{
		$_activity_id = gen_uuid();
	}

	if(isset($_GET['project_number_search']) && isset($_GET['activity_id'])){
		$_activity_id = $_GET['activity_id'];
	}
?>


<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	// insert data
	if(isset($_POST['action']) && $_POST['action'] == "save"){

		$_sql_insert = "
			INSERT INTO `project_activity`(
				`activity_id`,
				`project_id`,
				`activity_number`,
				`activity_name`,
				`acadyear`,
				`acadsemester`,
				`activity_details`,
				`start_date`,
				`finish_date`,
				`income_id`,
				`department_id`,
				`request_budget`,
				`approve_budget`,
				`used_budget`,
				`activity_owner`,
				`activity_status`,
				`created_datetime`,
				`created_user`,
				`updated_datetime`,
				`updated_user`
			)
			VALUES(
				'" . $_POST['activity_id'] . "',
				'" . $_POST['project_id'] . "',
				'" . $_POST['activity_number'] . "',
				'" . trim($_POST['activity_name']) . "',
				'" . $_POST['acadyear'] . "',
				'" . $_POST['acadsemester'] . "',
				'" . trim($_POST['activity_details']) . "',
				'" . $_POST['start_date'] . "',
				'" . $_POST['finish_date'] . "',
				'" . $_POST['income_id'] . "',
				'" . $_POST['department_id'] . "',
				'" . $_POST['request_budget'] . "',
				'" . $_POST['approve_budget'] . "',
				'" . $_POST['used_budget'] . "',
				'" . $_POST['activity_owner'] . "',
				'" . $_POST['activity_status'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "'
			)
		";

		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res){
			$_processing_text = "บันทึกข้อมูลกิจกรรม " . trim($_POST['activity_name']) . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "กิจกรรม: ";
			$_text .= trim($_POST['activity_name']) . " ถูกเพิ่มเข้าในระบบแล้ว";
			$_text .= "" . "โดย - " . $_SESSION['shortname'];

			$message = $_text;
			SendLineMessage($message,$_line_token);

			$_event_details = "";
			$_event_details .= $_text;

			$_event_key = hash("sha256",$_event_details);

			$_event_user_id = $_SESSION['user_account_id'];
			if(checkDuplicateEventLog($_connection,$_event_key)){
				event_log($_connection,1,1,2,
				$_event_key,
				$_event_details,
				$_event_user_id,$acadyear,$acadsemester);
			}

		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกกิจกรรมได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}
	}

	// - update data
	if(isset($_POST['action']) && $_POST['action'] == "update"){ 

		$_sql_update = "
			UPDATE `project_activity`
			SET
				`activity_number`  = '" . $_POST['activity_number'] . "',
				`activity_name`    = '" . trim($_POST['activity_name']) . "',
				`acadyear`         = '" . $_POST['acadyear'] . "',
				`acadsemester`     = '" . $_POST['acadsemester'] . "',
				`activity_details` = '" . trim($_POST['activity_details']) . "',
				`start_date`       = '" . $_POST['start_date'] . "',
				`finish_date`      = '" . $_POST['finish_date'] . "',
				`income_id`        = '" . $_POST['income_id'] . "',
				`department_id`    = '" . $_POST['department_id'] . "',
				`request_budget`   = '" . $_POST['request_budget'] . "',
				`approve_budget`   = '" . $_POST['approve_budget'] . "',
				`used_budget`      = '" . $_POST['used_budget'] . "',
				`activity_owner`   = '" . $_POST['activity_owner'] . "',
				`activity_status`  = '" . $_POST['activity_status'] . "',
				`updated_datetime` =  CURRENT_TIMESTAMP,
				`updated_user`     = '" . $_SESSION['user_account_id'] . "'
			WHERE
				`activity_id` =   '" . $_POST['activity_id'] . "'
		";

		$_res_update = mysqli_query($_connection,$_sql_update);
		if($_res_update){
			$_processing_text = "บันทึกแก้ไขข้อมูลกิจกรรม " . trim($_POST['activity_name']) . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "กิจกรรม: ";
			$_text .= trim($_POST['activity_name']) . " ถูกแก้ไขข้อมูล ";
			$_text .= "" . "โดย - " . $_SESSION['shortname'];

			$message = $_text;
			SendLineMessage($message,$_line_token);

			$_event_details = "";
			$_event_details .= $_text;

			$_event_key = hash("sha256",$_event_details);

			$_event_user_id = $_SESSION['user_account_id'];
			if(checkDuplicateEventLog($_connection,$_event_key)){
				event_log($_connection,1,1,2,
				$_event_key,
				$_event_details,
				$_event_user_id,$acadyear,$acadsemester);
			}

		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกกิจกรรมได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}
		
	}


	// search & project number textbox
	$_project_number = "";

	if(isset($_GET['project_number_search']) && trim($_GET['project_number_search'] != "")){
		$_project_number = $_GET['project_number_search'];
	}


	// project info
	$_sql_project = "
			select 
				p.*,
				d.department_name,
				concat(t.firstname,' ',t.lastname) as 'owner_name',
				s.status_name
			from 
				projects p left join departments d 
				on (p.department_id  = d.department_id) left join teachers t 
				on (p.project_owner  = t.teacher_id) left join project_status s
				on (p.project_status = s.status_id)
			where p.project_number = '" . $_project_number . "'";
	$_res_project = mysqli_query($_connection,$_sql_project);

	$_check_existing_activity = 0;
	$_check_existing = mysqli_num_rows($_res_project);

	if($_check_existing>0){
		$_project = mysqli_fetch_assoc($_res_project);
	}


	// retreive a new project activity
	$_sql_new_act = "select * from project_activity where activity_id = '" . $_activity_id . "'";
	$_res_new_act = mysqli_query($_connection,$_sql_new_act);

	$_check_new_act = mysqli_num_rows($_res_new_act);

	if($_check_new_act>0){
		$_new_act = mysqli_fetch_assoc($_res_new_act);
	}

?>

<div id="content">
	
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_projects/index">
						<img src="../images/computer.png" alt="" width="48" height="48" border="0"/>
					</a>
				</td>
				<td >
					<strong><font color="#990000" size="4">ระบบสารสนเทศแผน งบประมาณ โครงการและกิจกรรม</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.3 บันทึกข้อมูลกิจกรรมย่อยของโครงการ</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<?php
						$_value = "";
						if(isset($_GET['project_number_search'])){
							$_value = $_GET['project_number_search'];
						}
					?>

					<form method="get" autocomplete="off" action="">
						<input type="hidden" name="option" value="module_projects/addActivity" />
						<font size="2" color="#000000">รหัสโครงการ</font>
						<input  type="text" name="project_number_search" id="project_number_search" 
								value="<?=$_value?>" 
								size="15" maxlength="20" class="inputboxUpdate" />

						<input  type="submit" name="search" value="เรียกดู" class="button" />
					</form>
				</td>
			</tr>
		</table>

		<? if($_check_existing >0){ ?>
			<div align="center">
				<table class="admintable">
					<tr>
						<td class="key" align="center" rowspan="2" width="100px">รหัสโครงการ</td>
						<td class="key" align="center" rowspan="2" width="300px">ชื่อโครงการ</td>
						<td class="key" align="center" colspan="3">งบประมาณ (บาท)</td>
						<td class="key" align="center" rowspan="2" width="120px"> ฝ่าย</td>
						<td class="key" align="center" rowspan="2" width="120px">ผู้รับผิดชอบ</td>
						<td class="key" align="center" rowspan="2" width="150px">สถานะโครงการ</td>
					</tr>
						<td class="key" align="center" width="80px">ขอ</td>
						<td class="key" align="center" width="80px">อนุมัติ</td>
						<td class="key" align="center" width="80px">คงเหลือ</td>
					</tr>
					<tr>
						<td valign="top" align="center"><?=$_project['project_number']?></td>
						<td valign="top" align="left"><?=$_project['project_name']?></td>
						<td valign="top" align="right"><?=number_format($_project['request_budget'],2,'.',',')?></td>
						<td valign="top" align="right"><?=number_format($_project['approve_budget'],2,'.',',')?></td>
						<td valign="top" align="right"><?=number_format($_project['approve_budget']-$_project['used_budget'],2,'.',',')?></td>
						<td valign="top" align="center"><?=$_project['department_name']?></td>
						<td valign="top" align="center"><?=$_project['owner_name']?></td>
						<td valign="top" align="center"><?=$_project['status_name']?></td>
					</tr>
				</table>

				<?php
					$_sql_activity = "
							select 
								p.*,
								d.department_name,
								concat(t.firstname,' ',t.lastname) as 'owner_name',
								s.status_name
							from 
								project_activity p left join departments d 
								on (p.department_id  = d.department_id) left join teachers t 
								on (p.activity_owner  = t.teacher_id) left join project_status s
								on (p.activity_status = s.status_id)
							where p.project_id = '" . $_project['project_id'] . "'
							order by
								p.sort_order,
								p.activity_number
							";
					$_res_activity = mysqli_query($_connection,$_sql_activity);

					$_check_existing_activity = mysqli_num_rows($_res_activity);
				?>
				
				<? if($_check_existing_activity>0){ ?> 
					<table class="admintable">
						<tr>
							<td class="key" align="center" width="100px">รหัสกิจกรรม</td>
							<td class="key" align="center" width="300px">ชื่อกิจกรรม</td>
							<td class="key" align="center" width="80px">ขอ</td>
							<td class="key" align="center" width="80px">อนุมัติ</td>
							<td class="key" align="center" width="80px">คงเหลือ</td>
							<td class="key" align="center" width="120px"> ฝ่าย</td>
							<td class="key" align="center" width="120px">ผู้รับผิดชอบ</td>
							<td class="key" align="center" width="150px">สถานะกิจกรรม</td>
						</tr>
							
						</tr>
						<? while($_existing_act = mysqli_fetch_assoc($_res_activity)) { ?>
							<tr>
								<td valign="top" align="center">
									<a href="index.php?option=module_projects/addActivity&project_number_search=<?=$_project_number?>&activity_id=<?=$_existing_act['activity_id']?>">
									<?=$_existing_act['activity_number']?>
									</a>
								</td>
								<td valign="top" align="left"><?=$_existing_act['activity_name']?></td>
								<td valign="top" align="right"><?=number_format($_existing_act['request_budget'],2,'.',',')?></td>
								<td valign="top" align="right"><?=number_format($_existing_act['approve_budget'],2,'.',',')?></td>
								<td valign="top" align="right"><?=number_format($_existing_act['approve_budget']-$_existing_act['used_budget'],2,'.',',')?></td>
								<td valign="top" align="left"><?=$_existing_act['department_name']?></td>
								<td valign="top" align="left"><?=$_existing_act['owner_name']?></td>
								<td valign="top" align="left"><?=$_existing_act['status_name']?></td>
							</tr>
						<? } ?>
					</table>
				<? } ?> 
			</div>
		<? } ?>

		<div align="center">
			<table class="admintable">
				<tr height="55px">
					<td colspan="2" align="center">
						<?php
								if($_processing_result){
									echo "<font color='green'>";
									echo $_processing_text;
									echo "</font>";
								}else{
									echo "<font color='red'>";
									echo $_processing_text;
									echo "</font>";
								}
						?>
					</td>
				</tr>
			</table>
		</div>

	<form method="post" autocomplete="off" name="myform">
		<div align="center">
			<table class="admintable">
				<tr height="35px">
					<td class="key" colspan="2" align="center">บันทึกข้อมูลกิจกรรม</td>
				</tr>
				<tr>
					<td align="right" width="200px" valign="top" >รหัสกิจกรรม :</td>
					<td>
						<?php

							$_input_activity_number  = "";
							$_input_acadyear         = "";
							$_input_acadsemester     = "";
							$_input_activity_name    = "";
							$_input_activity_details = "";
							$_input_income_id        = "";
							$_input_start_date       = "";
							$_input_finish_date      = "";
							$_input_request_budget   = "";
							$_input_approve_budget   = "";
							$_input_used_budget      = "0";
							$_input_department_id    = "";
							$_input_activity_owner   = "";
							$_input_activity_status  = "";
							

							if((isset($_POST['action']) && $_processing_result) || $_check_new_act){

								$_input_activity_number  = $_new_act['activity_number'];
								$_input_acadyear         = $_new_act['acadyear'];
								$_input_acadsemester     = $_new_act['acadsemester'];
								$_input_activity_name    = $_new_act['activity_name'];
								$_input_activity_details = $_new_act['activity_details'];
								$_input_start_date       = $_new_act['start_date'];
								$_input_income_id        = $_new_act['income_id'];
								$_input_finish_date      = $_new_act['finish_date'];
								$_input_request_budget   = $_new_act['request_budget'];
								$_input_approve_budget   = $_new_act['approve_budget'];
								$_input_used_budget      = $_new_act['used_budget'];
								$_input_department_id    = $_new_act['department_id'];
								$_input_activity_owner   = $_new_act['activity_owner'];
								$_input_activity_status  = $_new_act['activity_status'];
								
							}
							if((isset($_POST['action']) && !$_processing_result)){ 
								$_input_activity_number  = $_POST['activity_number'];
								$_input_acadsemester     = $_POST['acadsemester'];
								$_input_acadyear         = $_POST['acadyear'];
								$_input_activity_name    = $_POST['activity_name'];
								$_input_activity_details = $_POST['activity_details'];
								$_input_start_date       = $_POST['start_date'];
								$_input_income_id        = $_POST['income_id'];
								$_input_finish_date      = $_POST['finish_date'];
								$_input_request_budget   = $_POST['request_budget'];
								$_input_approve_budget   = $_POST['approve_budget'];
								$_input_used_budget      = $_POST['used_budget'];
								$_input_department_id    = $_POST['department_id'];
								$_input_activity_owner   = $_POST['activity_owner'];
								$_input_activity_status  = $_POST['activity_status'];

							}
						?>
						<input  type="text" id="activity_number" name="activity_number" class="noborder2" 
								size="20" maxlength="20" value="<?=$_input_activity_number?>" />
						<font color="#FF0000">*</font>
					</td>
				</tr>
				<tr>
					<td align="right" width="200px" valign="top" >ภาคเรียน / ปีการศึกษา :</td>
					<td>
						<input  type="text" id="acadsemester" name="acadsemester" class="noborder2" 
								size="1" maxlength="1" onkeypress="return isNumberKey(event)"
								value="<?=$_input_acadsemester?>" /> / 

						<input  type="text" id="acadyear" name="acadyear" class="noborder2" 
								size="4" maxlength="4" onkeypress="return isNumberKey(event)"
								value="<?=$_input_acadyear?>" />
						<font color="#FF0000">*</font>
					</td>
				</tr>

				<tr>
					<td align="right" >ชื่อกิจกรรม :</td>
					<td>
						<input  type="text" id="activity_name" name="activity_name" class="noborder2" s
								size="50" maxlength="250" 
								value="<?=$_input_activity_name?>" />
						<font color="#FF0000">*</font>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" >วัตถุประสงค์ :</td>
					<td>
						<textarea id="activity_details" name="activity_details" cols="50" rows="6" 
								  class="inputboxUpdate" ><?=$_input_activity_details?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right" >วันที่เริ่มกิจกรรม :</td>
					<td>
						<input  type="text" name="start_date" id="start_date" size="10" 
								onClick="showCalendar(this.id)" 
								value="<?=$_input_start_date?>" class="noborder2"  />
						<font color="#FF0000">*</font>
					</td>
				</tr>
				<tr>
					<td align="right" >วันที่สิ้นสุดกิจกรรม :</td>
					<td>
						<input  type="text" name="finish_date" id="finish_date" size="10" 
								onClick="showCalendar(this.id)" 
								value="<?=$_input_finish_date?>" class="noborder2"  />
						<font color="#FF0000">*</font>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" >ประเภทเงินงบประมาณ :</td>
					<td>
						<?php
							$_sql_income = "select * from incomes order by convert(income_name using tis620) ";
							$_res_income = mysqli_query($_connection,$_sql_income);
						?>
						<select id="income_id" name="income_id" class="inputboxUpdate">
							<option value=""></option>
							<? while($_dat = mysqli_fetch_assoc($_res_income)) { ?> 
								<option value="<?=$_dat['income_id']?>" <?=$_input_income_id==$_dat['income_id']?"selected":""?> >
									<?=$_dat['income_name']?>
								</option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" >จำนวนเงินงบประมาณที่ขอ :</td>
					<td>
						<input  type="text" id="request_budget" name="request_budget" 
								size="15" maxlength="13" value="<?=$_input_request_budget?>" 
								onkeypress="return isNumberKey2(event)" class="noborder2"  /> 
								บาท <font color="#FF0000">*</font>
					</td>
				</tr>
				<tr>
					<td align="right" >จำนวนเงินงบประมาณที่อนุมัติ :</td>
					<td>
						<input  type="text" id="approve_budget" name="approve_budget" 
								size="15" maxlength="13" value="<?=$_input_approve_budget?>" 
								onkeypress="return isNumberKey2(event)" class="noborder2"  /> 
								บาท <font color="#FF0000">*</font>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" >ฝ่ายที่รับผิดกิจกรรม :</td>
					<td>
						<?php
							$_sql_income = "select * from departments";
							$_res_income = mysqli_query($_connection,$_sql_income);
						?>
						<select id="department_id" name="department_id" class="inputboxUpdate">
							<option value=""></option>
							<? while($_dat = mysqli_fetch_assoc($_res_income)) { ?> 
								<option value="<?=$_dat['department_id']?>" <?=$_input_department_id==$_dat['department_id']?"selected":""?> >
									<?=$_dat['department_name']?>
								</option>
							<? } ?>
						</select>
					</td>
				</tr>

				<tr>
					<td align="right" valign="top" >ผู้รับผิดชอบกิจกรรม :</td>
					<td>
						<?php 
							$sql_teacher = " select * from hr_staff s
											where 
												1=1
												and staff_status = 'ACTIVE'
											order by convert(s.firstname using tis620), convert(s.lastname using tis620)
										";
							$resTeacher = mysqli_query($_connection,$sql_teacher);	
							$_submit_teacher_name = "";		
						?>
						
							<select id="activity_owner" name="activity_owner" class="inputboxUpdate">
								<option value=""></option>
								<?php
									$_select = "";
									while($dat = mysqli_fetch_assoc($resTeacher))
									{
										if($_input_activity_owner == $dat['staff_id']){
											$_select = "selected";
										}else{
											$_select = "";
										}
										echo "<option value=\"" . $dat['staff_id'] . "\" $_select>";
										echo $dat['firstname']. ' ' . $dat['lastname'];
										echo "</option>";
									}
									
								?>
							</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top" >สถานะกิจกรรม :</td>
					<td>
						<?php
							$_sql_status = "select * from project_status";
							$_res_status = mysqli_query($_connection,$_sql_status);
						?>
						<select id="activity_status" name="activity_status" class="inputboxUpdate">
							<? while($_dat = mysqli_fetch_assoc($_res_status)) { ?> 
								<option value="<?=$_dat['status_id']?>" <?=$_input_activity_status==$_dat['status_id']?"selected":""?> >
									<?=$_dat['status_name']?>
								</option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"></td>
					<td>
						<?php
							$_button_value = "บันทึก";
							$_action       = "save";
							$_button       = "";
							if((isset($_POST['action']) && $_processing_result) || $_check_new_act){
								$_action       = "update";
								$_button_value = "แก้ไข";
								$_button       = "<input type=\"button\" value=\"เพิ่มใหม่\" ";
								$_button      .= "onclick=\"location.href='index.php?option=module_projects/addActivity&project_number_search=" . $_project['project_number'] . "'\"";
								$_button      .= " class=\"button\" />";
							}else{
								$_action = "save";
							}
						?>
						<input type="hidden" name="used_budget"  value="<?=$_input_used_budget?>" />
						<input type="hidden" name="project_id"   value="<?=$_project['project_id']?>" />
						<input type="hidden" name="activity_id"  value="<?=$_activity_id?>" />
						<input type="hidden" name="action" value="<?=$_action?>" />
						<input type="button" value="<?=$_button_value?>" onclick="checkFormValue()" class="button" />
						<?=$_button?>
					</td>
				</tr>
			</table>
		</div>
	</form>

</div>

