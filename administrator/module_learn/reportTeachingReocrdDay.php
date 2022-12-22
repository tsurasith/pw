

<link rel="stylesheet" type="text/css" href="module_learn/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_learn/js/calendar.js"></script>

<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.3 แสดงรายการบันทึกหลังการสอน (เลือกวันที่)</strong></font></span></td>
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
					echo "<a href=\"index.php?option=module_learn/reportTeachingReocrdDay&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportTeachingReocrdDay&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportTeachingReocrdDay&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportTeachingReocrdDay&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				<br/>
			
		<font  size="2" color="#000000">
			<form action="index.php?option=module_learn/reportTeachingReocrdDay" method="post" autocomplete="off">
				เลือกวันที่ <input name="date" type="text" id="date" onClick="showCalendar(this.id)" size="10px" maxlength="10" value="<?=(isset($_POST['date'])&&$_POST['date']!=""?$_POST['date']:"")?>" class="inputboxUpdate" /> 
				</select> <input type="submit" name="search" value="เรียกดู" class="button" /><br/>
				<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
				<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
			</form>
		</font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if((isset($_POST['search']) && $_POST['date'] != "")) { ?>

		<?php
			$_sql_display = "
				select 
						u.user_account_prefix, 
						u.user_account_firstname,
						u.user_account_lastname,
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
						s.updated_datetime
				from 
					teaching_record s left join users_account u
					on (s.teacher_id = u.user_account_id)
				where
					s.teaching_date = '". $_POST['date'] ."'	
				order by
					s.room_id,
					s.period
			";
			$_res_display = mysqli_query($_connection,$_sql_display);
			$_count_display = mysqli_num_rows($_res_display);

			$_no = 1;
			$_previous_name = "";

		?>
		<?	if($_count_display>0){ ?>
				<div align="center">
					<br/>
					<table class="admintable">
						<tr>
							<th align="center" colspan="11">
							<img src="../images/school_logo.png" width="120px"><br/>
							แสดงรายการบันทึกการสอน<br/>
							วัน<?=displayDayofWeek(date('w',strtotime($_POST['date'])))?>  
							ที่ <?=displayFullDate($_POST['date'])?>
							<br/><br/>
							</th>
						</tr>
						<tr>
							<td class="key" align="center" width="30px">ที่</td>
							<td class="key" align="center" width="140px">ครูผู้สอน</td>
							<td class="key" align="center" width="30px">ห้อง</td>
							<td class="key" align="center">คาบ</td>
							<td class="key" align="center" width="70px">รหัสวิชา</td>
							<td class="key" align="center">ใบงาน/<br/>การบ้าน</td>
							<td class="key" align="center" width="440px">บันทึกการสอน</td>
							<td class="key" align="center" width="100px">-</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_res_display)){ ?>
						<tr>
							<td align="center" valign="top"><?=$_no++;?></td>
							<td valign="top">
								<?
									if($_previous_name != $_dat['user_account_firstname'] . ' ' . $_dat['user_account_lastname']){
										echo "ครู" . $_dat['user_account_firstname'] . ' ' . $_dat['user_account_lastname'];
									}else{
										echo "";
									}
								?>
							</td>
							<td align="center" valign="top"><?=getFullRoomFormat($_dat['room_id'])?></td>
							<td align="center" valign="top"><?=$_dat['period']?></td>
							<td align="center" valign="top"><?=$_dat['SubjectCode']?></td>
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
							<?php
								$_previous_name = $_dat['user_account_firstname'] . ' ' . $_dat['user_account_lastname'];
							?>
						</tr>
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

