
<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear'])) { $acadyear = $_POST['acadyear']; }
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester']; }
?>


<?php
	if(isset($_POST['delete']))
	{
		$_sqlDel = "
			DELETE from teaching_schedule
			WHERE
				teacher_id   = '" . $_POST['teacher_id'] . "' and 
				weekday      = '" . $_POST['weekday'] . "' and 
				period       = '" . $_POST['period'] . "' and 
				acadyear     = '" . $_POST['acadyear'] . "' and 
				acadsemester = '" . $_POST['acadsemester'] . "' and
				SubjectCode  = '" . $_POST['subject_code'] . "'";
		//echo $_sqlDel . "<br/>";
		$resDel = mysqli_query($_connection,$_sqlDel);

		//$message = $_text;
		//SendLineMessage($message,$_line_token);

	}
?>



  <?php
		$_addnew_result = "";
		$_roomID = "";

		$xlevel  = "";
		$xyearth = "";
		$room    = "";

		if(isset($_POST['addnew']) && $_POST['subject_info'] != ""){

			$_subject = explode("|",$_POST['subject_info']);
			$_roomID  = $_subject[0];
			$xlevel   = getXlevel($_roomID);
			$xyearth  = getXyearth($_roomID);
			$room     = getRoom($_roomID);

			if(trim($_subject[1])==""){
				$_addnew_result = "<font color='red'>ไม่สามารถเพิ่มรายวิชาที่สอนได้ กรุณาใส่รหัสวิชาให้ถูกต้อง</font>";
			}else {

				$_existing = 0;
				$_sqlExisting = "SELECT * FROM teaching_schedule 
							WHERE
								teacher_id       = '" . $_POST['teacher_id'] . "'
								and weekday      = '" . $_POST['weekday'] . "'
								and period       = '" . $_POST['period'] . "'
								and acadyear 	 = '" . $acadyear . "'
								and acadsemester = '" . $acadsemester ."' 
								and teacher_id   = '" . $_POST['teacher_id'] . "' 
								and SubjectCode  = '" . trim($_subject[1]) . "' 
							";
				$_resExisting = mysqli_query($_connection,$_sqlExisting);
				$_existing = mysqli_num_rows($_resExisting);
				if($_existing > 0){

					$_addnew_result  = "<font color='red'>";
					$_addnew_result .= "ไม่สามารถบันทึกได้เนื่องจาก " . "ปีการศึกษา " . $acadyear . " ภาคเรียนที่ " . $acadsemester . "<br/> ";;
					$_addnew_result .= "วัน" . displayDayofWeek($_POST['weekday']);
					$_addnew_result .= " คาบเรียนที่ " . $_POST['period'] . " มีคาบสอนอยู่ก่อนแล้ว หากต้องการเพิ่มให้ทำการลบ วิชาที่สอนออกจากตารางก่อน";
					$_addnew_result .= "</font>";
					
				}
				else{

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
						`room_id`,
						`club_code`,
						`created_datetime`,
						`created_user`
					)
					VALUES(
						'" . $_POST['teacher_id'] . "',
						'" . trim($_subject[1]) . "',
						'" . $_POST['weekday'] . "',
						'" . $_POST['period'] . "',
						'" . trim($_POST['location']) . "',
						'" . $acadyear . "',
						'" . $acadsemester . "',
						'" . $xlevel . "',
						'" . substr($_roomID,0,1) . "',
						'" . $room . "',
						'" . $_subject[0] . "',
						'" . $_subject[2] . "',
						CURRENT_TIMESTAMP,
						'" . $_SESSION['user_account_id'] . "'
					) ";
	
					$_resAddnew = mysqli_query($_connection,$_sql);
	
					if($_resAddnew){

						// update student_learn && student_learn_task
						if(substr($_roomID,-2) != "00"){
							$_sql_student_learn = "
								update student_learn
								set
									SubjectCode = '" . trim($_subject[1]) . "',
									teacher_id  = '" . $_POST['teacher_id'] . "' 
								where
									acadyear     = '" . $acadyear . "' and
									acadsemester = '" . $acadsemester . "' and
									class_id     = '" . $_roomID . "' and
									period       = '" . $_POST['period'] . "' and
									weekday      = '" . $_POST['weekday'] . "' 
							";
							$_res_student_learn = mysqli_query($_connection,$_sql_student_learn);

							$_sql_student_learn_task = "
								update student_learn_task
								set
									SubjectCode = '" . trim($_subject[1]) . "',
									teacher_id  = '" . $_POST['teacher_id'] . "' 
								where
									acadyear     = '" . $acadyear . "' and
									acadsemester = '" . $acadsemester . "' and
									task_roomid  = '" . $_roomID . "' and
									period       = '" . $_POST['period'] . "' and
									weekday      = '" . $_POST['weekday'] . "' 
							";
							$_res_student_learn_task = mysqli_query($_connection,$_sql_student_learn_task);

							$_sql_teaching_record = "
								update teaching_record
								set
									SubjectCode = '" . trim($_subject[1]) . "'
								where
									acadyear     = '" . $acadyear . "' and
									acadsemester = '" . $acadsemester . "' and
									room_id      = '" . $_roomID . "' and
									period       = '" . $_POST['period'] . "' and
									weekday      = '" . $_POST['weekday'] . "' 
							";
						}


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
	
						$_key = $_POST['teacher_id'].trim($_subject[1]).$_POST['weekday'].$_POST['period'].$_subject[0].$acadyear.$acadsemester;
						
						$_event_details = "";
						$_event_details .= "บันทึกข้อมูลตารางสอน วิชา " . trim($_subject[1]) ;
						$_event_details .= " ห้อง " . substr($_subject[0],0,1) . '/' .$room . " สอนวัน" . displayDayofWeek($_POST['weekday']) ;
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
						$_addnew_result = "<font color='red'>ไม่สามารถเพิ่มวิชาที่สอนได้ เนื่องจาก : " . mysqli_error($_connection) . "</font>";
					}

				}
			}
		}
  ?>


<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.3 จัดการรายวิชาที่สอน (จากผลการลงทะเบียน)</strong></font></span></td>
      <td >

		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_gpa/teachingTable&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_gpa/teachingTable&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/teachingTable&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/teachingTable&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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
							count(t.teacher_id) as c
						from 
							users_account u 
							left join teaching_schedule t 
							on (u.user_account_id = t.teacher_id and t.acadyear = '" . $acadyear ."' and t.acadsemester = '" . $acadsemester ."')
						where
							u.user_account_status = 'active'
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

		$_table = array(array(array()));
		$_days  = array("","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์");

		$_totalRows = 0;
		$_sqlTable = "
					SELECT *
					FROM
						teaching_schedule t
					WHERE
						t.teacher_id = '" . $_POST['teacher_id'] . "'
						and t.acadyear = '" . $acadyear . "'
						and t.acadsemester = '" . $acadsemester . "'
					order BY
						weekday,period,room_id  ";
		
		//echo $_sqlTable . "<br/>";
		$_resTable2 = mysqli_query($_connection,$_sqlTable);
		$_totalRows = mysqli_num_rows($_resTable2);

		if($_totalRows>0){

			// add data to array
			while($_data = mysqli_fetch_assoc($_resTable2)){
				$_table[$_data['weekday']][$_data['period']][] = $_data['SubjectCode']."|".$_data['room_id'] . "|" . $_data['teacher_id'] . "|" . $_data['club_code'] . "|" . $_data['location'];
			}
			$_bg_color = "onMouseOver=\"this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';\" onMouseOut=this.style.backgroundColor=\"#FFFFFF\";";
			//print_r($_table);

			for($_i=1;$_i<count($_days);$_i++){
				echo "<tr height='75px'>";
				echo "<td align='center' class='key'>" . displayDayofWeek($_i) . "</td>";
				for($_j=1;$_j<=8;$_j++){
					if(!empty($_table[$_i][$_j])){
						echo "<td align='center' " . $_bg_color . " valign='top'>";
						for($_y=0;$_y<count($_table[$_i][$_j]);$_y++){
							if($_y!=0){ echo "<br/>";}

							/* 
							0 - $_data['SubjectCode']."|".
							1 - $_data['room_id'] . "|" . 
							2 - $_data['teacher_id'] . "|" . 
							3 - $_data['club_code'] . "|" . 
							4 - $_data['location']; */
							$_rr = explode("|",$_table[$_i][$_j][$_y]);

							echo "<a href='index.php?option=module_gpa/deleteSchedule&teacher_id=" . $_rr[2] .
									"&period="   . $_j . "&weekday="   . $_i . 
									"&acadyear=" . $acadyear           . "&acadsemester=" . $acadsemester . 
									"&subject="  . $_rr[0] . 
									"&page=teachingTable'>";
							echo "<b>" . $_rr[0] . "</b><br/>";
							echo "</a>";
							echo getFullRoomFormat($_rr[1])."<br/>";
							echo $_rr[4];
						}
						echo "</td>";
					}
					else{
						echo "<td align='center' " . $_bg_color . " valign='top'>-</td>";
					}
					if($_j==4){
						echo "<td></td>";
					}
				}
				echo "</tr>";
			}
		}else{
			echo "<tr><td colspan='10' align='center'> ไม่พบการบันทึกข้อมูลตามเงื่อนไขที่สืบค้น </td></tr>";
		}
	?>
</table>
</div>
  <? } //end else-if ?>

<? if(isset($_POST['teacher_id']) && $_POST['teacher_id'] != "") { ?>


	<div align="center">
		<br/><br/><br/>
		<form action="" method="post" autocomplete="off">
			<table class="admintable">
				<tr height="35px">
					<td class="key" colspan="2"> &nbsp; ส่วนเพิ่มรายวิชาที่สอน</td>
				</tr>
				<tr>
					<td align="right" width="100px"> ห้อง / รายวิชา </td>
					<td> 
						<?php
							$_sql_teaching = "
							
									select 
										r.room_id,
										r.SubjectCode,
										s.SubjectName,
										r.club_code,
										c.club_name,
										if(r.club_code = '0000',s.SubjectName,c.club_name) as 's_name'
									from 
										register_teachers r inner join teachers t
										on (r.teacher_id = t.teacher_id and r.acadyear = '" . $acadyear . "' and r.acadsemester = '" . $acadsemester . "')
										left join curriculum_subjects s 
										on (r.SubjectCode = s.SubjectCode) 
										left join curriculum_clubs c 
										on (r.club_code = c.club_code and r.club_code != '0000')
									where
										r.teacher_id = '" . $_POST['teacher_id'] . "'
									order by
										FIELD(
											s.SubjectType,
											'พื้นฐาน',
											'เพิ่มเติม',
											'กิจกรรมพัฒนาผู้เรียน'
										),
										s.SubjectGroup,
										c.club_code,
										r.SubjectCode,
										r.room_id
						
								";
							$_res_teaching = mysqli_query($_connection,$_sql_teaching);
						?>
						<select name="subject_info" class="inputboxUpdate">
							<option value=""></option>
							<? while ($_datT = mysqli_fetch_assoc($_res_teaching)) { ?>
								<option value="<?=$_datT['room_id'] . "|" . $_datT['SubjectCode'] . "|" . $_datT['club_code']?>" >
									<?=getFullRoomFormat($_datT['room_id']) . ' - ' . $_datT['SubjectCode'] . ' ' . $_datT['s_name']?>
								</option>
							<? } ?>
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
					<td> <input type="text" name="location" size="10" class="inputboxUpdate" maxlength="15" /> </td>
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
<? } ?>

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

?>

