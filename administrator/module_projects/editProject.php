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
	  	if(document.getElementById('project_number').value == '' )
		{ 
			alert('กรุณาป้อนข้อมูล รหัสโครงการให้ถูกต้อง'); 
			document.getElementById('project_number').focus(); 
			return;
		}

		if(document.getElementById('project_name').value == '' || document.getElementById('project_name').value.length < 6)
		{ 
			alert('กรุณาป้อนข้อมูล ชื่อโครงการก่อน'); 
			document.getElementById('project_name').focus(); 
			return;
		}

		if(document.getElementById('project_details').value == '' || document.getElementById('project_details').value.length < 6)
		{ 
			alert('กรุณาป้อนข้อมูล วัตถุประสงค์ก่อน'); 
			document.getElementById('project_details').focus(); 
			return;
		}
		
		if(document.getElementById('start_date').value == '' || document.getElementById('start_date').value.length < 10)
		{ 
			alert('กรุณาป้อนข้อมูล วันที่เริ่มโครงการก่อน'); 
			document.getElementById('start_date').focus(); 
			return;
		}
		
		if(document.getElementById('finish_date').value == '' || document.getElementById('finish_date').value.length < 10)
		{ 
			alert('กรุณาป้อนข้อมูล วันที่สิ้นสุดโครงการก่อน'); 
			document.getElementById('finish_date').focus(); 
			return;
		}

		const start_date   = new Date(document.getElementById('start_date').value);
		const finish_date  = new Date(document.getElementById('finish_date').value);

		if(start_date > finish_date)
		{
			alert('คุณป้อนข้อมูลไม่ถูกต้อง วันที่เริ่มโครงการ มากกว่า วันสิ้นสุดโครงการ กรุณาตรวจสอบอีกครั้ง'); 
			document.getElementById('start_absent_date').focus(); return;
		}


		if(document.getElementById('income_id').value == '')
		{ 
			alert('กรุณาเลือก ประเภทเงินงบประมาณก่อน'); 
			document.getElementById('income_id').focus(); 
			return;
		}

		if(document.getElementById('request_budget').value == '')
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
		
		if(document.getElementById('approve_budget').value == '')
		{ 
			alert('กรุณาป้อนข้อมูล เงินงบประมาณที่อนุมัติก่อน'); 
			document.getElementById('approve_budget').focus(); 
			return;
		}

		if(document.getElementById('department_id').value == '')
		{ 
			alert('กรุณาเลือก ฝ่ายที่รับผิดชอบโครงการก่อน'); 
			document.getElementById('department_id').focus(); 
			return;
		}

		if(document.getElementById('project_owner').value == '')
		{ 
			alert('กรุณาเลือก ผู้รับผิดชอบโครงการก่อน'); 
			document.getElementById('project_owner').focus(); 
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

	$_project_number = "";
	if(isset($_POST['search'])){
		$_project_number = trim($_POST['project_number_search']);
	}

	if(isset($_POST['action'])){
		$_project_number = trim($_POST['project_number']);
	}
?>

<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	// - update data
	if(isset($_POST['action']) && $_POST['action'] == "update"){ 

		$_sql_update = "
			UPDATE `projects`
			SET
				`project_number`   = '" . $_POST['project_number'] . "',
				`project_name`     = '" . trim($_POST['project_name']) . "',
				`acadyear`         = '" . $_POST['acadyear'] . "',
				`acadsemester`     = '" . $_POST['acadsemester'] . "',
				`project_details`  = '" . trim($_POST['project_details']) . "',
				`start_date`       = '" . $_POST['start_date'] . "',
				`finish_date`      = '" . $_POST['finish_date'] . "',
				`income_id`        = '" . $_POST['income_id'] . "',
				`department_id`    = '" . $_POST['department_id'] . "',
				`request_budget`   = '" . $_POST['request_budget'] . "',
				`approve_budget`   = '" . $_POST['approve_budget'] . "',
				`used_budget`      = '" . $_POST['used_budget'] . "',
				`project_owner`    = '" . $_POST['project_owner'] . "',
				`project_status`   = '" . $_POST['project_status'] . "',
				`updated_datetime` =  CURRENT_TIMESTAMP,
				`updated_user`     = '" . $_SESSION['user_account_id'] . "'
			WHERE
				`project_id` =   '" . $_POST['project_id'] . "'
		";

		$_res_update = mysqli_query($_connection,$_sql_update);
		if($_res_update){
			$_processing_text = "บันทึกแก้ไขข้อมูลโครงการ " . trim($_POST['project_name']) . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "โครงการ: ";
			$_text .= trim($_POST['project_name']) . " ถูกแก้ไขข้อมูล ";
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
			$_project_number  = $_POST['old_project_number'];
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกโครงการได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}
		
	}

	$_sql_project = "select * from projects where project_number = '" . $_project_number . "'";
	$_res_project = mysqli_query($_connection,$_sql_project);

	$_check_existing = 0;
	$_check_existing = mysqli_num_rows($_res_project);
	if($_check_existing>0){
		$_project = mysqli_fetch_assoc($_res_project);
	}else{
		$_processing_result = false;
		$_processing_text   = "ไม่พบโครงการตามที่ค้นหา";
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
					<span class="normal"><font color="#0066FF"><strong>1.2 แก้ไขข้อมูลโครงการ</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<br/>
					
					<?php
						$_value = "";
						if(isset($_POST['search'])){
							$_value = $_POST['project_number_search'];
						}else if($_check_existing != 0){
							$_value = $_project['project_number'];
						}
					?>

					<form method="post" autocomplete="off">
						<font size="2" color="#000000">รหัสโครงการ</font>
						<input  type="text" name="project_number_search" id="project_number_search" 
								value="<?=$_value?>" 
								size="20" maxlength="20" class="inputboxUpdate" />

						<input  type="submit" name="search" value="เรียกดู" class="button" />
					</form>
				</td>
			</tr>
		</table>

		<div align="center">
			<table class="admintable">
				<tr height="55px">
					<td colspan="2" align="center">
						<?php
							if(isset($_POST['action']) && $_processing_result){
								echo "<font color='green'>";
								echo $_processing_text;
								echo "</font>";
							}
							
							if(isset($_POST['action']) && $_processing_result == false){
								echo "<font color='red'>";
								echo $_processing_text;
								echo "</font>";
							}

							if(isset($_POST['search']) && $_check_existing == 0){
								echo "<font color='red'>";
								echo $_processing_text;
								echo "</font>";
							}
						?>
					</td>
				</tr>
			</table>
		</div>

	<? if($_check_existing != 0) { ?>
		<form method="post" autocomplete="off" name="myform">
			<div align="center">
				<table class="admintable">
					<tr height="35px">
						<td class="key" colspan="2" align="center">บันทึกข้อมูลโครงการ</td>
					</tr>
					<tr>
						<td align="right" width="200px" valign="top" >รหัสโครงการ :</td>
						<td>
							<input  type="text" id="project_number" name="project_number" class="noborder2" 
									size="20" maxlength="20" value="<?=$_project['project_number']?>" />
							<font color="#FF0000">*</font>
						</td>
					</tr>
					<tr>
						<td align="right" width="200px" valign="top" >ภาคเรียน / ปีการศึกษา :</td>
						<td>
							<input  type="text" id="acadsemester" name="acadsemester" class="noborder2" 
									size="1" maxlength="1" onkeypress="return isNumberKey(event)"
									value="<?=$_project['acadsemester']?>" /> / 

							<input  type="text" id="acadyear" name="acadyear" class="noborder2" 
									size="4" maxlength="4" onkeypress="return isNumberKey(event)"
									value="<?=$_project['acadyear']?>" />
							<font color="#FF0000">*</font>
						</td>
					</tr>

					<tr>
						<td align="right" >ชื่อโครงการ :</td>
						<td>
							<input  type="text" id="project_name" name="project_name" class="noborder2" s
									size="50" maxlength="250" 
									value="<?=$_project['project_name']?>" />
							<font color="#FF0000">*</font>
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" >วัตถุประสงค์ :</td>
						<td>
							<textarea id="project_details" name="project_details" cols="50" rows="6" 
									class="inputboxUpdate" ><?=$_project['project_details']?></textarea>
						</td>
					</tr>
					<tr>
						<td align="right" >วันที่เริ่มโครงการ :</td>
						<td>
							<input  type="text" name="start_date" id="start_date" size="10" 
									onClick="showCalendar(this.id)" 
									value="<?=$_project['start_date']?>" class="noborder2"  />
							<font color="#FF0000">*</font>
						</td>
					</tr>
					<tr>
						<td align="right" >วันที่สิ้นสุดโครงการ :</td>
						<td>
							<input  type="text" name="finish_date" id="finish_date" size="10" 
									onClick="showCalendar(this.id)" 
									value="<?=$_project['finish_date']?>" class="noborder2"  />
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
									<option value="<?=$_dat['income_id']?>" <?=$_project['income_id']==$_dat['income_id']?"selected":""?> >
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
									size="15" maxlength="13" value="<?=$_project['request_budget']?>" 
									onkeypress="return isNumberKey2(event)" class="noborder2"  /> 
									บาท <font color="#FF0000">*</font>
						</td>
					</tr>
					<tr>
						<td align="right" >จำนวนเงินงบประมาณที่อนุมัติ :</td>
						<td>
							<input  type="text" id="approve_budget" name="approve_budget" 
									size="15" maxlength="13" value="<?=$_project['approve_budget']?>" 
									onkeypress="return isNumberKey2(event)" class="noborder2"  /> 
									บาท <font color="#FF0000">*</font>
						</td>
					</tr>
					<tr>
						<td align="right" valign="top" >ฝ่ายที่รับผิดโครงการ :</td>
						<td>
							<?php
								$_sql_income = "select * from departments";
								$_res_income = mysqli_query($_connection,$_sql_income);
							?>
							<select id="department_id" name="department_id" class="inputboxUpdate">
								<option value=""></option>
								<? while($_dat = mysqli_fetch_assoc($_res_income)) { ?> 
									<option value="<?=$_dat['department_id']?>" <?=$_project['department_id']==$_dat['department_id']?"selected":""?> >
										<?=$_dat['department_name']?>
									</option>
								<? } ?>
							</select>
						</td>
					</tr>

					<tr>
						<td align="right" valign="top" >ผู้รับผิดชอบโครงการ :</td>
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
							
								<select id="project_owner" name="project_owner" class="inputboxUpdate">
									<option value=""></option>
									<?php
										$_select = "";
										while($dat = mysqli_fetch_assoc($resTeacher))
										{
											if($_project['project_owner'] == $dat['staff_id']){
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
						<td align="right" valign="top" >สถานะโครงการ :</td>
						<td>
							<?php
								$_sql_status = "select * from project_status";
								$_res_status = mysqli_query($_connection,$_sql_status);
							?>
							<select id="project_status" name="project_status" class="inputboxUpdate">
								<? while($_dat = mysqli_fetch_assoc($_res_status)) { ?> 
									<option value="<?=$_dat['status_id']?>" <?=$_project['project_status']==$_dat['status_id']?"selected":""?> >
										<?=$_dat['status_name']?>
									</option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right"></td>
						<td>

							<input type="hidden" name="used_budget"  value="<?=$_project['used_budget']?>" />
							<input type="hidden" name="project_id"   value="<?=$_project['project_id']?>" />
							<input type="hidden" name="old_project_number"   value="<?=$_project['project_number']?>" />
							<input type="hidden" name="action" value="update" />
							<input type="button" value="บันทึกแก้ไข" onclick="checkFormValue()" class="button" />
						</td>
					</tr>
				</table>
			</div>
		</form>
	<? } ?>
	

</div>

