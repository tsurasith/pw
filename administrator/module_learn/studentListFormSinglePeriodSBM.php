
<div id="content">
    <style>
		.hover:hover{
			color:#039;
			font-weight:bold;
			cursor:pointer;
		}
		input,label{
			cursor:pointer;
		}
	</style>

	<script language="javascript" type="text/javascript">
		function check(name,value) { document.getElementById(name).bgColor=value; }
	</script>

	<?php
			$_roomID = "";
			$_roomID = isset($_POST['roomID'])?$_POST['roomID']:"";
			
			$room_id = getRoom($_REQUEST['room']);
			$xyear = getXyearth($_REQUEST['room']);
			$xlevel  = getXLevel($_REQUEST['room']);
			/*
				$date = ????-??-??
				$room_id = ?
				$room = ?0?
				$xlevel = 3|4
				$xyear = 1-6
			*/

			$_sql_learntable = "
					SELECT
						t.teacher_id,
						t.SubjectCode,
						t.location,
						l.task_date,
						l.period,
						l.task_roomid,
						l.acadyear,
						l.acadsemester,
						l.weekday,
						s.*
					FROM
						student_learn_task l
					INNER JOIN teaching_schedule t ON
						(
							l.acadyear = t.acadyear AND 
							l.acadsemester = t.acadsemester AND 
							l.weekday = t.weekday AND 
							l.period = t.period AND 
							l.task_roomid = t.room_id AND 
							t.teacher_id IS NOT NULL
						)
					LEFT JOIN teaching_substitute s ON
						(
							l.acadyear = s.acadyear AND 
							l.acadsemester = s.acadsemester AND 
							l.weekday = s.weekday AND 
							l.period = s.period AND 
							l.task_roomid = s.room_id AND 
							s.teacher_id IS NOT NULL
						)
				WHERE
						l.task_date = '" . $_REQUEST['date'] . "' and
						(t.teacher_id = '" . $_REQUEST['teacher_id'] . "' or s.teacher_id = '" . $_REQUEST['teacher_id'] . "'  )and
						(t.room_id = '". $_REQUEST['room'] . "' or s.room_id = '". $_REQUEST['room'] . "') and
						(t.SubjectCode = '" . $_REQUEST['subject'] . "' or s.SubjectCode = '" . $_REQUEST['subject'] . "' ) and 
						l.period = '" . $_REQUEST['period'] . "' 
					" ;

			//echo $_sql_learntable . "<br/>";
			$_resCheck = mysqli_query($_connection,$_sql_learntable);
			$_check_data = mysqli_num_rows($_resCheck);

			$_check_data = 1;


	?>
	<? if($_check_data>0) { ?>
		<div align="center">
			<table  align="center" cellspacing="1" class="admintable">
				<tr>
					<td class="header" align="center">
						<img src="../images/school_logo.png" width="120px"><br/>
						บันทึกการเข้าชั้นเรียน รหัสวิชา <font color='green'><?=$_REQUEST['subject']?></font> 
						<font color='#641E16'><?=$_REQUEST['class_type']=="substitute"?"(สอนแทน)":""?></font> <br/>
						ประจำวัน <?=displayDayOfWeek(date('w',strtotime($_REQUEST['date'])))?>  
						ที่ <font color="green"><?=displayFullDate($_REQUEST['date'])?></font>
						ห้อง  <font color="green"><?=getFullRoomFormat($_REQUEST['room'])?> </font>
						คาบที่ <font color="green"><?=$_REQUEST['period']?></font><br/>
						ภาคเรียนที่ <?=$_REQUEST['acadsemester']?>  ปีการศึกษา <?=$_REQUEST['acadyear']?><br/>
					</td>
				</tr>
				<tr>
					<td>
						
						<form method="post" action="index.php?option=module_learn/insertStudentCheckSinglePeriodSBM">
						<table cellspacing="1" class="admintable">
							<tr>
							<? $p_sql = 
									"select 
										task_date,
										task_roomid,
										task_status,
										period,
										SubjectCode
									from 
										student_learn_task 
									where 
										task_date = '"  .$_REQUEST['date'] . "' 
										and task_roomid = '" . $_REQUEST['room'] . "' 
									order by period " ;?>
							<? $p_res = mysqli_query($_connection,$p_sql) or die ( ' ' . mysqli_error($_connection));?>
							<? $y = 1; ?>
							<? while($p_dat = mysqli_fetch_assoc($p_res)) {	?>
								<? if($y == $_REQUEST['period']) {?>
									<td valign="top" width="80px" align="center">
										<font  size="2" color="#CC0000"><b> คาบที่ <?=$y?><br/></b></font>
										<font color="#FF0000"><font><?=$_REQUEST['subject']?></font></font>
										<? $y++;  ?>
									</td>
								<? }else if($p_dat['task_status'] == '1'){ ?>
									<td valign="top" width="70x" class="key">
										<font  size="2" color="#1E8849">
											<b> คาบที่ <?=$y?><br/>
											บันทึกแล้ว<br/>
										<?=$p_dat['SubjectCode']?></font>
										<? $y++;  ?>
									</td>
								<? } else { ?>
									<td valign="top" width="70px" class="key">
										<input type="checkbox" name="cperiod[<?=$y?>]" value="<?=$y?>" disabled>
										คาบที่ <?=$y?><br/>
										<input type="radio" name="cTeacherSign[<?=$y?>]" value="sign" disabled>มี <br/>
										<input type="radio" name="cTeacherSign[<?=$y?>]" value="unsign" disabled>ไม่มี
										<? $y++;  ?>
									</td>
								<? }//end if-else ?>
							<? }//end while ?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
							<input type="hidden" name="room_id" value="<?php echo $_REQUEST['room']; ?>"/>
							<input type="hidden" name="date" value="<?php echo $_REQUEST['date']; ?>"/>
							<input type="hidden" name="period" value="<?php echo $_REQUEST['period'] ; ?>"/>
							<input type="hidden" name="acadyear" value="<?=$_REQUEST['acadyear']?>"/>
							<input type="hidden" name="acadsemester" value="<?=$_REQUEST['acadsemester']?>"/>		
							<input type="hidden" name="teacher_id" value="<?=$_REQUEST['teacher_id']?>" />
							<input type="hidden" name="subject_code" value="<?=$_REQUEST['subject']?>" />
							<input type="hidden" name="class_type" value="<?=$_REQUEST['class_type']?>" />
										  
												<table cellspacing="1" cellpadding="1" width="90%">
													<tr>
														<td>
															<input type="radio" name="teacherSign" checked value="sign" id="teacherSign">
															<label for="teacherSign"><strong><font color="#009900" face="Tahoma" size="2"> มี</font></strong><font face="Tahoma" size="2">การลงชื่อของครูผู้สอน </font></label><br/>
															<input type="radio" name="teacherSign" value="unsign" id="teacherUnsign">
															<label for="teacherUnsign"><strong><font color="#FF0000" face="Tahoma" size="2">ไม่มี</font></strong><font face="Tahoma" size="2">การลงชื่อของครูผู้สอน  </font></label><br/>
															<font face="Tahoma" size="2">ผู้บันทึกข้อมูล :</font><font color="blue" face="Tahoma" size="2"> <?=$_SESSION['name']?></font>
														</td>
													</tr>
												</table>

												<?php
													$sql = "
															SELECT 
																s.id, s.prefix , s.firstname , s.lastname,s.xlevel,s.xyearth,s.room,
																concat(((s.xlevel-3)*3)+s.xyearth,'/',s.room) as class_room
															FROM 
																students s left join register_students r
																on (s.id = r.student_id and s.xedbe = r.acadyear)
															WHERE 
																xLevel =  '" . $xlevel . "' AND xYearth = '" . $xyear . "' and 
																xedbe = '" .$acadyear . "'  and r.SubjectCode = '" . $_REQUEST['subject'] ."' and 
																studstatus = '1' 
															ORDER BY sex,convert(firstname using tis620), convert(lastname using tis620) 
														";
													
													//	echo $sql;
													$result = mysqli_query($_connection,$sql) or die ('Error  - ' .mysqli_error($_connection));
													$i = 1;
													$j = 0;
													$rows = mysqli_num_rows($result);

												?>

												<table border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#3366FF">
													<tr bgcolor="#CCCCFF"> 
														<td width="30px" rowspan="2"><div align="center"><font color="#990066"><strong>ที่</strong></font></div></td>
														<td width="55px" rowspan="2"><div align="center"><font color="#990066"><strong>เลขประจำตัว</strong></font></div></td>
														<td width="200px" rowspan="2"><div align="center"><font color="#990066"><strong>ชื่อ - สกุล</strong></font></div></td>
														<td width="35px" rowspan="2"><div align="center"><font color="#990066"><strong>ห้อง</strong></font></div></td>
														<td colspan="6"><div align="center"><font color="#990066"><strong>การมาเข้าห้องเรียน</strong></font></div></td>
													</tr>
													<tr> 
														<td width="35px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>มา</strong></font></div></td>
														<td width="35px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>กิจกรรม</strong></font></div></td>
														<td width="35px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>สาย</strong></font></div></td>
														<td width="35px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>ลา</strong></font></div></td>
														<td width="35px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>ขาด</strong></font></div></td>
														<td width="35px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>หนีเรียน</strong></font></div></td>
													</tr>
													<? while($data = mysqli_fetch_assoc($result)) { ?>
														<tr   id="check[<?=$j?>]" bgcolor='#FFFFFF' class='hover' >
															<td align="center"><font size='2' face='Tahoma'><?=$i?></font></td>
															<td align="center">
																<input type='hidden' name="student_id[<?=$j?>]"    value="<?=$data['id']?>" />
																<input type='hidden' name="class_room[<?=$j?>]"    value="<?=str_replace("/","0",$data['class_room'])?>" />
																<input type='hidden' name="student_name[<?=$j?>]"  value="<?=$data['prefix'].$data['firstname'] .' ' .$data['lastname']?>" />
																<font size='2' face='Tahoma'><?=$data['id']?></font>
															</td>
															<td><font size='2' face='Tahoma'><?=$data['prefix'].$data['firstname']. ' ' .$data['lastname']?></font></td>
															<td align="center"><?=$data['class_room']?></td>
															<td align="center">
																<input type='radio' name="check[<?=$j?>]" value='white' checked  onclick="check(this.name,this.value)" />
															</td>
															<td align="center"><input type='radio' name="check[<?=$j?>]" value='lightgreen'  onclick='check(this.name,this.value)' /></td>
															<td align="center"><input type='radio' name="check[<?=$j?>]" value='yellow'   onclick='check(this.name,this.value)' ></td>
															<td align="center"><input type='radio' name="check[<?=$j?>]" value='lightblue'   onclick='check(this.name,this.value)' ></td>
															<td align="center"><input type='radio' name="check[<?=$j?>]" value='orange'  onclick='check(this.name,this.value)' ></td>
															<td align="center"><input type='radio' name="check[<?=$j?>]" value='red' onclick='check(this.name,this.value)'></td>
														</tr>
														<?	$j++; $i++; ?>
													<? } ?>
			
													<tr bgcolor="#FFFFFF"> 
													<td colspan="10" align="center"> <input type="hidden" name="count" value="<?php  echo $j; ?>"/>
														<table>
															<tr>
																<td>
																	<input type="submit" name="save" value="บันทึก"/> 
																	</form>	
																</td>
																<td>
																	<form method="post" action="index.php?option=module_learn/dateTaskListTeacher">
																		<input type="hidden" name="date" value="<?=$_REQUEST['date']?>" />
																		<input type="hidden" name="teacher_id" value="<?=$_REQUEST['teacher_id']?>" />
																		<input type="submit" name="submit" value="ยกเลิก" /> 
																	</form>
																</td>
															</tr>
														</table>
													</td>
													</tr>
												</table>


							</td>
							</tr>
		</table>

		<? mysqli_free_result($result); ?>		
		</div>	
		
	<? } else { ?>
		<div align="center">
			<br/><br/><br/>
			<font color='red'>
				เกิดข้อผิดพลาด ไม่พบข้อมูลตามที่ร้องขอ กรุณาย้อนกับไปที่หน้าหลักแล้วทำการเลือกวันที่และวิชาที่สอนของท่าน อีกครั้ง
			</font><br/><br/><br/>
			<input type="button" value="ตกลง" 
					onclick="location.href='index.php?option=module_learn/dateTaskListTeacher'" />
		</div>
	<? } ?>
		
</div>
