<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
?>

<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['curriculum_id'])){

		$_ops = "";
		$_sql_Operation = "";
		if(isset($_POST['mapping'])){
			$_sql_Operation = "
					INSERT INTO `curriculum_register`(
						`curriculum_id`,
						`acadyear`,
						`acadsemester`,
						`created_datetime`,
						`created_user`,
						`updated_datetime`,
						`updated_user`
					)
					VALUES(
						'" . $_POST['curriculum_id'] . "',
						'" . $acadyear ."',
						'" . $acadsemester . "',
						CURRENT_TIMESTAMP,
						'". $_SESSION['user_account_id'] . "',
						CURRENT_TIMESTAMP,
						'". $_SESSION['user_account_id'] . "'
					)
				";
			$_ops = "เปิด";
		}else{
			$_sql_Operation = "
				DELETE FROM `curriculum_register`
				WHERE
					`curriculum_id` = 	'" . $_POST['curriculum_id'] . "' AND
					`register_id`	=	'" . $_POST['register_id'] . "' 
				";
			$_ops = "ยกเลิก";
		}

		

		$_res = mysqli_query($_connection,$_sql_Operation);
		if($_res){
			$_processing_text  = "ระบบได้" .$_ops . "ให้ลงทะเบียนเรียนหลักสูตร: ";
			$_processing_text .= $_POST['curriculum_name'] . " ภาคเรียนที่ " . $acadsemester . " ปีการศึกษา " . $acadyear . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "แผนการเรียน: " . $_POST['curriculum_name'] . " ";
			$_text .= ($_ops=="เปิด"?"เปิดให้ลงทะเบียน":"ยกเลิกการลงทะเบียน"). " ภาคเรียนที่ " . $acadsemester . " ปีการศึกษา " . $acadyear . " เรียบร้อยแล้ว";
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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึก ประมวลผลการเปิด/ยกเลิก การลงทะเบียนเรียนหลักสูตรได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึก <b>หลักสูตรที่ต้องการลงทะเบียน</b> ซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ - " . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "เกิดข้อผิดพลาด ไม่สามารถระบุสาเหตุได้ ขอให้แจ้งผู้ดูแลระบบ";
	}

?>


<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_curriculum/index">	
						<img src="../images/graduation.png" alt="" width="48" height="48" />
				</a>
				</td>
				<td width="64%"><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>4.1 จัดการหลักสูตรสำหรับลงทะเบียนเรียน</strong></font></span>
				</td>
				<td >
					<?php
						if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
						if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
					?>
					ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_curriculum/CurriculumRegisterMapping&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_curriculum/CurriculumRegisterMapping&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/CurriculumRegisterMapping&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_curriculum/CurriculumRegisterMapping&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
					?>
				</td>
			</tr>
	</table>
  
	<?php


		$_sqlCurr = "
		SELECT
			c.curriculum_id,
			r.curriculum_id as registered_curriculumn,
			c.curriculum_code,
			c.curriculum_start_year,
			c.curriculum_name,
			c.curriculum_status,
			c.curriculum_level,
			r.acadyear,
			r.acadsemester,
			r.register_id
		FROM
			curriculums c LEFT JOIN curriculum_register r 
			ON (
					c.curriculum_id = r.curriculum_id and 
					r.acadyear      = '" . $acadyear . "' and
					r.acadsemester  = '" . $acadsemester . "' 
				)
			
		ORDER BY
			c.curriculum_level,
			c.curriculum_code
		";

		$_resC = mysqli_query($_connection,$_sqlCurr);
		$_rowC = mysqli_num_rows($_resC);
	?>

	<form method="post" action="">
		<div align="center">
			<? if($_rowC >0){ ?> 
				<table class="admintable">
					<tr height="35px">
						<td class="key" colspan="10"> &nbsp; หลักสูตรเปิดให้ลงทะเบียนเรียนภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?> </td>
					</tr>
					<tr height="35px"> 
						<td class="key" width="25px" align="center">-</td>
						<td class="key" width="90px" align="center">รหัสหลักสูตร</td>
						<td class="key" width="300px" align="center">ชื่อหลักสูตร (แผนการเรียน)</td>
						<td class="key" width="95px" align="center">ระดับการศึกษา</td>
						<td class="key" width="60px" align="center">ปีที่เริ่มใช้</td>
						<td class="key" width="80px" align="center">สถานะ<br/>หลักสูตร</td>
						<td class="key" width="120px" align="center">สถานะการเปิด<br/>ลงทะเบียนเรียน</td>
						<td class="key" align="center">-</td>
					</tr>
					<? while($_dat = mysqli_fetch_assoc($_resC)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center">
								<? $_i=0; ?>
								<? $_select = ($_dat['curriculum_id']==$_dat['registered_curriculumn']?"checked":""); ?>
								<form method="post" action="">
									<input type="checkbox" name="mapping" value="10"
										onChange="this.form.submit();"; <?=$_select?> />
									<input type="hidden" name="curriculum_id" value="<?=$_dat['curriculum_id']?>" />
									<input type="hidden" name="curriculum_code" value="<?=$_dat['curriculum_code']?>" />
									<input type="hidden" name="curriculum_name" value="<?=$_dat['curriculum_name']?>" />
									<input type="hidden" name="register_id"		value="<?=$_dat['register_id']?>" />
								</form>
							</td>
							<td align="center"><?=$_dat['curriculum_code']?></td>
							<td><?=$_dat['curriculum_name']?></td>
							<td align="center"><?=displayEducationLevel($_dat['curriculum_level'])?></td>
							<td align="center"><?=$_dat['curriculum_start_year']?></td>
							<td align="center"><?=$_dat['curriculum_status']?></td>
							<td align="center">
								<?=($_dat['registered_curriculumn']!=""?"<font color='green'>อนุญาตลงทะเบียน</font>":"ไม่เปิดลงทะเบียน")?>
							</td>
						</tr>
					<? } ?>
				</table>
			<? } else { 
					echo "<br/><br/>";
					echo "<font color='red'> ไม่พบหลักสูตร (แผนการเรียน) ที่สามารถเปิดลงทะเบียนเรียนได้ ขอให้กลับไปตรวจสอบหลักสูตรที่เปิดสอนอีกครั้ง </font>";
					echo "<br/><br/>";
			} ?>
		</div>
	</form>

</div>

