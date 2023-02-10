<SCRIPT language="javascript" type="text/javascript">


	  function checkValue(){
		if(document.getElementById('staff_type').value == "")
			{ alert('กรุณาระบุ ประเภทบุคลากร ก่อน'); document.getElementById('staff_type').focus(); return;}

		if(document.getElementById('staff_status').value == "")
			{ alert('กรุณาระบุ สถานภาพปัจจุบันบุคลากร ก่อน'); document.getElementById('staff_status').focus(); return;
		}
		else 
		{ 
			document.staff_details.submit(); 
		}
	  }
	  	  
</SCRIPT>


<?php
	$_processing_message = "";
	$_processing_result  = false;

	if(isset($_POST['confirmUpdate'])){


		$_sql_update = "
				UPDATE
					`hr_staff`
				SET
					`finger_code`	= '" . trim($_POST['finger_code']) ."',
					`prefix` 		= '" . trim($_POST['prefix']) ."',
					`firstname`		= '" . trim($_POST['firstname']) ."',
					`lastname`		= '" . trim($_POST['lastname']) ."',
					`nickname`		= '" . trim($_POST['nickname']) ."',
					`position`		= '" . trim($_POST['position']) ."',
					`staff_type`	= '" . trim($_POST['staff_type']) ."',
					`staff_status`	= '" . trim($_POST['staff_status']) ."',
					`phone_number`	= '" . trim($_POST['phone_number']) ."',
					`mobile_number` = '" . trim($_POST['mobile_number']) ."',
					`email` 		= '" . trim($_POST['email']) ."',
					`updated_datetime` = CURRENT_TIMESTAMP,
					`updated_user` = '". $_SESSION['user_account_id'] ."'
				WHERE
					`staff_id` = '" . trim($_POST['staff_id']) ."'
				";
		$_res_update = mysqli_query($_connection,$_sql_update);
			if($_res_update){

				$_check = 0;
				$_check_existing_in_teacher = "select * from teachers where teacher_id = '" . trim($_POST['staff_id']) . "'";
				$_check = mysqli_num_rows(mysqli_query($_connection,$_check_existing_in_teacher));

				if($_check > 0){
					$_sql_update_teachers = "
									UPDATE
										`teachers`
									SET
										`PREFIX`     = '" . trim($_POST['prefix']) ."',
										`FIRSTNAME`  = '" . trim($_POST['firstname']) ."',
										`LASTNAME`   = '" . trim($_POST['lastname']) ."',
										`NICKNAME`   = '" . trim($_POST['nickname']) ."',
										`POSITION`   = '" . trim($_POST['position']) ."',
										`T_PHONE`    = '" . trim($_POST['phone_number']) ."',
										`t_mobile`   = '" . trim($_POST['mobile_number']) ."',
										`t_email`    = " . (trim($_POST['email'])==""?"NULL":("'".trim($_POST['email'])."'")) . "
									WHERE
										`teacher_id` = '" . trim($_POST['staff_id']) ."'
							";
					$_res_update_teachers = mysqli_query($_connection,$_sql_update_teachers);

				}				
				
				
				$_processing_message  = "ระบบได้ทำการบันทึกแก้ไขข้อมูลบุคลากรเรียบร้อยแล้ว";

				$_text   = "";
				$_text  .= "มีการบันทึกแก้ไขข้อมูลบุคลากร";

				$_text .=  "\nโดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token_module_hr);
				SendLineMessage($message,$_line_token);
				$_processing_result = true;

				
				$_event_details = "";
				$_event_details .= str_replace("<br/>","",$_processing_message);

				$_event_key = hash("sha256",$_event_details.date("H:i:s").$_sql_update);

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
			<span class="normal"><font color="#0066FF"><strong>3.8 แก้ไขข้อมูลบุคลากร</strong></font></span></td>
		<td align="right">

			ปีการศึกษา <?=$acadyear?>
			ภาคเรียนที่ <?=$acadsemester?>

			<br/>
			<form name="staff" method="post" action="" onChange="document.staff.submit();">
				<font size="2" color="black">เลือกบุคลากร</font> 
				<?php
					$_sql_staff = "
							select * 
							from   hr_staff
							order by 
									convert(firstname using tis620), convert(lastname using tis620)
						";
					$_res_staff = mysqli_query($_connection,$_sql_staff);

				?>
					
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
	</table>
	
	<? if(isset($_POST['staff_id']) && $_POST['staff_id'] != "") { ?>  
		<?php
			$_sql_staff_details = " select * from hr_staff where staff_id = '" . $_POST['staff_id'] . "' ";
			$_res_detail = mysqli_query($_connection,$_sql_staff_details);

			$_dat = mysqli_fetch_assoc($_res_detail);

		?>
		<div align="center">
		<form name="staff_details" method="post" autocomplete="off">
			<table class="admintable" align="center">
				<tr>
					<td class="key" align="center" colspan="2">
						แก้ไขข้อมูลบุคลากร 
					</td>
				</tr>
				<tr>
					<td align="right">รหัสเครื่องสแกนลายนิ้วมือ :</td>
					<td>
						<input type="text" name="finger_code" id="finger_code" value="<?=$_dat['finger_code']?>" 
							   class="inputboxUpdate" size="4" maxlength="4"
							   onKeyPress="return isNumberKey(this,event)" />
					</td>
				</tr>

				<tr>
					<td align="right">คำนำหน้า :</td>
					<td>
						<input type="text" name="prefix" id="prefix" value="<?=$_dat['prefix']?>"
							   class="inputboxUpdate" size="7"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ชื่อ :</td>
					<td>
						<input type="text" name="firstname" id="firstname" value="<?=$_dat['firstname']?>"
							   class="inputboxUpdate" size="20"
							   />
					</td>
				</tr>
				<tr>
					<td align="right">นามสกุล :</td>
					<td>
						<input type="text" name="lastname" id="lastname" value="<?=$_dat['lastname']?>"
							   class="inputboxUpdate" size="20"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ชื่อเล่น :</td>
					<td>
						<input type="text" name="nickname" id="nickname" value="<?=$_dat['nickname']?>"
							   class="inputboxUpdate" size="7"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ตำแหน่ง :</td>
					<td>
						<input type="text" name="position" id="position" value="<?=$_dat['position']?>"
							   class="inputboxUpdate" size="40"
							   />
					</td>
				</tr>

				<tr>
					<td align="right">ประเภทบุคลากร :</td>
					<td>
						<select name="staff_type" class="inputboxUpdate" id="staff_type" >
							<option value=""></option>
							<option value="ผู้บริหาร" 			      <?=$_dat['staff_type']=="ผู้บริหาร"?"selected":""?> >ผู้บริหาร</option>
							<option value="ข้าราชการครู" 	         <?=$_dat['staff_type']=="ข้าราชการครู"?"selected":""?> >ข้าราชการครู</option>
							<option value="ครูอัตราจ้าง"              <?=$_dat['staff_type']=="ครูอัตราจ้าง"?"selected":""?> >ครูอัตราจ้าง</option>
							<option value="ลูกจ้างประจำและพนักงานจ้าง" <?=$_dat['staff_type']=="ลูกจ้างประจำและพนักงานจ้าง"?"selected":""?> >ลูกจ้างประจำและพนักงานจ้าง</option>
							<option value="ลูกจ้างเหมาบริการ"         <?=$_dat['staff_type']=="ลูกจ้างเหมาบริการ"?"selected":""?> >ลูกจ้างเหมาบริการ</option>
							<option value="นักศึกษาฝึกประสบการณ์"      <?=$_dat['staff_type']=="นักศึกษาฝึกประสบการณ์"?"selected":""?> >นักศึกษาฝึกประสบการณ์</option>
						</select>
					</td>
				</tr>

				<tr>
					<td align="right">สถานภาพปัจจุบัน :</td>
					<td>
						<select name="staff_status" class="inputboxUpdate" id="staff_status">
							<option value=""></option>
							<option value="ACTIVE"   <?=$_dat['staff_status']=="ACTIVE"?"selected":""?> >ปฏิบัติหน้าที่อยู่</option>
							<option value="INACTIVE" <?=$_dat['staff_status']=="INACTIVE"?"selected":""?> >ไม่ปฏิบัติหน้าที่</option>
						</select>
					</td>
				</tr>

				<tr>
					<td align="right">เบอร์โทร :</td>
					<td>
						<input type="text" name="phone_number" id="phone_number" value="<?=$_dat['phone_number']?>"
							   class="inputboxUpdate" size="12"
							   onKeyPress="return isNumberKey(this,event)" />
					</td>
				</tr>

				<tr>
					<td align="right">เบอร์มือถือ :</td>
					<td>
						<input type="text" name="mobile_number" id="mobile_number" value="<?=$_dat['mobile_number']?>"
							   class="inputboxUpdate" size="12"
							   onKeyPress="return isNumberKey(this,event)" />
					</td>
				</tr>

				<tr>
					<td align="right">อีเมล :</td>
					<td>
						<input type="text" name="email" id="email" value="<?=$_dat['email']?>"
							   class="inputboxUpdate" size="30"
							   />
					</td>
				</tr>
				<tr>
					<td align="right">แก้ไขข้อมูลล่าสุด :</td>
					<td><?=$_dat['updated_datetime']?> โดย <?=getUserAccountName($_connection,$_dat['updated_user'])?></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="hidden" name="confirmUpdate" value="confirm" />
						<input type="button" value="บันทึกแก้ไข" class="button" onclick="checkValue();" />
						<input type="hidden" name="staff_id" value="<?=$_POST['staff_id']?>" />
					</td>
				</tr>
				<? if(isset($_POST['confirmUpdate'])){ ?>
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
		</div>
	<? } ?>


</div>

