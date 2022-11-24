<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.2 ผลสัมฤทธิ์นักเรียนตามห้องเรียน (แสดงผลการเรียนทั้งหมด)</strong></font></span></td>
      <td >
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_gpa/studentList&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_gpa/studentList&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/studentList&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/studentList&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
		<br/>
	  	<font  size="2" color="#000000">เลือกห้องเรียน
			<?php 
					$sql_Room = "select room_id from rooms where acadyear = '". $acadyear . "' and acadsemester = '" . $acadsemester . "'  order by room_id";
					//echo $sql_Room ;
					$resRoom = mysqli_query($_connection,$sql_Room);			
			?>
			<select name="roomID" class="inputboxUpdate">
				<option value=""></option>
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
	  		<input type="submit" value="สืบค้น" class="button" name="search"/> <br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=isset($_POST['studstatus'])=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
			</font>
	   </td>
    </tr>
  </table>
  </form>
  <?php

	  $_roomID = "";
	  $_roomID = isset($_POST['roomID'])?$_POST['roomID']:"";


	  $xlevel = getXlevel($_roomID);
	  $xyearth= getXyearth($_roomID);
	  $room = getRoom($_roomID);
  ?>
  
<? if(isset($_POST['search']) && $_POST['roomID'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือกห้องเรียนก่อน !</font></center>
<? } else if (isset($_POST['search']) && $_POST['roomID'] != "") { ?>
<div align="center">
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="21" align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			รายชื่อนักเรียนห้อง <?=getFullRoomFormat($_POST['roomID'])?><br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
    <tr> 
		<td class="key" width="35px" align="center" rowspan="2">เลขที่</td>
      	<td class="key" width="80px" align="center" rowspan="2">เลขประจำตัว</td>
      	<td class="key" width="195px" align="center" rowspan="2">ชื่อ - นามสกุล</td>
      	<td class="key" width="100px"  align="center" rowspan="2">สถานภาพ</td>
		<td class="key" width="95px" align="center" rowspan="2">คะแนน<br/>ความประพฤติ</td>
		<td class="key" align="center" colspan="14">ผลการเรียนไม่พึงประสงค์</td>
		<td class="key" width="70px" align="center" rowspan="2">-</td>
    </tr>
    <tr>
		<td class="key"  align="center">4.0</td>
		<td class="key"  align="center">3.5</td>
		<td class="key"  align="center">3.0</td>
		<td class="key"  align="center">2.5</td>
		<td class="key"  align="center">2.0</td>
		<td class="key"  align="center">1.5</td>
		<td class="key"  align="center">1.0</td>
      	<td class="key"  align="center">0</td>
      	<td class="key"  align="center">ร</td>
      	<td class="key"  align="center">มส.</td>
        <td class="key"  align="center">ผ</td>
		<td class="key"  align="center">มผ</td>
		<td class="key"  align="center">n/a</td>
		<td class="key"  align="center">รวม</td>
    </tr>
	<?php
		$sqlStudent = "select 
							id,prefix,firstname,lastname,studstatus,points,
							sum(if(Grade='4' or Grade = '4.0',1,0)) as 'grade40',
							sum(if(Grade='3.5',1,0)) as 'grade35',
							sum(if(Grade='3' or Grade = '3.0',1,0)) as 'grade30',
							sum(if(Grade='2.5',1,0)) as 'grade25',
							sum(if(Grade='2' or Grade = '2.0',1,0)) as 'grade20',
							sum(if(Grade='1.5',1,0)) as 'grade15',
							sum(if(Grade='1' or Grade = '1.0',1,0)) as 'grade10',
							sum(if(Grade='0' or Grade = '0.0',1,0)) as 'grade00',
							sum(if(Grade='ร',1,0)) as 'gradewait',
							sum(if(Grade='มส',1,0)) as 'gradedismiss',
							sum(if(Grade='ผ',1,0)) as 'gradepass',
							sum(if(Grade='มผ',1,0)) as 'gradenotpass',
							sum(if(trim(Grade)='',1,0)) as 'nograde',
							count(Grade) as 'total'
					   from 
					   		students s left join learn_grades g on (s.id = g.studentcode)				   
					   where 
					   		s.xlevel = '". $xlevel . "' and s.xyearth = '" . $xyearth . "' and 
							s.room = '" . $room . "'  and  s.xedbe = '" . $acadyear . "' ";
		if(isset($_POST['studstatus'])=="1,2") $sqlStudent .= " and studstatus in (1,2) ";
		$sqlStudent .= "group by s.id order by sex,convert(firstname using tis620), convert(lastname using tis620) ";
		
		// echo $sqlStudent;
		
		$resStudent = mysqli_query($_connection,$sqlStudent);
		$ordinal = 1;
		$totalRows = mysqli_num_rows($resStudent);
		for($i = 0; $i < $totalRows ; $i++) { ?>
		<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
			<? $dat = mysqli_fetch_array($resStudent); ?>
			<td align="center"><?=$ordinal++?></td>
			<td align="center"><?=$dat['id']?></td>
			<td><?=$dat['prefix'] . $dat['firstname'] . " " . $dat['lastname']?></td>
			<td align="center"><?=displayStudentStatusColor($dat['studstatus'])?></td>
			<td align="center"><?=displayPoint($dat['points'])?></td>
			<td align="center"><?=$dat['grade40']==0?"-":("<b>" . $dat['grade40'] . "</b>")?></td>
			<td align="center"><?=$dat['grade35']==0?"-":("<b>" . $dat['grade35'] . "</b>")?></td>
			<td align="center"><?=$dat['grade30']==0?"-":("<b>" . $dat['grade30'] . "</b>")?></td>
			<td align="center"><?=$dat['grade25']==0?"-":("<b>" . $dat['grade25'] . "</b>")?></td>
			<td align="center"><?=$dat['grade20']==0?"-":("<b>" . $dat['grade20'] . "</b>")?></td>
			<td align="center"><?=$dat['grade15']==0?"-":("<b>" . $dat['grade15'] . "</b>")?></td>
			<td align="center"><?=$dat['grade10']==0?"-":("<b>" . $dat['grade10'] . "</b>")?></td>
			<td align="center"><?=$dat['grade00']==0?"-":("<b><font color='red'>" . $dat['grade00'] . "</font></b>")?></td>
			<td align="center"><?=$dat['gradewait']==0?"-":("<b>" . $dat['gradewait'] . "</b>")?></td>
            <td align="center"><?=$dat['gradedismiss']==0?"-":("<b>" . $dat['gradedismiss'] . "</b>")?></td>
            <td align="center"><?=$dat['gradepass']==0?"-":("<b>" . $dat['gradepass'] . "</b>")?></td>
            <td align="center"><?=$dat['gradenotpass']==0?"-":("<b>" . $dat['gradenotpass'] . "</b>")?></td>
			<td align="center"><?=$dat['nograde']==0?"-":("<b>" . $dat['nograde'] . "</b>")?></td>
			<td align="center"><?=$dat['total']==0?"-":("<b>" . $dat['total'] . "</b>")?></td>
            <td align="center">
            	<a href="index.php?option=module_gpa/gradeDetailsAll&xlevel=<?=$xlevel?>&xyearth=<?=$xyearth?>&room=<?=$room?>&name=<?=$dat['prefix'] . $dat['firstname'] . " " . $dat['lastname']?>&student_id=<?=$dat['id']?>&previouspage=studentListGradeAll&roomID=<?=$_roomID?>">
                	รายละเอียด
                </a>
            </td>
		</tr>
	<? } //end for?>
</table>
</div>
  <? } //end else-if ?>
</div>

