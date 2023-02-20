<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	if(isset($_POST['addnew']) && trim($_POST['club_name']) != "" ){

		$_sql_insert = "
			INSERT INTO `curriculum_clubs`(
				`club_level`,
				`club_name`,
				`club_description`,
				`club_status`,
				`created_datetime`,
				`created_user`,
				`updated_datetime`,
				`updated_user`
			)
			VALUES(
				'". $_POST['club_level'] . "',
				'". trim($_POST['club_name']). "',
				'". trim($_POST['club_description']). "',
				'". $_POST['club_status']. "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "'
			)
		";

		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res){
			$_processing_text = "บันทึกข้อมูล ชุมนุม" . trim($_POST['club_name']) . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "ชุมนุม: ";
			$_text .= trim($_POST['club_name']) . " ถูกเพิ่มเข้าในระบบแล้ว";
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
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกเพิ่มชุมนุมได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}

	}else{
		$_processing_text = "การป้อนข้อมูลบันทึกไม่ถูกต้อง ท่านอาจจะไม่ได้ระบุให้ครบถ้วน กรุณาตรวจสอบอีกครั้ง";
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
					<span class="normal"><font color="#0066FF"><strong>3.6 เพิ่ม/แก้ไข ชุมนุม</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<br/>
					<input type="button" value="โหลดดูข้อมูลล่าสุด" onclick="location.href='index.php?option=module_curriculum/CreateClub';" />
				</td>
			</tr>
	</table>
  
	<div align="center">
		<?php
			$_sql_cur = "
					select
						c.club_code,
						c.club_name,
						c.club_level,
						c.club_status,
						c.created_datetime,
						c.updated_datetime,
						count(r.club_code) as 'total_register_club'
					from
						curriculum_clubs c left join register_clubs r
						on (c.club_code = r.club_code)
					group by
						c.club_code
					order by
						convert(c.club_name using tis620),
						c.club_code	
				";
			$_res_curr = mysqli_query($_connection,$_sql_cur);
			$_rows = mysqli_num_rows($_res_curr);
			$_no = 1;
		?>
		<? if($_rows>0){  ?>
			<table class="admintable">
				<tr> 
				<th colspan="10" align="center">
						<img src="../images/school_logo.png" width="120px"><br/>
						รายชื่อชุมนุม โรงเรียนเพชรวิทยาคาร <br/><br/>
				</th>
				</tr>
				<tr height="35px"> 
					<td class="key" width="30px" align="center">ที่</td>
					<td class="key" width="80px" align="center">รหัสชุมนุม</td>
					<td class="key" width="240px" align="center">ชื่อชุมนุม</td>
					<td class="key" width="115px" align="center">ระดับการศึกษา</td>
					<td class="key" width="90px" align="center">สถานะ</td>
					<td class="key" width="160px" align="center">แก้ไขล่าสุด</td>
					<td class="key" colspan="2" align="center">-</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
					<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
						<td align="center"><?=$_no++?></td>
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
						<td align="center">
							<form action="index.php?option=module_curriculum/ModifyClub" method="post">
								<input type="hidden" name="club_code" value="<?=$_dat['club_code']?>"/>
								<input type="hidden" name="club_name" value="<?=$_dat['club_name']?>"/>
								<input type="hidden" name="club_level" value="<?=$_dat['club_level']?>"/>
								<input type="submit" name="edit" value="แก้ไข"/>
							</form>
						</td>
						<td align="center">
							<?
								
								$_disable_text = "";
								
								if($_dat['total_register_club']!="0"){
									//$_disable_text = "";
									$_disable_text = "disabled";
								}
								
							?>
							<form action="index.php?option=module_curriculum/DeleteClub" method="post">
								<input type="hidden" name="club_code" value="<?=$_dat['club_code']?>"/>
								<input type="hidden" name="club_name" value="<?=$_dat['club_name']?>"/>
								<input type="hidden" name="club_level" value="<?=$_dat['club_level']?>"/>
								<input type="hidden" name="total_register_club" value="<?=$_dat['total_register_club']?>"/>
								<input type="submit" name="delete" value="ลบ" <?=$_disable_text?> />
							</form>	
						</td>
					</tr>
				<? } ?>
			</table>
		<? } else { ?>
				<div align='center'><font color='red'><br/><br/>ยังไม่มีการบันทึกชุมนุมใดๆ </font></div>
		<? } ?>
	</div>

	<div align="center">
		<br/><br/><br/>
		<form action="" method="post" autocomplete="off">
			<table class="admintable">
				<tr height="35px">
					<td class="key" colspan="2"> &nbsp; ส่วนเพิ่มชุมนุมใหม่</td>
				</tr>
				<tr>
					<td align="right"> ระดับการศึกษา </td>
					<td> 
						<select name="club_level" class="inputboxUpdate">
							<option value="0">ทุกระดับชั้น</option>
							<option value="3">มัธยมศึกษาตอนต้น</option>
							<option value="4">มัธยมศึกษาตอนปลาย</option>
						</select>
					</td>
				</tr>
				<tr>
					<td  align="right"> ชื่อชุมนุม </td>
					<td> <input type="text" name="club_name" size="50" class="inputboxUpdate" maxlength="150" /> </td>
				</tr>
				<tr>
					<td align="right"> สถานะแผนการเรียน </td>
					<td> 
						<select name="club_status" class="inputboxUpdate">
							<option value="ACTIVE">เปิดสอน</option>
							<option value="CANCEL">หยุดทำการสอน</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"> คำอธิบายชุมนุม </td>
					<td><textarea name="club_description" rows="4" cols="75"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><br/>
						<?php
							if(isset($_POST['addnew'])){
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
						<input type="submit" name="addnew" class="button" value="เพิ่ม" /> 
					</td>
				</tr>
			</table>
		</form>
	</div>


</div>

