


<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.4 ยืนยัน/ติดตามการสอนแทน </strong></font></span></td>
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
					echo "<a href=\"index.php?option=module_learn/teachingSubstitueConfirmList&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/teachingSubstitueConfirmList&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/teachingSubstitueConfirmList&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/teachingSubstitueConfirmList&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				<br/>
			
           <font color="#000000" size="2">
           		<form name="sSelect" method="post" action="index.php?option=module_learn/teachingSubstitueConfirmList">
				   
					เลือกวันที่ 
						<select name="date" class="inputboxUpdate" >
						<option value=""></option>
					<?php
					$sql_date = "select distinct task_date,weekday from student_learn_task where acadyear = '" .$acadyear ."' and acadsemester = '" .$acadsemester."' order by task_date " ;
					$result = mysqli_query($_connection,$sql_date);
					while($data = mysqli_fetch_assoc($result)) { ?>
						<option value="<?=$data['task_date']?>" <?=$_date==$data['task_date']?"selected":""?>>
							<?=displayFullDate($data['task_date'])?> (<?=displayDayofWeek($data['weekday'])?>)
						</option>
					<? }  ?>
						</select>
						<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
						<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
						<input type="hidden" name="search" value="search"> <br/>
						<input type="submit" name="submit" value="เรียกดู" class="button">
                </form>
           </font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if((isset($_POST['submit']) && $_POST['date'] != "")) { ?>

		<?php
			$_sql_display = "
				select 
						u.user_account_prefix, 
						u.user_account_firstname,
						u.user_account_lastname,
						s.acadyear,
						s.acadsemester,
						s.teacher_id,
						s.owner_teacher_id,
						s.teaching_date,
						s.SubjectCode,
						s.period,
						s.room_id,
						convert_tz(s.confirmed_datetime,'+00:00','+07:00') as confirmed_datetime,
						s.has_assignment,
						trim(s.teaching_details) as teaching_details,
						o.user_account_prefix as owner_prefix, 
						o.user_account_firstname as owner_firstname,
						o.user_account_lastname as owner_lastname,
						s.teaching_substitute_status
				from 
					teaching_substitute s left join users_account u
					on (s.teacher_id = u.user_account_id)
					left join users_account o
					on (s.owner_teacher_id = o.user_account_id)
				where
					s.teaching_date = '". $_POST['date'] ."'	
				order by
					convert(o.user_account_firstname using tis620),
					convert(o.user_account_lastname using tis620),
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
							การจัดการสอนแทนวัน <?=displayDayofWeek(date('w',strtotime($_POST['date'])))?>  ที่  <?=displayFullDate($_POST['date'])?>
							<br/><br/>
							</th>
						</tr>
						<tr>
							<td class="key" align="center" width="25px">ที่</td>
							<td class="key" align="center" width="130px">ครูเจ้าของวิชา</td>
							<td class="key" align="center" width="130px">ครูสอนแทน</td>
							<td class="key" align="center">คาบ</td>
							<td class="key" align="center" width="70px">รหัสวิชา</td>
							<td class="key" align="center">ห้อง</td>
							<td class="key" align="center">ยืนยัน/รับทราบ<br/>สอนแทน</td>
							<td class="key" align="center">ใบงาน</td>
							<td class="key" align="center" width="240px">บันทึกการสอนแทน</td>
							<td class="key" align="center" width="110px">สถานะ</td>
							<td class="key" align="center">-</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_res_display)){ ?>
						<tr>
							<td align="center"><?=$_no++;?></td>
							<td>
								<?
									if($_previous_name != $_dat['owner_firstname'] . ' ' . $_dat['owner_lastname']){
										echo "ครู" . $_dat['owner_firstname'] . ' ' . $_dat['owner_lastname'];
									}else{
										echo "";
									}
								?>
							</td>
							<td><?='ครู' . $_dat['user_account_firstname'] . ' ' . $_dat['user_account_lastname']?></td>
							<td align="center" valign="top"><?=$_dat['period']?></td>
							<td align="center" valign="top"><?=$_dat['SubjectCode']?></td>
							<td align="center" valign="top"><?=getFullRoomFormat($_dat['room_id'])?></td>
							<td align="center" valign="top">
								<?php
									if($_dat['confirmed_datetime']==""){
										if($_dat['teacher_id']==$_SESSION['user_account_id']){ ?>
											<form method="post" action="index.php?option=module_learn/teachingSubstitueConfirm">
												<input type="hidden" name="period" value="<?=$_dat['period']?>" />
												<input type="hidden" name="room_id" value="<?=$_dat['room_id']?>" />
												<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
												<input type="hidden" name="acadyear" value="<?=$acadyear?>" />
												<input type="hidden" name="acadsemester" value="<?=$acadsemester?>" />
												<input type="hidden" name="teaching_date" value="<?=$_dat['teaching_date']?>" />
												<input type="hidden" name="owner_teacher_id" value="<?=$_dat['owner_teacher_id']?>" />
												<input type="hidden" name="teacher_id" value="<?=$_dat['teacher_id']?>" />
												<input type="submit" name="confirm" value="ยืนยันรับทราบ" />
											</form>
									<?	}else{
											echo "รอยืนยันรับทราบ";
										}
										
									}else{
										echo $_dat['confirmed_datetime'];
									}
								?>
							</td>
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
										echo $_dat['teaching_details'];
									}
									else{
										echo "-";
									}
								?>
							</td>
							<td align="left" valign="top"><?=displayTeachingSubstituteStatus($_dat['teaching_substitute_status'])?></td>
							<td align="center" valign="top">
								<? if($_dat['teaching_substitute_status']=="0"){ ?>
								<form method="post" action="index.php?option=module_learn/teachingSubstitueDetails">
									<input type="hidden" name="period" value="<?=$_dat['period']?>" />
									<input type="hidden" name="room_id" value="<?=$_dat['room_id']?>" />
									<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
									<input type="hidden" name="acadyear" value="<?=$acadyear?>" />
									<input type="hidden" name="acadsemester" value="<?=$acadsemester?>" />
									<input type="hidden" name="teaching_date" value="<?=$_dat['teaching_date']?>" />
									<input type="hidden" name="owner_teacher_id" value="<?=$_dat['owner_teacher_id']?>" />
									<input type="hidden" name="teacher_id" value="<?=$_dat['teacher_id']?>" />
									<input type="submit" name="details" value="รายละเอียด" disabled />
								</form>
								<? } else {
									echo "-" ;
								}
								?>
							</td>
							<?php
								$_previous_name = $_dat['owner_firstname'] . ' ' . $_dat['owner_lastname'];
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

