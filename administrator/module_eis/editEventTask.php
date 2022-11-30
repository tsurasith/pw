<div id="content">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>2.2 เพิ่ม/แก้ไข ส่วนงานย่อยของโมดูลการทำงาน (แก้ไข)</strong></font></span></td>
    <td>&nbsp;</td>
  </tr>
</table>



	<?php
		$_update_result = "";

		if(isset($_POST['save'])){
			if(trim($_POST['event_task_name']) != "") {
				$_sqlInsert = "UPDATE `event_task`
						SET
						`event_module_code` = '" . trim($_POST['event_module_code']) . "',
						`event_task_name` = '" . trim($_POST['event_task_name']) . "',
						`event_task_description` = '" . trim($_POST['event_task_description']) . "',
						`event_task_owner` = '" . trim($_POST['event_task_owner']) . "',
						`average_time_count` = '" . trim($_POST['average_time_count']) . "',
						`event_task_point_count` = '" . trim($_POST['event_task_point_count']) . "',
						`event_task_year` = '" . $acadyear . "',
						`updated_datetime` = CURRENT_TIMESTAMP,
						`updated_user` = '" . $_SESSION['user_account_id'] . "'
						WHERE
							event_task_code = '" . (INT)$_REQUEST['eventTaskCode'] . "'";

				$_resInsert = mysqli_query($_connection,$_sqlInsert);
				if($_resInsert){
					$_update_result = "<font color='green'>ระบบได้เพิ่มส่วนงานย่อย:" . $_POST['event_task_name'] . " เรียบร้อยแล้ว</font>";
				}else {
					$_update_result = "<font color='red'>เกิดข้อผิดพลาด ไม่สามารถเพิ่มข้อมูลได้</font>";
				}
				
			}else {
				$_update_result = "<font color='red'>เกิดข้อผิดพลาด ไม่สามารถเพิ่มข้อมูลได้เนื่องจากระบบไม่อนุญาตให้ชื่อโมดูลเป็นค่าว่าง</font>";
			}
		}

		$_event_task_code = "";
		$_event_task_code = isset($_REQUEST['eventTaskCode'])?$_REQUEST['eventTaskCode']:"";

		$_sql = "SELECT * FROM event_task where event_task_code = '" . $_event_task_code . "'";
		$_res = mysqli_query($_connection,$_sql);

		if($_res) {
			$_datEventTask = mysqli_fetch_assoc($_res);
	?>

	<div align="center">
			<form method="post">
			<table class="admintable" align="center">
				<tr>
					<td class="key" colspan="2" align="center">
						แก้ไขส่วนงานย่อย
					</td>
				</tr>
				<tr>
					<?php
						$_sqlEventModule = "SELECT * 
											FROM event_module
											ORDER BY convert(event_module_name using tis620) ";
						$_resEventModule = mysqli_query($_connection,$_sqlEventModule);
						$_event_module = "";
						$_event_module = isset($_POST['event_module_code'])?$_POST['event_module_code']:"";

					?>

					<td align="right">โมดูลงานหลัก</td>
					<td>
						<select name="event_module_code" class="inputboxUpdate">
							<? while($_dat = mysqli_fetch_assoc($_resEventModule)) { ?> 
								<option value="<?=$_dat['event_module_code']?>" <?=$_dat['event_module_code']==$_datEventTask['event_module_code']?"selected":""?>>
									<?=$_dat['event_module_name']?>
								</option>
							<? } //end while ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">ชื่องานย่อย</td>
					<td valign="top">
						<input type="text" class="inputboxUpdate" name="event_task_name" size="50" maxlength="99" value="<?=$_datEventTask['event_task_name']?>" />
						<br/>ป้อนความยาวได้ไม่เกิน 90 ตัวอักษร
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">คำอธิบายเพิ่มเติม</td>
					<td valign="top">
						<textarea name="event_task_description" cols="40" rows="3" class="inputboxUpdate"><?=$_datEventTask['event_task_description']?></textarea>
						<br/>ป้อนความยาวได้ไม่เกิน 250 ตัวอักษร
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">น้ำหนักคะแนน</td>
					<td valign="top">
						<input type="text" class="inputboxUpdate" name="event_task_point_count" size="5" maxlength="3" value="<?=$_datEventTask['event_task_point_count']?>" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">ค่าเฉลี่ยเวลา</td>
					<td valign="top">
						<input type="text" class="inputboxUpdate" name="average_time_count" size="5" maxlength="3" value="<?=$_datEventTask['average_time_count']?>" />
						 ให้ระบุตัวเลขในหน่วยนาที
					</td>
				</tr>
				<tr>
					<?php
						$_sqlEventOwner = "SELECT * 
											FROM users_account
											ORDER BY convert(user_account_firstname using tis620) ";
						$_resEventOwner = mysqli_query($_connection,$_sqlEventOwner);
						$_event_owner = "";
						$_event_owner = isset($_POST['event_task_owner'])?$_POST['event_task_owner']:"";

					?>

					<td align="right">ผู้รับผิดชอบหลัก</td>
					<td>
						<select name="event_task_owner" class="inputboxUpdate">
						<option value=""> ไม่ระบุ </option>
							<? while($_dat = mysqli_fetch_assoc($_resEventOwner)) { ?> 
								<option value="<?=$_dat['user_account_id']?>" <?=$_dat['user_account_id']==$_datEventTask['event_task_owner']?"selected":""?>>
									<?=$_dat['user_account_prefix'].$_dat['user_account_firstname']. ' ' . $_dat['user_account_lastname']?>
								</option>
							<? } //end while ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<br/>
						<?=$_update_result?>
						<br/><br/>
						<input class="button" type="submit" name="save" value="บันทึก" /> 
						<input class="button" type="button" name="back" value="ย้อนกลับ" onClick="location.href='../administrator/index.php?option=module_eis/eventTaskList'" />
					</td>
				</tr>
			</table>
	</div>
	<?php		
		}
		else {
			echo "<div align='center'><br/><br/><font color='red'>ไม่พบข้อมูลจงย้อนกลับไปที่หน้าแสดงรายการงานย่อย/font></div>";
	}
	?>
</div>

