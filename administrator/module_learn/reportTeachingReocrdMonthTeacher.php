

<link rel="stylesheet" type="text/css" href="module_learn/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_learn/js/calendar.js"></script>

<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.4 แสดงรายการบันทึกหลังการสอนของครูผู้สอน (เลือกเดือน)</strong></font></span></td>
      <td align="right">
	  <? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

			//==============$_POST value===========//
			if(isset($_POST['acadyearX'])) { $acadyear = $_POST['acadyearX']; }
			if(isset($_POST['acadsemesterX'])) { $acadsemester = $_POST['acadsemesterX']; }

			$_date = "";
			$_date = isset($_POST['date'])?$_POST['date']:"";

			if($_date == ""){
				$_date = isset($_REQUEST['date'])?$_REQUEST['date']:"";
			}

			$_date = "";
			$_date = isset($_POST['date'])?$_POST['date']:"";
	
			$_teacher_id = "";
			$_teacher_id = isset($_POST['teacher_id'])?$_POST['teacher_id']:"";

		?>
        ปีการศึกษา
        <?php  
					echo "<a href=\"index.php?option=module_learn/reportTeachingReocrdMonthTeacher&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportTeachingReocrdMonthTeacher&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportTeachingReocrdMonthTeacher&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportTeachingReocrdMonthTeacher&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				<br/>
			
		<font  size="2" color="#000000">
			<form action="index.php?option=module_learn/reportTeachingReocrdMonthTeacher" method="post" autocomplete="off">
				
				<? if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc100" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") { ?>
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
									left join teaching_record t 
									on (u.user_account_id = t.teacher_id and t.acadyear = '" . $acadyear ."' and t.acadsemester = '" . $acadsemester ."')
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
							$_select = "";
							while($dat = mysqli_fetch_assoc($resTeacher))
							{
								$_select = (isset($_POST['teacher_id'])&&$_POST['teacher_id'] == $dat['user_account_id']?"selected":"");
								echo "<option value=\"" . $dat['user_account_id'] . "\" $_select>";
								echo $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $dat['c'] . ')';
								echo "</option>";
							}
							
						?>
					</select><br/>
					<? } else { ?> 
						<input type="hidden" name="teacher_id" value=<?=$_SESSION['user_account_id']?> />
					<? } ?>
			
				เดือน 
				<select name="month" class="inputboxUpdate">
			 	<option value="">ทั้งหมด</option>
				<?php
					$_sqlMonth = "select distinct month(teaching_date)as m,year(teaching_date)+543 as y
									from teaching_record where acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester . "'
									order by year(teaching_date),month(teaching_date)";
					$_resMonth = mysqli_query($_connection,$_sqlMonth);
					while($_datMonth = mysqli_fetch_assoc($_resMonth))
					{
						$_select = (isset($_POST['month'])&&$_POST['month'] == $_datMonth['m']?"selected":"");
						echo "<option value=\"" . $_datMonth['m'] . "\" $_select>" . displayMonth($_datMonth['m']) . ' ' . $_datMonth['y'] . "</option>";
					} mysqli_free_result($_resMonth);
				?>
			 </select>
				<input type="submit" name="search" value="เรียกดู" class="button" /><br/>
				<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
				<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
			</form>
		</font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if((isset($_POST['search']))) { ?>

		<?php
			$_sql_display = "
				select 
						s.acadyear,
						s.acadsemester,
						s.teacher_id,
						s.teaching_date,
						s.SubjectCode,
						s.period,
						s.room_id,
						convert_tz(s.created_datetime,'+00:00','+07:00') as created_datetime,
						s.has_assignment,
						trim(s.teaching_details) as teaching_details,
						s.updated_datetime,
						s.weekday
				from 
					teaching_record s 
				where
					1=1 and ";
			if($_POST['month']!=""){
				$_sql_display .= " month(s.teaching_date) = '". $_POST['month'] ."' and ";
			}
			$_sql_display .= " 
					s.teacher_id = '" . $_POST['teacher_id'] . "' and
					s.acadyear = '" . $acadyear . "' and
					s.acadsemester = '" . $acadsemester . "' 
				order by
					s.room_id,
					s.SubjectCode,
					s.teaching_date
			";
			$_res_display = mysqli_query($_connection,$_sql_display);
			$_count_display = mysqli_num_rows($_res_display);

			$_no = 1;
			$_previous_room = "";
			$_previous_subject = "";

		?>
		<?	if($_count_display>0){ ?>
				<div align="center">
					<br/>
					<table class="admintable">
						<tr>
							<th align="center" colspan="11">
							<img src="../images/school_logo.png" width="120px"><br/>
							แสดงรายการบันทึกการสอน<br/>
							<?php
								if(trim($_POST['month'])!=""){
									echo displayMonth($_POST['month']);
								}
							?>
							ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?>
							<br/><br/>
							</th>
						</tr>
						<tr>
							<td class="key" align="center" width="25px">ที่</td>
							<td class="key" align="center" width="40px">ห้อง</td>
							<td class="key" align="center" width="65px">รหัสวิชา</td>
							<td class="key" align="center" width="85px">วันที่</td>
							<td class="key" align="center" width="55px">วัน</td>
							<td class="key" align="center" width="27px">คาบ</td>
							<td class="key" align="center" width="70px">ใบงาน/<br/>การบ้าน</td>
							<td class="key" align="center" width="480px">บันทึกการสอน</td>
							<td class="key" align="center" width="100px">-</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_res_display)){ ?>
						<tr>
							<td align="center" valign="top"><?=$_no++;?></td>
							<td align="center" valign="top">
								<?
									if($_previous_room!=$_dat['room_id']){
										echo getFullRoomFormat($_dat['room_id']);
									}else{
										echo "";
									}
								?>
							</td>
							<td align="center" valign="top">
								<?
									if($_previous_subject.$_previous_room != $_dat['SubjectCode'].$_dat['room_id']){
										echo $_dat['SubjectCode'];
									}else{
										echo "";
									}
								?>
							</td>
							<td align="center" valign="top"><?=$_dat['teaching_date']?></td>
							<td align="center" valign="top"><?=displayDayofWeek($_dat['weekday'])?></td>
							<td align="center" valign="top"><?=$_dat['period']?></td>
							<td align="center" valign="top">
								<?php
									if($_dat['has_assignment']=="1"){
										echo "มี";

									}else if($_dat['has_assignment']=="0"){
										echo "ไม่มี";
									}
									else{
										echo "-";
									}
								?>
							</td>
							<td align="left" valign="top">
								<?php
									if(strlen($_dat['teaching_details'])>0){
										echo trim($_dat['teaching_details']);
									}
									else{
										echo "-";
									}
								?>
							</td>
							<td align="left" valign="top"></td>
							<td align="center" valign="top">
							</td>
						</tr>
						<?php
							$_previous_room = $_dat['room_id'];
							$_previous_subject = $_dat['SubjectCode'];
						?>
						<? } ?>
					</table>
				</div>
		<?	} else { ?>
			<div align="center">
					<br/><br/>
					<font color="red">ยังไม่มีการบันทึกข้อมูลในส่วนนนี้</font>
					<br/>
			</div>
		<? } ?>

	
	<? } //end if-check search ?>
</div>

