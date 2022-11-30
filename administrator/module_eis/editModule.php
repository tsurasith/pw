<div id="content">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>2.1 เพิ่ม/แก้ไข รายการโมดูลการทำงานหลัก (แก้ไข)</strong></font></span></td>
    <td>&nbsp;</td>
  </tr>
</table>

	<?php
		$_update_result = "";

		if(isset($_POST['update'])){
			if(trim($_POST['event_module_name']) != "") {
				$_sqlUpdate = "UPDATE event_module 
								SET event_module_name = '" . trim($_POST['event_module_name']) . "',
									event_module_description = '" . trim($_POST['event_module_description']) . "',
									updated_datetime = CURRENT_TIMESTAMP ,
									updated_user = '" . $_SESSION['user_account_id'] . "' 
								WHERE 
									event_module_code = '" . trim($_POST['event_module_code']) . "'";
				$_resUpdate = mysqli_query($_connection,$_sqlUpdate);
				if($_resUpdate){
					$_update_result = "<font color='green'>แก้ไขข้อมูลเรียบร้อยแล้ว</font>";
				}else {
					$_update_result = "<font color='red'>เกิดข้อผิดพลาด ไม่สามารถแก้ไขข้อมูลได้</font>";
				}
				
			}else {
				$_update_result = "<font color='red'>เกิดข้อผิดพลาด ไม่สามารถแก้ไขข้อมูลได้เนื่องจากระบบไม่อนุญาตให้ชื่อโมดูลเป็นค่าว่าง</font>";
			}
		}
	?>
  <?php

	 	$_module_code = "";
		$_module_code = isset($_REQUEST['eventModuleCode'])?$_REQUEST['eventModuleCode']:"";
  	 
	    $_sql = "select * from event_module where event_module_code = '" . $_module_code . "'";
        $_resModule = mysqli_query($_connection,$_sql);
      
	  	$_count = 0;
		$_count = mysqli_num_rows($_resModule);

  ?>
    <div align="center">
	<?php
			if($_count > 0){
				$_dat = mysqli_fetch_assoc($_resModule);
		?>
			<form method="post">
			<table class="admintable" align="center">
				<tr>
					<td class="key" colspan="2" align="center">
						แก้ไขโมดูลการทำงาน
					</td>
				</tr>
				<tr>
					<td align="right">รหัสโมดูล</td>
					<td ><?=$_dat['event_module_code']?></td>
				</tr>
				<tr>
					<td align="right" valign="top">ชื่อโมดูลงาน</td>
					<td valign="top">
						<input type="text" class="inputboxUpdate" name="event_module_name" size="50" maxlength="99" value="<?=$_dat['event_module_name']?>" />
						<br/>ป้อนความยาวได้ไม่เกิน 90 ตัวอักษร
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">คำอธิบายเพิ่มเติม</td>
					<td valign="top">
						<textarea name="event_module_description" cols="40" rows="3" class="inputboxUpdate"><?=$_dat['event_module_description']?></textarea>
						<br/>ป้อนความยาวได้ไม่เกิน 250 ตัวอักษร
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">วันที่แก้ไขล่าสุด</td>
					<td valign="top"><?=$_dat['updated_datetime']?></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<br/>
						<?=$_update_result?>
						<br/><br/>
						<input type="hidden" name="event_module_code" value=<?=$_module_code?> />
						<input class="button" type="submit" name="update" value="บันทึกแก้ไข" /> 
						<input class="button" type="button" name="back" value="ย้อนกลับ" onClick="location.href='../administrator/index.php?option=module_eis/moduleList'" />
					</td>
				</tr>
			</table>
		<? } 
			else {
					echo "<div align='center'><br/><br/><font color='red'>ไม่พบข้อมูลจงย้อนกลับไปที่หน้าหลักของโมดูล</font></div>";
			}
		?>
	</div>
</div>

