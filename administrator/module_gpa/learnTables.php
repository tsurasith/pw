
<?php
	if(isset($_POST['delete']))
	{
		$_sqlDel = "
			DELETE from teaching_schedule
			WHERE
				teacher_id = '" . $_POST['teacher_id'] . "' and 
				SubjectCode = '" . $_POST['subject_code'] . "' and 
				weekday = '" . $_POST['weekday'] . "' and 
				period = '" . $_POST['period'] . "' and 
				acadyear = '" . $_POST['acadyear'] . "' and 
				acadsemester = '" . $_POST['acadsemester'] . "' ";
		//echo $_sqlDel . "<br/>";
		$resDel = mysqli_query($_connection,$_sqlDel);


		$_sqlRemove = " UPDATE teaching_schedule_display ";

		switch($_POST['period']){
			case "1": $_sqlRemove = " UPDATE teaching_schedule_display SET period1 = '' "; break;
			case "2": $_sqlRemove = " UPDATE teaching_schedule_display SET period2 = '' "; break;
			case "3": $_sqlRemove = " UPDATE teaching_schedule_display SET period3 = '' "; break;
			case "4": $_sqlRemove = " UPDATE teaching_schedule_display SET period4 = '' "; break;
			case "5": $_sqlRemove = " UPDATE teaching_schedule_display SET period5 = '' "; break;
			case "6": $_sqlRemove = " UPDATE teaching_schedule_display SET period6 = '' "; break;
			case "7": $_sqlRemove = " UPDATE teaching_schedule_display SET period7 = '' "; break;
			case "8": $_sqlRemove = " UPDATE teaching_schedule_display SET period8 = '' "; break;
			default : $_sqlRemove = "";
		}

		$_sqlRemove .= "
			WHERE
				teacher_id = '" . $_POST['teacher_id'] . "' and 
				weekday = '" . $_POST['weekday'] . "' and 
				acadyear = '" . $_POST['acadyear'] . "' and 
				acadsemester = '" . $_POST['acadsemester'] . "'
		";

		//echo $_sqlRemove;
		if($resDel){
			$resRemove = mysqli_query($_connection,$_sqlRemove);
		}
		//echo "hello";
	}
?>



  <?php
		$_addnew_result = "";
		$_roomID = "";
	  	$_roomID = isset($_POST['roomID'])?$_POST['roomID']:"";


		$xlevel = getXlevel($_roomID);
		$xyearth= getXyearth($_roomID);
		$room = getRoom($_roomID);

		if(isset($_POST['addnew'])){
			$_sql = "INSERT INTO `teaching_schedule`(
				`teacher_id`,
				`SubjectCode`,
				`weekday`,
				`period`,
				`location`,
				`acadyear`,
				`acadsemester`,
				`acadslevel`,
				`level`,
				`room`,
				`created_datetime`,
				`created_user`
			)
			VALUES(
				'" . $_POST['teacher_id'] . "',
				'" . trim($_POST['subject_code']) . "',
				'" . $_POST['weekday'] . "',
				'" . $_POST['period'] . "',
				'" . trim($_POST['location']) . "',
				'" . $acadyear . "',
				'" . $acadsemester . "',
				'" . $xlevel . "',
				'" . substr($_POST['roomID'],0,1) . "',
				'" . $room . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "'
			) ";

			$_resAddnew = mysqli_query($_connection,$_sql);
			if($_resAddnew){
				$_addnew_result = "<font color='green'>เพิ่มวิชาที่สอนเรียบร้อยแล้ว</font>";

				        /*
						event_log($_connection,
						$_event_action_type_code,
						$_event_module_code,
						$_event_task_code,
						$_event_key,  -- $_event_key = hash("sha256",$_event_details);
						$_event_details,
						$_event_user_id,$acadyear,$acadsemester)
						*/

				$_key = $_POST['teacher_id'].trim($_POST['subject_code']).$_POST['weekday'].$_POST['period'].$_POST['roomID'].$acadyear.$acadsemester;
				
				$_event_details = "";
				$_event_details .= "บันทึกข้อมูลตารางสอน วิชา " . trim($_POST['subject_code']) ;
				$_event_details .= " ห้อง " . substr($_POST['roomID'],0,1) . '/' .$room . " สอนวัน" . displayDayofWeek($_POST['weekday']) ;
				$_event_details .= " คาบเรียนที่ " . $_POST['period'];
				
				$_event_key = hash("sha256",$_key);
	
				$_event_user_id = $_SESSION['user_account_id'];
				if(checkDuplicateEventLog($_connection,$_event_key)){
					event_log($_connection,1,2,3,
					$_event_key,
					$_event_details,
					$_event_user_id,$acadyear,$acadsemester);
				}


			}else{
				$_addnew_result = "<font color='red'>ไม่สามารถเพิ่มรายวิชาที่สอนได้เนื่องจาก <br/>" . mysqli_error($_connection) . "</font>";
			}
		}
  ?>


<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.2 จัดการรายวิชาที่สอน</strong></font></span></td>
      <td >
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

			if(isset($_POST['acadyear'])) { $acadyear = (int)$_POST['acadyear']; }
			if(isset($_POST['acadsemester'])) { $acadsemester = (int)$_POST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_gpa/learnTables&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_gpa/learnTables&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/learnTables&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/learnTables&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
		<br/>

		<font  size="2" color="#000000">เลือกครูผู้สอน
			<?php 
					$sql_teacher = " 
						select 
							u.user_account_prefix,
							u.user_account_firstname,
							u.user_account_lastname,
							u.user_account_id,
							count(t.SubjectCode) as c
						from 
							users_account u 
							left join teaching_schedule t on (u.user_account_id = t.teacher_id)
						group by 
							u.user_account_id,
							u.user_account_prefix,
							u.user_account_firstname,
							u.user_account_lastname
						order by convert(u.user_account_firstname using tis620) ";
					//echo $sql_Room ;
					$resTeacher = mysqli_query($_connection,$sql_teacher);	
					$_submit_teacher_name = "";		
			?>
			<select name="teacher_id" class="inputboxUpdate">
				<option value=""></option>
				<?php

					while($dat = mysqli_fetch_assoc($resTeacher))
					{
						$_select = (isset($_POST['teacher_id'])&&$_POST['teacher_id'] == $dat['user_account_id']?"selected":"");
						echo "<option value=\"" . $dat['user_account_id'] . "\" $_select>";
						echo $dat['user_account_prefix'].$dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $dat['c'] . ')';
						echo "</option>";
						if($_select == "selected"){
							$_submit_teacher_name = $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'];
						}
					}
					
				?>
			</select>
			<input type="hidden" value="<?=$_submit_teacher_name?>" name="teacher_name" />
	  		<input type="submit" value="สืบค้น" class="button" name="search"/> <br/>
			</font>
		<br/>
	   </td>
    </tr>
  </table>
  </form>
  
<? if(isset($_POST['search']) && $_POST['teacher_id'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือก ครูผู้สอนก่อน !</font></center>
<? } else if (isset($_POST['search']) && $_POST['teacher_id'] != "") { ?>
<div align="center">
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="21" align="center">
	  		<!--<img src="../images/school_logo.png" width="120px"><br/> -->
			ตารางสอน ของคุณครู<?=$_submit_teacher_name?> <br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
    <tr height="35px"> 
		<? $_width = "90px"; ?>
      	<td class="key" width="100px" align="center">วัน/คาบเรียน</td>
		<td class="key" width="<?=$_width?>" align="center">1</td>
		<td class="key" width="<?=$_width?>" align="center">2</td>
		<td class="key" width="<?=$_width?>" align="center">3</td>
		<td class="key" width="<?=$_width?>" align="center">4</td>
		<td class="key" width="<?=$_width?>" align="center">พักเที่ยง</td>
		<td class="key" width="<?=$_width?>" align="center">5</td>
		<td class="key" width="<?=$_width?>" align="center">6</td>
		<td class="key" width="<?=$_width?>" align="center">7</td>
		<td class="key" width="<?=$_width?>" align="center">8</td>
    </tr>
	<?php


		$_sqlTable = "select * from teaching_schedule_display where teacher_id = '" . $_POST['teacher_id'] . "' order by weekday";
		$_resTable = mysqli_query($_connection,$_sqlTable);
	?>
	<? while($_dat = mysqli_fetch_assoc($_resTable)) { ?>
	<tr>
		<td align="center" class="key"><?=displayDayName($_dat['day_name'])?></td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=1&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period1']?>">
				<?=displayTeachingSchedule($_dat['period1'])?>
			</a>
		</td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=2&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period2']?>">
				<?=displayTeachingSchedule($_dat['period2'])?>
			</a>
		</td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=3&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period3']?>">
				<?=displayTeachingSchedule($_dat['period3'])?>
			</a>
		</td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=4&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period4']?>">
				<?=displayTeachingSchedule($_dat['period4'])?>
			</a>
		</td>
		<td> </td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=5&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period5']?>">
				<?=displayTeachingSchedule($_dat['period5'])?>
			</a>
		</td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=6&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period6']?>">
				<?=displayTeachingSchedule($_dat['period6'])?>
			</a>
		</td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=7&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period7']?>">
				<?=displayTeachingSchedule($_dat['period7'])?>
			</a>
		</td>
		<td align="center">
			<a href="index.php?option=module_gpa/deleteSchedule&teacher_id=<?=$_dat['teacher_id']?>&period=8&weekday=<?=$_dat['weekday']?>&acadyear=<?=$_dat['acadyear']?>&acadsemester=<?=$_dat['acadsemester']?>&subject=<?=$_dat['period8']?>">
				<?=displayTeachingSchedule($_dat['period8'])?>
			</a>
		</td>
	</tr>
	<? } ?>
</table>
</div>
  <? } //end else-if ?>



	<div align="center">
		<br/><br/><br/>
		<form action="" method="post" autocomplete="off">
			<table class="admintable" width="400px">
				<tr height="35px">
					<td class="key" colspan="2"> &nbsp; ส่วนเพิ่มรายวิชาที่สอน</td>
				</tr>
				<tr>
					<td align="right" width="100px"> รหัสวิชา </td>
					<td> <input type="text" name="subject_code" size="10" class="inputboxUpdate" maxlength="6" /> </td>
				</tr>
				<tr>
					<td align="right"> เลือกห้องเรียน </td>
					<td> 
						<?php
							$sql_Room = "select room_id from rooms where acadyear = '". $acadyear . "' and acadsemester = '" . $acadsemester . "'  order by room_id";
							$resRoom = mysqli_query($_connection,$sql_Room);
						?>
						<select name="roomID" class="inputboxUpdate">
							<option value="000">สอนรวม</option>
							<?php

								while($dat = mysqli_fetch_assoc($resRoom))
								{
									$_select = (isset($_POST['roomID'])&&$_POST['roomID'] == $dat['room_id']?"selected":"");
									echo "<option value=\"" . $dat['room_id'] . "\" $_select>";
									echo getFullRoomFormat($dat['room_id']);
									echo "</option>";
								}
								
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td  align="right"> เลือกวัน </td>
					<td> 
						<select name="weekday" class="inputboxUpdate">
							<option value="1">จันทร์</option>
							<option value="2">อังคาร</option>
							<option value="3">พุธ</option>
							<option value="4">พฤหัสบดี</option>
							<option value="5">ศุกร์</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> คาบเรียน </td>
					<td> 
					<select name="period" class="inputboxUpdate">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"> สถานที่สอน </td>
					<td> <input type="text" name="location" size="10" class="inputboxUpdate" maxlength="10" /> </td>
				</tr>
				<tr>
					<td colspan="2" align="center"><br/><?=$_addnew_result?><br/><br/>
						<input type="hidden" name="search" value="ค้นหา" />
						<input type="hidden" name="teacher_id" value="<?=$_POST['teacher_id']?>" />
						<input type="hidden" value="<?=$_submit_teacher_name?>" name="teacher_name" />
						<input type="submit" name="addnew" class="button" value="เพิ่ม" /> 
					</td>
				</tr>
			</table>
		</form>
	</div>


</div>

<?php
	function displayTeachingSchedule($_text){
		// text = รหัสวิชา|ห้องเรียน|สถานที่สอน
		$_result = "";

		if(trim($_text)!=""){
			$dat = explode("|",$_text);
			$_result .= "<b>" . $dat[0] . "</b><br/>";
			$_result .= $dat[1] . "<br/>";
			$_result .= $dat[2] . "<br/>";
		}
		return $_result;
	}

	function displayDayName($_text){
		
		switch($_text){
			case "monday" : return "จันทร์"; break;
			case "tuesday" : return "อังคาร"; break;
			case "wednesday" : return "พุธ"; break;
			case "thursday" : return "พฤหัสบดี"; break;
			case "friday" : return "ศุกร์"; break;
			default : return "n/a";
		}
	}


	function displayDayofWeek($_text){
		
		switch($_text){
			case "1" : return "จันทร์"; break;
			case "2" : return "อังคาร"; break;
			case "3" : return "พุธ"; break;
			case "4" : return "พฤหัสบดี"; break;
			case "5" : return "ศุกร์"; break;
			default : return "n/a";
		}
	}
?>

