<div id="content">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>2.1 เพิ่ม/แก้ไข รายการโมดูลการทำงานหลัก (เพิ่ม)</strong></font></span></td>
    <td>&nbsp;</td>
  </tr>
</table>

	<?php
		$_insert_result = "";

		if(isset($_POST['save'])){
			if(trim($_POST['event_module_name']) != "") {
				$_sqlInsert = "INSERT INTO event_module (
								event_module_name,
								event_module_description,
								created_datetime,
								updated_user ) VALUES (
								 '" . trim($_POST['event_module_name']) . "',
								 '" . trim($_POST['event_module_description']) . "',
								 CURRENT_TIMESTAMP ,
								'" . $_SESSION['user_account_id'] . "') ";
				$_resInsert = mysqli_query($_connection,$_sqlInsert);
				if($_resInsert){
					$_insert_result = "<font color='green'>ระบบได้เพิ่มโมดูลงานหลัก:" . $_POST['event_module_name'] . " เรียบร้อยแล้ว</font>";
				}else {
					$_insert_result = "<font color='red'>เกิดข้อผิดพลาด ไม่สามารถเพิ่มข้อมูลได้</font>";
				}
				
			}else {
				$_insert_result = "<font color='red'>เกิดข้อผิดพลาด ไม่สามารถเพิ่มข้อมูลได้เนื่องจากระบบไม่อนุญาตให้ชื่อโมดูลเป็นค่าว่าง</font>";
			}
		}
	?>
			<form method="post">
			<table class="admintable" align="center">
				<tr>
					<td class="key" colspan="2" align="center">
						เพิ่มโมดูลการทำงานหลัก
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">ชื่อโมดูลงาน</td>
					<td valign="top">
						<input type="text" class="inputboxUpdate" name="event_module_name" size="50" maxlength="99" />
						<br/>ป้อนความยาวได้ไม่เกิน 90 ตัวอักษร
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">คำอธิบายเพิ่มเติม</td>
					<td valign="top">
						<textarea name="event_module_description" cols="40" rows="3" class="inputboxUpdate"></textarea>
						<br/>ป้อนความยาวได้ไม่เกิน 250 ตัวอักษร
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<br/>
						<?=$_insert_result?>
						<br/><br/>
						<input class="button" type="submit" name="save" value="บันทึก" /> 
						<input class="button" type="button" name="back" value="ย้อนกลับ" onClick="location.href='../administrator/index.php?option=module_eis/moduleList'" />
					</td>
				</tr>
			</table>
	</div>
</div>

