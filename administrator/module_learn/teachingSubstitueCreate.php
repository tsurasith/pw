
 
 <?php
	$_processing_message = "";
	$_processing_result  = false;

	if(isset($_POST['save'])){
		$_var = explode("/",$_POST['subject_info']);

		$_sql_check_existing = " 
					select * from teaching_substitute 
					where
						owner_teacher_id 	= '" . $_POST['teacher_id'] . "' and
						acadyear 			= '" . $acadyear . "' and
						acadsemester 		= '" . $acadsemester . "' and
						SubjectCode			= '" . $_var[0] . "' and
						teaching_date		= '" . $_POST['date'] . "' and
						period				= '" . $_var[1] . "' 
					";
		$_resExisting = mysqli_query($_connection,$_sql_check_existing);
		$_countExisting = mysqli_num_rows($_resExisting);

		if($_countExisting == 0){
			$_sql_insert = "
				INSERT INTO `teaching_substitute`(
					`teacher_id`,
					`owner_teacher_id`,
					`teaching_date`,
					`SubjectCode`,
					`weekday`,
					`period`,
					`location`,
					`acadyear`,
					`acadsemester`,
					`room_id`,
					`teaching_substitute_status`,
					`created_datetime`,
					`created_user`
				)
				VALUES(
					'" . $_POST['teacher_id2'] . "',
					'" . $_POST['teacher_id'] . "',
					'" . $_POST['date'] . "',
					'" . $_var[0] . "',
					'" . date('w',strtotime($_POST['date'])) . "',
					'" . $_var[1] . "',
					'" . $_POST['location'] . "', 
					'" . $acadyear . "',
					'" . $acadsemester . "',
					'" . $_var[2] . "',
					'0',
					CURRENT_TIMESTAMP,
					'" . $_SESSION['user_account_id'] . "'
				)
				";
			$_resInsert = mysqli_query($_connection,$_sql_insert);
			if($_resInsert){
				
				$_processing_message = "ระบบได้ทำการบันทึกข้อมูลการจัดสอนแทนเรียบร้อยแล้ว <br/>";

				$_text  = "";
				$_text .= "ระบบได้บันทึกจัดสอนแทน โดยครู". getUserAccountName($_connection,$_POST['teacher_id2']) . " ";
				$_text .= "ทำการสอนแทน ครู" . getUserAccountName($_connection,$_POST['teacher_id']) . " ";
				$_text .= "ในวัน " . displayDayofWeek(date('w',strtotime($_POST['date']))) . " ";
				$_text .= "ที่ " . displayFullDate($_POST['date']) . " รหัสวิชา " . $_var[0] . " ";
				$_text .= "ห้อง " . getFullRoomFormat($_var[2]) . " ";
				$_text .= "คาบเรียนที่ " . $_var[1] . " (รอการอนุมัติจากฝ่ายวิชาการ) ";

				$_processing_message = $_text;

				$_text .=  "\nโดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token);
				$_processing_result = true;

				/*
				$_event_details = "";
				$_event_details .= "มีการบันทึกการจัดสอนแทน วันที่ " . displayFullDate($_POST['date']);

				$_event_key = hash("sha256",$_event_details.date("H:i:s"));

				$_event_user_id = $_SESSION['user_account_id'];
				if(checkDuplicateEventLog($_connection,$_event_key)){
					event_log($_connection,1,2,4,
					$_event_key,
					$_event_details,
					$_event_user_id,$acadyear,$acadsemester);
				}
				*/

			}else{
				$_processing_message  = "ไม่สามารถบันทึกการสอนแทนได้ เนื่องจากเกิดการบันทึกซ้ำ <br/>";
				$_processing_message .= "หรือใช้ข้อความต่อไปนี้แจ้งผู้ดูแลระบบ : " . mysqli_error($_connection);
			}
		}else{
			$_processing_message  = "ไม่สามารถบันทึกการสอนแทนได้ เนื่องจากเกิดการบันทึกซ้ำ ของรหัสวิชา คาบเรียนและวันที่สอน ตามรายการที่ท่านเลือก <br/>";
		}
	} // end save data process

?>
 
 
 
 
 
 
 <script language="javascript" type="text/javascript">
	function disableSave(){
		var saveButton = document.getElementById('save');
		if(typeof(saveButton) != 'undefined' && saveButton != null)
		{
			saveButton.disabled = true;
		}
	}
 </script>

<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.3 งานจัดการสอนแทน </strong></font></span></td>
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
					echo "<a href=\"index.php?option=module_learn/teachingSubstitueCreate&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/teachingSubstitueCreate&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/teachingSubstitueCreate&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/teachingSubstitueCreate&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				<br/>
			
           <font color="#000000" size="2">
           		<form name="sSelect" method="post" action="index.php?option=module_learn/teachingSubstitueCreate">
				   
					เลือกวันที่ 
						<select name="date" class="inputboxUpdate" >
						<option value=""></option>
					<?php
					$sql_date = "select distinct task_date,weekday from student_learn_task where task_status = '0' and acadyear = '" .$acadyear ."' and acadsemester = '" .$acadsemester."' order by task_date " ;
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
<? if((isset($_POST['submit']) && $_POST['date'] != "") || isset($_POST['submit2']) || isset($_POST['submit3'])) { ?>
	<div align="center">

	<form method="post" name="teachList" action="index.php?option=module_learn/teachingSubstitueCreate">
		<input type="hidden" name="submit2" value="เรียกดู">
		<input type="hidden" name="date" value="<?=$_POST['date']?>" />

		<table>
			<tr>
				<td align="right">เลือกครูผู้สอน</td>
				<td>
					<?php 
							$sql_teacher = " 
							SELECT
								t.teacher_id,
								u.user_account_prefix,
								u.user_account_firstname,
								u.user_account_lastname,
								count(t.teacher_id) as 'c'
							FROM
								student_learn_task l INNER JOIN teaching_schedule t 
								ON
								(
									l.acadyear = t.acadyear AND 
									l.acadsemester = t.acadsemester AND 
									l.weekday = t.weekday AND 
									l.period = t.period AND 
									l.task_roomid = t.room_id AND 
									t.teacher_id IS NOT NULL
								)
								INNER JOIN users_account u 
								ON
								(
									t.teacher_id = u.user_account_id
								)
							WHERE
								l.task_date = '" . $_POST['date'] . "' and
								concat(t.teacher_id,t.period) not in (
																		select distinct concat(owner_teacher_id,period)
																		from teaching_substitute
																		where teaching_date = '" . $_POST['date'] . "' 
																	)
							GROUP BY
								t.teacher_id,u.user_account_prefix,u.user_account_firstname,u.user_account_lastname
							ORDER BY
								CONVERT(u.user_account_firstname using tis620),
								CONVERT(u.user_account_lastname using tis620)
							" ;
							//echo $sql_Room ;
							$resTeacher = mysqli_query($_connection,$sql_teacher);	
					?>
					<select name="teacher_id" class="inputboxUpdate" onChange="document.teachList.submit();">
						<option value=""></option>
						<?php
							$_select = "";
							while($dat = mysqli_fetch_assoc($resTeacher))
							{
								$_select = (isset($_POST['teacher_id'])&&$_POST['teacher_id'] == $dat['teacher_id']?"selected":"");
								echo "<option value=" . $dat['teacher_id'] . " $_select>";
								echo $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $dat['c'] . ')';
								echo "</option>";
							}
						?>
					</select>
				</td>
			<? if(isset($_POST['teacher_id'])){ ?> 
			<tr>
				<td align="right">เลือกรายวิชา</td>
				<td>
					<?php 
							$sql_subject = " 
							SELECT
								t.teacher_id,
								t.SubjectCode,
								t.period,
								t.room_id
							FROM
								student_learn_task l INNER JOIN teaching_schedule t 
								ON
								(
									l.acadyear = t.acadyear AND 
									l.acadsemester = t.acadsemester AND 
									l.weekday = t.weekday AND 
									l.period = t.period AND 
									l.task_roomid = t.room_id AND 
									t.teacher_id IS NOT NULL
								)
							WHERE
								l.task_date = '" . $_POST['date'] . "' and 
								t.teacher_id = '" . $_POST['teacher_id'] . "' and
								concat(t.SubjectCode,t.period) not in (
																		select distinct concat(SubjectCode,period)
																		from teaching_substitute
																		where 
																				teaching_date = '" . $_POST['date'] . "' and 
																				owner_teacher_id = '" . $_POST['teacher_id'] . "' and
																				acadyear = '" . $acadyear . "' and
																				acadsemester = '" . $acadsemester . "' 
																		)
							ORDER BY
								t.period
							" ;
							//echo $sql_Room ;
							$resSubject = mysqli_query($_connection,$sql_subject);	
					?>
					<select name="subject_info" class="inputboxUpdate" onChange="document.teachList.submit();">
						<option value=""></option>
						<?php
							$_select = "";
							while($dat = mysqli_fetch_assoc($resSubject))
							{
								$_select = (isset($_POST['subject_info'])&&$_POST['subject_info'] == ($dat['SubjectCode'].'/'.$dat['period'].'/'. $dat['room_id'] . '/' . $dat['weekday'])?"selected":"");
								echo "<option value=" . $dat['SubjectCode'].'/'.$dat['period'].'/'. $dat['room_id'] . '/' . $dat['weekday'] . " $_select>";
								echo $dat['SubjectCode'] . ' ห้อง ' . getFullRoomFormat($dat['room_id']) . ' คาบที่ ' . $dat['period'] ;
								echo "</option>";
							}
						?>
					</select>
					<input type="hidden" name="submit3" />
				</td>
			</tr>
			<? } ?>
			<? if(isset($_POST['subject_info']) && $_POST['subject_info'] != ""){ ?>
				<?php
					$_var = explode("/",$_POST['subject_info']);
				?>
			<tr>
				<td align="right">เลือกครูผู้สอนแทน</td>
				<td>
					<?php 
							$sql_teacher = " 
							SELECT
								t.teacher_id,
								u.user_account_prefix,
								u.user_account_firstname,
								u.user_account_lastname,
								count(t.teacher_id) as 'c'
							FROM
								student_learn_task l INNER JOIN teaching_schedule t 
								ON
								(
									l.acadyear = t.acadyear AND 
									l.acadsemester = t.acadsemester AND 
									l.weekday = t.weekday AND 
									l.period = t.period AND 
									l.task_roomid = t.room_id AND 
									t.teacher_id IS NOT NULL
								)
								INNER JOIN users_account u 
								ON
								(
									t.teacher_id = u.user_account_id
								)
							WHERE
								l.task_date = '" . $_POST['date'] . "' and
								t.teacher_id != '" . $_POST['teacher_id'] . "' and
								t.teacher_id not in (
														SELECT teacher_id from teaching_schedule
														WHERE 
															period = '" . $_var[1] . "' and
															weekday = '" . date('w',strtotime($_POST['date'])) . "' and
															acadyear = '" . $acadyear . "' and
															acadsemester = '" . $acadsemester . "' 
														UNION
														SELECT teacher_id FROM teaching_substitute
														WHERE
															period = '" . $_var[1] . "' and
															weekday = '" . date('w',strtotime($_POST['date'])) . "' and
															teaching_date = '" . $_POST['date'] . "' and
															acadyear = '" . $acadyear . "' and
															acadsemester = '" . $acadsemester . "' 
													)
							GROUP BY
								t.teacher_id,u.user_account_prefix,u.user_account_firstname,u.user_account_lastname
							ORDER BY
								CONVERT(u.user_account_firstname using tis620),
								CONVERT(u.user_account_lastname using tis620)
							" ;
							//echo $sql_Room ;
							$resTeacher = mysqli_query($_connection,$sql_teacher);	
					?>
					<select name="teacher_id2" class="inputboxUpdate" onChange="disableSave();">
						<option value=""></option>
						<?php
							$_select = "";
							while($dat = mysqli_fetch_assoc($resTeacher))
							{
								$_select = (isset($_POST['teacher_id2'])&&$_POST['teacher_id2'] == $dat['teacher_id']?"selected":"");
								echo "<option value=" . $dat['teacher_id'] . " $_select>";
								echo $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $dat['c'] . ')';
								echo "</option>";
							}
						?>
					</select>
					
					<input type="hidden" name="submit4" />
					<input type="submit" name="verify" value="ตรวจสอบ" />

				</td>
			</tr>
			<? } ?>
			<? if(isset($_POST['verify']) && $_POST['teacher_id2'] != "" && $_POST['subject_info'] != ""){ ?>
			<tr>
				<td colspan="2">
					<?php
						$_subject_info = explode("/",$_POST['subject_info']);

						$_sql_location = "
								SELECT 
									location
								FROM
									teaching_schedule
								WHERE
									SubjectCode 	= '" . $_subject_info[0] . "' AND
									weekday 		= '" . date('w',strtotime($_POST['date'])) . "' AND
									teacher_id 		= '" . $_POST['teacher_id'] . "' AND
									period 			= '" . $_subject_info[1] . "' AND 
									acadyear 		= '" . $acadyear . "' AND
									acadsemester	= '" . $acadsemester . "' 
							";
						$_resLocation = mysqli_query($_connection,$_sql_location);
						$_datLo = mysqli_fetch_assoc($_resLocation);
					?>
					<br/><br/>
					หากต้องการบันทึกให้ <font color='green'><b>ครู<?=getUserAccountName($_connection,$_POST['teacher_id2'])?></b></font> 
					ทำการสอนแทนคุณ <font color='darkorange'><b>ครู<?=getUserAccountName($_connection,$_POST['teacher_id'])?></b></font> <br/>
					ในวัน <font color='green'><b><?=displayDayofWeek(date('w',strtotime($_POST['date'])))?></b> </font>
					ที่ <font color='green'><b><?=displayFullDate($_POST['date'])?></b></font>
					รหัสวิชา <font color='green'><b><?=$_subject_info[0]?></b></font> 
					ห้อง <font color='green'><b><?=getFullRoomFormat($_subject_info[2])?></b></font>
					คาบเรียนที่ <font color='green'><b><?=$_subject_info[1]?></b></font> 
					สถานที่ <font color='green'><b><?=$_datLo['location']?></b></font>  <br/>
					ให้ทำการกดปุ่ม "บันทึก" ด้านล่างเพื่อบันทึกข้อมูลและแจ้งเตือนไปยังข้อความทางไลน์
					<br/><br/>
					<center>
						<input type="hidden" name="location" value="<?=$_datLo['location']?>" />
						<input type="submit" name="save" value="บันทึก" id="save"/> 
						<input type="button" name="reset" value="ยกเลิก" onClick="document.teachList.submit();" /> 
				    </center>
				</td>
			</tr>
			<? } ?>
			<? if(isset($_POST['save'])){ ?>
			<tr>
				<td colspan="2" align="center" witdth="400px">
					<br/><br/>
					<?
						if($_processing_result){
							echo "<font color='green'>" . $_processing_message . "</font>";
						}else{
							echo "<font color='red'>" . $_processing_message . "</font>";
						}
					?>
				</td>
			</tr>
			<? } ?>
		</table>

	</div>
	</form>

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
						s.location,
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
							<th align="center" colspan="8">
							การจัดการสอนแทนวัน <?=displayDayofWeek(date('w',strtotime($_POST['date'])))?>  ที่  <?=displayFullDate($_POST['date'])?>
							<br/><br/>
							</th>
						</tr>
						<tr>
							<td class="key" align="center" width="35px">ที่</td>
							<td class="key" align="center" width="150px">ครูเจ้าของวิชา</td>
							<td class="key" align="center" width="150px">ครูสอนแทน</td>
							<td class="key" align="center">คาบที่</td>
							<td class="key" align="center" width="70px">รหัสวิชา</td>
							<td class="key" align="center">ห้องเรียน</td>
							<td class="key" align="center">สถานที่</td>
							<td class="key" align="center">สถานะ</td>
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
							<td align="center"><?=$_dat['period']?></td>
							<td align="center"><?=$_dat['SubjectCode']?></td>
							<td align="center"><?=getFullRoomFormat($_dat['room_id'])?></td>
							<td align="center"><?=$_dat['location']?></td>
							<td align="left"><?=displayTeachingSubstituteStatus($_dat['teaching_substitute_status'])?></td>
							<td align="center">
								<? if($_dat['teaching_substitute_status']=="0"){ ?>
								<form method="post" action="index.php?option=module_learn/teachingSubstitueDelete">
									<input type="hidden" name="period" value="<?=$_dat['period']?>" />
									<input type="hidden" name="room_id" value="<?=$_dat['room_id']?>" />
									<input type="hidden" name="subject_code" value="<?=$_dat['SubjectCode']?>" />
									<input type="hidden" name="acadyear" value="<?=$acadyear?>" />
									<input type="hidden" name="acadsemester" value="<?=$acadsemester?>" />
									<input type="hidden" name="teaching_date" value="<?=$_dat['teaching_date']?>" />
									<input type="hidden" name="owner_teacher_id" value="<?=$_dat['owner_teacher_id']?>" />
									<input type="hidden" name="teacher_id" value="<?=$_dat['teacher_id']?>" />
									<input type="submit" name="delete" value="ลบ" />
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
		<?	} ?>

	
	<? } //end if-check search ?>
</div>

