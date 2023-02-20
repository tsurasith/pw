<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	$_continue_processing = false;

	$_sql_club = "";
	$_sql_update_mapping = "";
	

	if(isset($_POST['ConfirmEdit']) && trim($_POST['club_name']) != "" ){

		$_sql_club = "
					UPDATE
						`curriculum_clubs`
					SET
						`club_name` 		= '" . trim($_POST['club_name']) ."',
						`club_description`	= '" . trim($_POST['club_description']) ."',
						`club_status`       = '" . trim($_POST['club_status']) ."',
						`club_level`        = '" . trim($_POST['club_level']) ."',
						`updated_datetime` 	= CURRENT_TIMESTAMP,
						`updated_user` 		= '" . $_SESSION['user_account_id'] ."'
					WHERE
						`club_code` = '" . trim($_POST['club_code']) ."'
				";

		$_res = mysqli_query($_connection,$_sql_club);
			
		if($_res){
			$_processing_text = "แก้ไขชุมนุม " . trim($_POST['club_name']) . "เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "ชุมนุม: ";
			$_text .= trim($_POST['club_name']) . " ถูกแก้ไขเรียบร้อยแล้ว ";
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
			//header("Location: index.php?option=module_curriculum/CreateCurriculum");
		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถแก้ไข ชุมนุม ได้" . "<br/>";
			$_processing_text .= "แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}
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
					<span class="normal"><font color="#0066FF"><strong>3.6 เพิ่ม/แก้ไข ชุมนุม - แก้ไขชุมนุม</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					</font>
				</td>
			</tr>
	</table>
  
	<div align="center">
		<?php
			$_sql_cur = "select * from curriculum_clubs where club_code = '" . $_POST['club_code'] . "'";
			$_res_curr = mysqli_query($_connection,$_sql_cur);
			$_rows = mysqli_num_rows($_res_curr);
		?>
		<? if($_rows>0){  ?>
			<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
				<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
					<tr> 
					<th colspan="7" align="center">
						<font color='purple'>	
						ข้อมูลชุมนุมที่ต้องการแก้ไข
						<br/>
						ท่านสามารถแก้ไขข้อมูลชุมนุมได้จากรายการด้านล่าง<br/><br/>
					</th>
					</tr>
				<tr height="35px"> 
					<td class="key" width="80px" align="center">รหัสชุมนุม</td>
					<td class="key" width="280px" align="center">ชื่อชุมนุม</td>
					<td class="key" width="90px" align="center">ระดับที่เปิดสอน</td>
					<td class="key" width="90px" align="center">สถานะ</td>
					<td class="key" width="160px" align="center">แก้ไขล่าสุด</td>
				</tr>
					
						<tr>
							<td align="center"><?=$_dat['club_code']?></td>
							<td><?=$_dat['club_name']?></td>
							<td align="center">
								<?php
									if($_dat['club_level']=="0"){
										echo "ทุกระดับชั้น";
									}else{
										echo displayEducationLevel($_dat['club_level']);
									}
								?>
							</td>
							<td align="center"><?=$_dat['club_status']=="ACTIVE"?"เปิดสอน":"หยุดทำการสอน"?></td>
							<td align="center"><?=$_dat['updated_datetime']!=""?$_dat['updated_datetime']:$_dat['created_datetime']?></td>
					</tr>
				</table>

				<div align="center">
					<br/><br/><br/>
					<form action="" method="post" autocomplete="off">
						<table class="admintable">
							<tr height="35px">
								<td class="key" colspan="2"> &nbsp; ส่วนแก้ไขชุมนุม</td>
							</tr>
							<tr>
								<td align="right" width="200px"> รหัสชุมนุม </td>
								<td> <b><?=$_dat['club_code']?> </b> </td>
							</tr>
							<tr>
								<td align="right"> ระดับที่เปิดสอน </td>
								<td> 
								<select name="club_level" class="inputboxUpdate">
									<option value="0" <?=$_dat['club_level']==0?"selected":""?> >ทุกระดับชั้น</option>
									<option value="3" <?=$_dat['club_level']==3?"selected":""?> >มัธยมศึกษาตอนต้น</option>
									<option value="4" <?=$_dat['club_level']==4?"selected":""?> >มัธยมศึกษาตอนปลาย</option>
								</select>
								</td>
							</tr>
							<tr>
								<td  align="right"> ชื่อชุมนุม </td>
								<td> <input type="text" name="club_name" value="<?=$_dat['club_name']?>" size="50" class="inputboxUpdate" maxlength="150" /> </td>
							</tr>
							<tr>
								<td align="right"> สถานะชุมนุม </td>
								<td> 
									<select name="club_status" class="inputboxUpdate">
										<option <?=$_dat['club_status']=="ACTIVE"?"selected":""?> value="ACTIVE">เปิดสอน</option>
										<option <?=$_dat['club_status']=="CANCEL"?"selected":""?> value="CANCEL">หยุดทำการสอน</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right" valign="top"> คำอธิบายชุมนุม </td>
								<td><textarea name="club_description" rows="4" cols="75"><?=$_dat['club_description']?></textarea></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><br/>
									<?php
										if(isset($_POST['ConfirmEdit'])){
											if($_processing_result){
												echo "<font color='green'>";
												echo $_processing_text;
												echo "</font>";
											}else{
												echo "<font color='red'>";
												echo $_processing_text;
												echo "</font>";
											}
										}
									?>
									<br/><br/>
									<input type="hidden" name="club_code" value="<?=$_dat['club_code']?>"/>
									<input type="submit" name="ConfirmEdit" class="button" value="แก้ไข" /> 
									<input type="button" name="cancel" class="button" value="กลับ" onclick="location.href='index.php?option=module_curriculum/CreateClub';"/>
								</td>
							</tr>
						</table>
					</form>
				</div>
			<? } // end while?>




		<? } else { ?>
				<div align='center'><font color='red'><br/><br/>ยังไม่มีการบันทึกชุมนุม</font></div>
		<? } ?>
	</div>

</div>

