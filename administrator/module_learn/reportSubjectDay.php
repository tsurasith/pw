

<link rel="stylesheet" type="text/css" href="module_learn/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_learn/js/calendar.js"></script>


<div id="content">
  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.1 แสดงรายผลการเข้าเรียนตามห้องเรียน</strong></font></span></td>
      <td >
	  	 ปีการศึกษา
        <? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
		   if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
					echo "<a href=\"index.php?option=module_learn/reportSubjectDay&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportSubjectDay&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportSubjectDay&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else { echo " <a href=\"index.php?option=module_learn/reportSubjectDay&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ; }
				?>	<br/>
		 <font  size="2" color="#000000">
		 <form action="" method="post" autocomplete="off">
		 	เลือกวันที่ <input name="date" type="text" id="date" onClick="showCalendar(this.id)" size="10px" maxlength="10" value="<?=(isset($_POST['date'])&&$_POST['date']!=""?$_POST['date']:"")?>" class="inputboxUpdate" /> 
			ห้องเรียน  
			<?php 
				$sql_Room = "select room_id from rooms where acadyear = '". $acadyear . "' and acadsemester = '" . $acadsemester . "'  order by room_id";
				$resRoom = mysqli_query($_connection,$sql_Room);			
			?>
		  	<select name="roomID" class="inputboxUpdate">
		  	<option> &nbsp; &nbsp; &nbsp; </option>
			<?php
				while($dat = mysqli_fetch_assoc($resRoom))
				{
					$_select = (isset($_POST['roomID'])&&$_POST['roomID'] == $dat['room_id']?"selected":"");
					echo "<option value=\"" . $dat['room_id'] . "\" $_select>";
					echo getFullRoomFormat($dat['room_id']);
					echo "</option>";
				}
				mysqli_free_result($resRoom);
			?>
			</select> <input type="submit" name="search" value="เรียกดู" class="button" /><br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=isset($_POST['studstatus'])=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
		 </form>
		 </font>
	  </td>
    </tr>
  </table>

<? if(isset($_POST['search'])){ ?>
<?	$_sql = "
				SELECT
				id,
				prefix,
				firstname,
				lastname,
				xlevel,
				xyearth,
				room,
				studstatus,
				x.timecheck_id,
				p1,p2,p3,p4,p5,p6,p7,p8
			FROM
				students s 
				inner join 
							(
								select student_id 
								from student_learn 
								where 
									check_date = '" . $_POST['date'] . "' and
									class_id = '" . $_POST['roomID'] ."'
							) as tmp on (s.id = student_id)
				left join 
							student_800 x on (
												s.id 		= x.student_id and 
												acadyear 	= '". $acadyear . "' and 
												acadsemester = '" . $acadsemester . "' and 
												check_date 	= '" . $_POST['date'] . "' and 
												class_id 	= '" . $_POST['roomID'] ."'
											  )
				left join 
							(
								select student_id,
										SUM(IF(period = 1, timecheck_id, NULL)) AS p1,
										SUM(IF(period = 2, timecheck_id, NULL)) AS p2,
										SUM(IF(period = 3, timecheck_id, NULL)) AS p3,
										SUM(IF(period = 4, timecheck_id, NULL)) AS p4,
										SUM(IF(period = 5, timecheck_id, NULL)) AS p5,
										SUM(IF(period = 6, timecheck_id, NULL)) AS p6,
										SUM(IF(period = 7, timecheck_id, NULL)) AS p7,
										SUM(IF(period = 8, timecheck_id, NULL)) AS p8
								from student_learn
								where
									check_date		= '" . $_POST['date'] . "' AND 
									acadyear 		= '". $acadyear . "' AND 
									acadsemester 	= '" . $acadsemester . "' AND 
									class_id 		= '" . $_POST['roomID'] ."' 
								group by student_id
							) as pp
				on (s.id = pp.student_id)
			WHERE
				xEDBE = '". $acadyear . "'   AND 
				xLevel = '" . getXlevel($_POST['roomID']) . "' AND 
				xYearth = '". getXyearth($_POST['roomID']) ."' AND
				room = '" . getRoom($_POST['roomID']) ."' ";


	if(isset($_POST['studstatus'])=="1,2") $_sql .= " and studstatus in (1,2) ";				
	$_sql .= " 	GROUP BY
				    id
				ORDER BY
					xlevel,
					xyearth,
					room,
					sex,
					convert(FIRSTNAME using tis620),
					convert(LASTNAME using tis620) ";

	$_res = mysqli_query($_connection,$_sql); ?>
	<? if(@mysqli_num_rows($_res)>0) { ?>
		<div align="center">
			<table class="admintable"   align="center"  >
			<tr>
			<th colspan="12" align="center">
				<img src="../images/school_logo.png" width="120px">
				<br/>
				รายงานการเข้าชั้นเรียนของนักเรียนชั้นมัธยมศึกษาปีที่ <?=getFullRoomFormat($_POST['roomID'])?><br/>
				ประจำวัน <?=displayDayofWeek(date('w',strtotime($_POST['date'])))?> ที่ <?=displayFullDate($_POST['date'])?><br/>
			  </th>
			</tr>
			<?php
				$_sql_period = "
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
							l.task_date = '" . $_POST['date'] . "'
						)
						left join teachers c on (t.teacher_id = c.teacher_id)
					where
						1=1
						and l.task_date		= '" . $_POST['date'] . "' 
						and l.task_roomid 	= '" . $_POST['roomID'] . "' 
					order by
						l.task_roomid,l.period";

				$_period = array();
				$_res_period = mysqli_query($_connection,$_sql_period);
				$_i = 0;
				if(mysqli_num_rows($_res_period)>0){
					while($_dat = mysqli_fetch_assoc($_res_period)){
						$_period[$_i] = $_dat;
						$_i++;
					}
				}

				
				
			?>
			<tr> 
				<td class="key" width="35px" align="center" rowspan="2">เลขที่</td>
				<td class="key" width="65px" align="center" rowspan="2">เลขประจำตัว</td>
				<td class="key" width="190px" align="center" rowspan="2">ชื่อ - นามสกุล</td>
				<td class="key" width="90px"  align="center" rowspan="2">สถานภาพ<br/>ปัจจุบัน</td>
				<td class="key" width="65px"  align="center" rowspan="2">หน้าเสาธง</td>
				<td class="key" align="center" colspan="8">คาบเรียนที่</td>
			</tr>
			<tr align="center">
				<?php
				for($_i=0;$_i<count($_period);$_i++){
					if(@$_period[$_i-1]['period']!=$_period[$_i]['period']) { 
						echo "<td align='center' valign='top' class='key' width='60px'>";
						$_period_initial = $_period[$_i];

						echo $_period[$_i]['period'] . "<br/>";
						echo $_period[$_i]['SubjectCode'] . "<br/>";
						echo $_period[$_i]['firstname']   . "<br/>";
					}else{
						echo "<br/>";
						echo $_period[$_i]['SubjectCode'] . "<br/>";
						echo $_period[$_i]['firstname']   . "<br/>";
					}
					
					if(@$_period[$_i+1]['period']!=$_period[$_i]['period']){
						echo "</td>";
					}
				}
				?>
			</tr>
			<? $_i = 1; ?>
			<? while($_dat = mysqli_fetch_assoc($_res)){ ?>
			<tr>
				<td align="center"><?=$_i++?></td>
				<td align="center"><?=$_dat['id']?></td>
				<td><?=$_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname']?></td>
				<td align="center"><?=displayStudentStatusColor($_dat['studstatus'])?></td>
				<td align="center" bgcolor="#F4FFEF"><?=displayTimecheckColor($_dat['timecheck_id'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p1'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p2'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p3'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p4'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p5'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p6'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p7'])?></td>
				<td align="center"><?=displayTimecheckColor($_dat['p8'])?></td>
			</tr>
			<? } mysqli_free_result($_res); //end while ?>
		 </table>
		</div>
<?	} else { //end chekc_rows	 ?>
		<table width="100%" align="center">
			<tr>
				<td align="center"><font color="#FF0000"><br/>ไม่พบข้อมูลที่ค้นตามเงื่อนไข กรุณาลองใหม่อีกครั้ง</font></td>
			</tr>
		</table>
<?	} //end else ?>
<? } //end if($_POST[''] ?>  

</div>
