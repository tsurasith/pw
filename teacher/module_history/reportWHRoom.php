﻿<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_history/index"><img src="../images/users.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">สืบค้นประวัติ</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>2.1.1 รายชื่อนักเรียนตาม น้ำหนัก ส่วนสูง<br/> หมู่โลหิตและดัชนีมวลกาย (รายห้องเรียน)</strong></font></span></td>
      <td >
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_history/reportWHRoom&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_history/reportWHRoom&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_history/reportWHRoom&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_history/reportWHRoom&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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
	  		<input type="submit" value="เรียกดู" class="button" name="search"/> <br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=$_POST['studstatus']=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
			</font>
	   </td>
    </tr>
  </table>
  </form>
  <?php
  $xlevel = getXlevel($_POST['roomID']);
  $xyearth= getXyearth($_POST['roomID']);
  $room = getRoom($_POST['roomID']);
  ?>

<? if(isset($_POST['search']) && $_POST['roomID'] == ""){ ?>
	<br/><center><font color="#FF0000">กรุณาเลือกห้องเรียนที่ต้องการทราบข้อมูลก่อน !</font></center>
<? }else if (isset($_POST['search']) && $_POST['roomID'] != "") { ?>
  <table class="admintable" width="100%" cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="10" align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			ข้อมูลสุขภาพนักเรียนห้อง <?=getFullRoomFormat($_POST['roomID'])?><br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
    <tr> 
		<td class="key" width="35px" align="center">เลขที่</td>
      	<td class="key" width="75px" align="center">เลขประจำตัว</td>
      	<td class="key" width="205px" align="center">ชื่อ - นามสกุล</td>
      	<td class="key" width="100px"  align="center">สถานภาพ</td>
		<td class="key" width="50px" align="center">หมู่<br/>โลหิต</td>
		<td class="key" width="70px" align="center">น้ำหนัก<br/>(กก.)</td>
		<td class="key" width="70px" align="center">ส่วนสูง<br/>(ซม.)</td>
      	<td class="key" width="50px" align="center">BMI</td>
      	<td class="key" align="center">ความหมาย<br/>ดัชนีมวลกาย</td>
    </tr>
	<?php
		$sqlStudent = "select id,prefix,firstname,lastname,studstatus,blood_group,weight,height,bmi
						from students 
						where xlevel = '". $xlevel . "' and xyearth = '" . $xyearth . "' and room = '" . $room . "' 
								and xedbe = '" . $acadyear . "' ";
		if($_POST['studstatus']=="1,2") $sqlStudent .= " and studstatus in (1,2) ";
		$sqlStudent .= "order by sex,id ";
		
		$resStudent = mysqli_query($_connection,$sqlStudent);
		$ordinal = 1;
		$totalRows = mysqli_num_rows($resStudent);
		for($i = 0; $i < $totalRows ; $i++)
		{ ?>
			<? $dat = mysqli_fetch_array($resStudent); ?>
			<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">	
			<td align="center"><?=$ordinal++?></td>
			<td align="center">
				<? if($_SESSION['superAdmin']) { ?><a href="index.php?option=module_history/editStudentWH&student_id=<?=$dat['id']?>&acadyear=<?=$acadyear?>&roomID=<?=$_POST['roomID']?>">
						<?=$dat['id']?>
					</a>
				<? } else { ?>
						<?=$dat['id']?>
				<? } // end if-else ?>
			</td>
			<td><?=$dat['prefix'] . $dat['firstname'] . " " . $dat['lastname']?></td>
			<td align="center"><?=displayStudentStatusColor($dat['studstatus'])?></td>
			<td align="center"><?=$dat['blood_group']!=""?$dat['blood_group']:"-"?></td>
			<td align="center"><?=$dat['weight']>0?number_format($dat['weight'],1,'.',','):"-"?></td>
			<td align="center"><?=$dat['height']>0?number_format($dat['height'],2,'.',','):"-"?></td>
			<td align="center"><?=$dat['bmi']>0?$dat['bmi']:"-"?></td>
			<td align="center"><?=displayBMI($dat['bmi'])?></td>
			</tr>
	<?	} //end_for	?>
</table>
<? } //end if-else ?>  
</div>
