<SCRIPT language="javascript" type="text/javascript">


	  function checkValue(){
		
		if(document.getElementById('prefix').value == "")
			{ alert('กรุณาระบุ คำนำหน้า ก่อน'); document.getElementById('prefix').focus(); return;}

		if(document.getElementById('firstname').value == "")
			{ alert('กรุณาระบุ ชื่อ ก่อน'); document.getElementById('firstname').focus(); return;}

		if(document.getElementById('lastname').value == "")
			{ alert('กรุณาระบุ นามสกุล ก่อน'); document.getElementById('lastname').focus(); return;}

		if(document.getElementById('position').value == "")
			{ alert('กรุณาระบุ ตำแหน่ง ก่อน'); document.getElementById('position').focus(); return;}
		
		if(document.getElementById('staff_type').value == "")
		{ 
			alert('กรุณาระบุ ประเภทบุคลากร ก่อน'); document.getElementById('staff_type').focus(); return;
		}
		else 
		{ 
			document.staff_details.submit(); 
		}
	  }
	  	  
</SCRIPT>


<?php
	$_staff_id = "";
	$_processing_message = "";
	$_processing_result  = false;

	if(isset($_POST['confirmSave'])){

		$_staff_id = strtoupper(gen_uuid());

		$_sql_insert = "
					
				INSERT INTO `hr_staff`(
					`staff_id`,
					`finger_code`,
					`prefix`,
					`firstname`,
					`lastname`,
					`nickname`,
					`position`,
					`staff_type`,
					`staff_status`,
					`phone_number`,
					`mobile_number`,
					`email`,
					`created_datetime`,
					`created_user`,
					`updated_datetime`,
					`updated_user`
				)
				VALUES(
					'" . $_staff_id . "',
					'" . trim($_POST['finger_code']) . "',
					'" . trim($_POST['prefix']) . "',
					'" . trim($_POST['firstname']) . "',
					'" . trim($_POST['lastname']) . "',
					'" . trim($_POST['nickname']) . "',
					'" . trim($_POST['position']) . "',
					'" . trim($_POST['staff_type']) . "',
					'ACTIVE',
					'" . trim($_POST['phone_number']) . "',
					'" . trim($_POST['mobile_number']) . "',
					'" . trim($_POST['email']) . "',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "'
				)
			";
		$_res_update = mysqli_query($_connection,$_sql_insert);
			if($_res_update){
				
				$_processing_message  = "ระบบได้ทำการบันทึกเพิ่มข้อมูลบุคลากรเรียบร้อยแล้ว";

				$_text   = "";
				$_text  .= "มีการบุคลากรเข้าไปในระบบ";

				$_text .=  "\nโดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token_module_hr);
				SendLineMessage($message,$_line_token);
				$_processing_result = true;

				
				$_event_details = "";
				$_event_details .= str_replace("<br/>","",$_processing_message);

				$_event_key = hash("sha256",$_event_details.date("H:i:s").$_sql_insert);

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
			<span class="normal"><font color="#0066FF"><strong>3.7 เพิ่มบุคลากร (ลูกจ้างประจำและพนักงานจ้าง, ลูกจ้างเหมาบริการ)</strong></font></span></td>
		<td align="right">

			ปีการศึกษา <?=$acadyear?>
			ภาคเรียนที่ <?=$acadsemester?>

		</td>
		</tr>
	</table>
	

	<div align="center">
		<form name="staff_details" method="post" autocomplete="off">
			<table class="admintable" align="center">
				<tr>
					<td class="key" align="center" colspan="2">
						เพิ่มข้อมูลบุคลากร 
					</td>
				</tr>
				<tr>
					<td align="right">รหัสเครื่องสแกนลายนิ้วมือ :</td>
					<td>
						<input type="text" name="finger_code" id="finger_code" 
							   class="inputboxUpdate" size="4" maxlength="4"
							   onKeyPress="return isNumberKey(this,event)" />
					</td>
				</tr>

				<tr>
					<td align="right">คำนำหน้า :</td>
					<td>
						<input type="text" name="prefix" id="prefix" 
							   class="inputboxUpdate" size="7"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ชื่อ :</td>
					<td>
						<input type="text" name="firstname" id="firstname" 
							   class="inputboxUpdate" size="20"
							   />
					</td>
				</tr>
				<tr>
					<td align="right">นามสกุล :</td>
					<td>
						<input type="text" name="lastname" id="lastname" 
							   class="inputboxUpdate" size="20"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ชื่อเล่น :</td>
					<td>
						<input type="text" name="nickname" id="nickname" 
							   class="inputboxUpdate" size="7"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ตำแหน่ง :</td>
					<td>
						<input type="text" name="position" id="position" 
							   class="inputboxUpdate" size="40"
							   />
					</td>
				</tr>

				<tr>
					<td align="right" valign="top">ประเภทบุคลากร :</td>
					<td>
						<select name="staff_type" class="inputboxUpdate" id="staff_type" >
							<option value=""></option>
							<option value="ลูกจ้างประจำและพนักงานจ้าง">ลูกจ้างประจำและพนักงานจ้าง</option>
							<option value="ลูกจ้างเหมาบริการ" >ลูกจ้างเหมาบริการ</option>
						</select>
						<br/>
						ผู้บริหาร ครูผู้สอน (รวมถึงนักศึกษาฝึกประสบการณ์) ให้แจ้งผู้ดูแลระบบเพิ่มผู้ใช้งานก่อน <br/>เนื่องจากจะต้องมีข้อมูลล็อกอินเข้าสู่ระบบด้วย
					</td>
				</tr>

				<tr>
					<td align="right">เบอร์โทร :</td>
					<td>
						<input type="text" name="phone_number" id="phone_number" 
							   class="inputboxUpdate" size="12"
							   onKeyPress="return isNumberKey(this,event)" />
					</td>
				</tr>

				<tr>
					<td align="right">เบอร์มือถือ :</td>
					<td>
						<input type="text" name="mobile_number" id="mobile_number" 
							   class="inputboxUpdate" size="12"
							   onKeyPress="return isNumberKey(this,event)" />
					</td>
				</tr>

				<tr>
					<td align="right">อีเมล :</td>
					<td>
						<input type="text" name="email" id="email" 
							   class="inputboxUpdate" size="30"
							   />
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<input type="hidden" name="confirmSave" value="confirm" />
						<input type="button" value="เพิ่ม" class="button" onclick="checkValue();" />
					</td>
				</tr>
				<? if(isset($_POST['confirmSave'])){ ?>
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
			</table>
		</form>
	</div>
</div>

