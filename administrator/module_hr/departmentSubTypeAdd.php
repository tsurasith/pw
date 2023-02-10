

<SCRIPT language="javascript" type="text/javascript">
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

	if(isset($_POST['addnew'])){
		$_sql_insert = "
				INSERT INTO `department_subtypes`(
					`department_subtype_name`,
					`department_id`,
					`updated_datetime`
				)
				VALUES(
					'" . trim($_POST['department_subtype_name']) . "',
					'" . $_POST['department_id'] . "',
					CURRENT_TIMESTAMP
				)
				";
		$_resInsert = mysqli_query($_connection,$_sql_insert);
			if($_resInsert){
				
				$_processing_message = "ระบบได้ทำการเพิ่มส่วนงาน: " . trim($_POST['department_subtype_name']) . " เรียบร้อยแล้ว <br/>";

				$_text  = "";
				$_text .= "ระบบได้ทำการเพิ่มส่วนงาน " . trim($_POST['department_subtype_name']) . "";

				$_processing_message = $_text;

				$_text .=  "\nโดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token_module_hr);
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
 

<?php

	$_department_subtype_id = "";
	if(isset($_REQUEST['department_subtype_id'])){
		$_department_subtype_id = $_REQUEST['department_subtype_id'];
	}

?>




<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>3.5 เพิ่ม/แก้ไข ภาระงานสังกัดฝ่าย</strong></font></span></td>
			<td >&nbsp;</td>
			</tr>
		</table>
	  


		<div align="center">
		<?php
			$_process = array();
			$_sql_cur = "
					select
						*
						
					from
						departments
					order by
						department_id		
				";
			$_res_curr = mysqli_query($_connection,$_sql_cur);
			$_rows = mysqli_num_rows($_res_curr);
			$_no = 1;
		?>
		<? if($_rows>0){  ?>
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
				<tr> 
				<th colspan="10" align="center">
						<!-- <img src="../images/school_logo.png" width="120px"><br/> -->
					การบริหารจัดการงานสังกัดฝ่าย โรงเรียนเพชรวิทยาคาร <br/><br/>
				</th>
				</tr>
				<tr height="35px"> 
					<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
						<td class="key" width="220px" align="center"><?=$_dat['department_name']?></td>
						<? array_push($_process,$_dat['department_id']); ?>
					<? } ?>
				</tr>
				<tr>
					<? for($_i = 0; $_i<count($_process); $_i++){ ?>  
					<td valign="top">
						<?php
							$_num = 1;
							$_sql_sub = "
								select * 
								from department_subtypes 
								where department_id = '" . $_process[$_i] . "' 
								order by convert(department_subtype_name using tis620) ";
							$_res = mysqli_query($_connection,$_sql_sub);
							while($_dat = mysqli_fetch_assoc($_res)){
								echo "<a href='index.php?option=module_hr/departmentSubTypeEdit&department_subtype_id=" . $_dat['department_subtype_id'] . "'>";
								echo $_num++ .". ";
								echo $_dat['department_subtype_name'];
								echo "</a>";
								echo "<br/>";
							}
						?>
					</td>
					<? } ?>
				</tr>
			</table>
		<? } ?>
		<form action="" name="myform" method="post" autocomplete="off">
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
							<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
								<option value="<?=$_dat['department_id']?>" <?=isset($_POST['department_id'])&&$_POST['department_id']==$_dat['department_id']?"selected":""?>  >
									<?=$_dat['department_name']?>
								</option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td  align="right"> ชื่อส่วนงาน </td>
					<td> <input type="text" name="department_subtype_name" id="department_subtype_name" 
								size="40" class="inputboxUpdate" maxlength="150" /> </td>
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
						<input type="button" name="xxxx" class="button" value="เพิ่ม" onclick="checkValue();" /> 
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

