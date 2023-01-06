
<?php
	if(isset($_POST['delete']))
	{
		$delSql = "delete from student_learn_task where task_date = '" . $_POST['delete'] . "'" ;
		$resDel = mysqli_query($_connection,$delSql);
		if($resDel)
		{
			$_text = "";
			$_text .= "งานบันทีกการเข้าเรียนของนักเรียน วันที่ " . displayFullDate($_POST['delete']) . " ";
			$_text .= "ถูกลบแล้ว\n" . "โดย - " . $_SESSION['shortname'];

			$message = $_text;
			SendLineMessage($message,$_line_token);

			$_event_details = "";
			$_event_details .= "งานบันทีกการเข้าเรียนของนักเรียน วันที่ " . displayFullDate($_POST['delete']);

			$_event_key = hash("sha256",$_event_details.date("H:i:s"));

			$_event_user_id = $_SESSION['user_account_id'];
			if(checkDuplicateEventLog($_connection,$_event_key)){
				event_log($_connection,3,2,4,
				$_event_key,
				$_event_details,
				$_event_user_id,$acadyear,$acadsemester);
			}
			header("Location: index.php?option=module_learn/dateTaskCreated");
		}
	}
?>
<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr> 
		<td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
		<td ><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>1.2 ตรวจสอบวันที่สร้างงานบันทึกแล้ว >> รายละเอียดการบันทึก</strong></font></span></td>
		<td >
			<input type="button" value="ย้อนกลับ" onclick="location.href='index.php?option=module_learn/dateTaskCreated'" />
		</td>
		</tr>
	</table>

	<div align="center">
		<?php
			$_sign = false;
			$_total_rows_follow = 0;
			$_sql_follow = "
			select 
				t.teacher_id,
				c.firstname,
				t.SubjectCode,
				l.task_roomid,
				l.task_status,
				l.period
			from
				student_learn_task l left join teaching_schedule t 
				on 
				(
					l.weekday = t.weekday and
					l.acadyear = t.acadyear and
					l.acadsemester = t.acadsemester and
					l.period = t.period and 
					l.task_roomid = t.room_id and
					l.task_date = '" . $_REQUEST['date'] . "'
				)
				left join teachers c on (t.teacher_id = c.teacher_id)
			where
				1=1
				and l.task_date = '" . $_REQUEST['date'] . "'
			order by
				l.task_roomid,l.period
		
			";
			$_res_follow = mysqli_query($_connection,$_sql_follow);
			$_total_rows_follow = mysqli_num_rows($_res_follow);

			$_data_follow = array();
			$_i=0;

			while($_dat = mysqli_fetch_assoc($_res_follow)){
				$_data_follow[$_i] = $_dat;
				$_i++;
			}

			//print_r($_data_follow);

		?>
		
		<? if($_total_rows_follow>0){ ?> 
			<form method="post" action="">
				<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
					<tr> 
					<th colspan="10" align="center">
							<img src="../images/school_logo.png" width="120px"><br/>
							รายละเอียดการบันทึกการเข้าเรียน<br/>
							วัน <?=displayDayOfWeek(date('w',strtotime($_REQUEST['date'])))?> ที่ <?=displayFullDate($_REQUEST['date'])?> 
							ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
					</th>
					</tr>
					<tr> 
						<td class="key" width="85px" align="center" rowspan="2">ห้อง</td>
						<td class="key" align="center" colspan="9">คาบเรียน</td>
					</tr>
					<tr>
						<td class="key" width="80px" align="center">1</td>
						<td class="key" width="80px" align="center">2</td>
						<td class="key" width="80px" align="center">3</td>
						<td class="key" width="80px" align="center">4</td>
						<td class="key" width="50px" align="center">พักเที่ยง</td>
						<td class="key" width="80px" align="center">5</td>
						<td class="key" width="80px" align="center">6</td>
						<td class="key" width="80px" align="center">7</td>
						<td class="key" width="80px" align="center">8</td>
					</tr>
					<? 
						$_hover = "onMouseOver=\"this.style.backgroundColor='#FFEFFF'; this.style.cursor='hand';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF'\"";
						$_room_initial = "000";
						$_period_initial = "0";

						for($_i=0;$_i<$_total_rows_follow;$_i++){
							if($_data_follow[$_i]['task_roomid'] != $_room_initial){
								echo "<tr $_hover height='60px'>";
								$_room_initial = $_data_follow[$_i]['task_roomid'];
								echo "<td align='center' valign='top'>";
								echo getFullRoomFormat($_data_follow[$_i]['task_roomid']);
								echo "</td>";
							}

							if($_data_follow[$_i]['period'] != $_room_initial && @$_data_follow[$_i-1]['period']!=$_data_follow[$_i]['period']) { 
								echo "<td align='center' valign='top'>";
								$_period_initial = $_data_follow[$_i];

								//echo $_data_follow[$_i]['period'] . "<br/>";

								if($_data_follow[$_i]['task_status']=="1"){
									echo "<font color='green'>บันทึกแล้ว</font><br/>";
									$_sign = true;
								}else{
									echo "<font color='red'>ยังไม่บันทึก</font><br/>";
								}
								echo $_data_follow[$_i]['SubjectCode'] . "<br/>";
								echo $_data_follow[$_i]['firstname']   . "<br/>";
							}else{
								echo "<br/>";
								echo $_data_follow[$_i]['SubjectCode'] . "<br/>";
								echo $_data_follow[$_i]['firstname']   . "<br/>";
							}
							
							if(@$_data_follow[$_i+1]['period']!=$_data_follow[$_i]['period']){
								echo "</td>";
							}

							
							if(@$_data_follow[$_i]['period']=="4" && $_data_follow[$_i+1]['period']=="5"){
								echo "<td></td>";
							}
							

							if($_i+1 == $_total_rows_follow){
								echo "</tr>";
							} else if($_data_follow[$_i+1]['task_roomid'] != $_data_follow[$_i]['task_roomid']){
								echo "</tr>";
							}
						} 
					?>
					<tr>
						<td class="key" colspan="10" align="center" valign="center" height="35px">
							<?php
								if($_sign == false)
								{
									echo "<input type=\"hidden\" value=\"" . $_REQUEST['date'] . "\" name=\"delete\" />";
									echo "<input type=\"submit\" value=\"ลบ\" class=\"button\" /> งานบันทึกข้อมูลวันนี้";
								}
							?>
						</td>
					</tr>
				</table>
			</form>
		<? } ?>
	</div>
</div>
