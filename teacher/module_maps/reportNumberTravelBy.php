﻿<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      	<td width="6%" align="center"><a href="index.php?option=module_maps/index"><img src="../images/add.png" alt="" width="54" height="50" border="0" /></a></td>
    	<td><strong><font color="#990000" size="4">แผนที่ติดตามที่อยู่</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>2.2.1 สารสนเทศวิธีการเดินทางมาโรงเรียน<br/> จำนวนนักเรียน แยกตามระดับชั้น</strong></font></span></td>
		<td>
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_maps/reportNumberTravelBy&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_maps/reportNumberTravelBy&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		</font>
		<br/>
	  	<font color="#000000" size="2">
		เลือกระดับชั้น
		  	<select name="roomID" class="inputboxUpdate">
		  		<option value=""></option>
				<option value="3/1" <?=isset($_POST['roomID'])&&$_POST['roomID']=="3/1"?"selected":""?>> มัธยมศึกษาปีที่ 1 </option>
				<option value="3/2" <?=isset($_POST['roomID'])&&$_POST['roomID']=="3/2"?"selected":""?>> มัธยมศึกษาปีที่ 2 </option>
				<option value="3/3" <?=isset($_POST['roomID'])&&$_POST['roomID']=="3/3"?"selected":""?>> มัธยมศึกษาปีที่ 3 </option>
				<option value="4/1" <?=isset($_POST['roomID'])&&$_POST['roomID']=="4/1"?"selected":""?>> มัธยมศึกษาปีที่ 4 </option>
				<option value="4/2" <?=isset($_POST['roomID'])&&$_POST['roomID']=="4/2"?"selected":""?>> มัธยมศึกษาปีที่ 5 </option>
				<option value="4/3" <?=isset($_POST['roomID'])&&$_POST['roomID']=="4/3"?"selected":""?>> มัธยมศึกษาปีที่ 6 </option>
				<option value="all" <?=isset($_POST['roomID'])&&$_POST['roomID']=="all"?"selected":""?>> ทั้งโรงเรียน </option>
			</select>  
	  		<input type="submit" value="เรียกดู" class="button" name="search"/> <br/>
			<input type="checkbox" name="studstatus" value="1,2" <?=isset($_POST['studstatus'])=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
			 </font>
	   </td>
    </tr>
  </table>
  </form>
<? if(isset($_POST['search']) && $_POST['roomID'] == ""){ ?>
		<center><font color="#FF0000"><br/>กรุณาเลือก ระดับชั้น ที่ต้องการเรียกดูข้อมูลก่อน</font></center>
<? } //end if ?>


 <?php
  if(isset($_POST['search']) && $_POST['roomID'] != "") {
  $_sql = "";
  $_totalStudent = 0;
  if($_POST['roomID']=="all")
  {
  	$_sql = "select travelby,count(*)as c from students where xedbe = '" . $acadyear . "'  ";
	if(isset($_POST['studstatus']) && $_POST['studstatus']=="1,2") $_sql .= "and studstatus in (1,2) ";
	$_sql .= " group by travelby order by 1 ";
	$_totalStudent = mysqli_fetch_assoc(mysqli_query($_connection,"select count(*) as total from students where xedbe = '" . $acadyear . "'" . ($_POST['studstatus']=="1,2"?"and studstatus in (1,2)":"")));
  }
  else
  {
  	$_sql = "select travelby,count(*)as c from students 
				where xedbe = '" . $acadyear . "' and xlevel = '" . substr($_POST['roomID'],0,1) . "' 
					and xyearth = '" . substr($_POST['roomID'],2,1) . "' ";
	if(isset($_POST['studstatus']) && $_POST['studstatus']=="1,2") $_sql .= "and studstatus in (1,2) ";
	$_sql .= " group by travelby order by 1 ";
	$_totalStudent = mysqli_fetch_assoc(mysqli_query($_connection,"select count(*) as total from students where xedbe = '" . $acadyear . "' and xlevel = '" . substr($_POST['roomID'],0,1) . "' and xyearth = '" . substr($_POST['roomID'],2,1) . "' " . ($_POST['studstatus']=="1,2"?"and studstatus in (1,2)":"")));
  }
  $_result = mysqli_query($_connection,$_sql);
  if(mysqli_num_rows($_result)>0) {
  ?>
  <table class="admintable" width="100%" align="center">
    <tr> 
      <th align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			รายงานแสดงวิธีการเดินทางมาโรงเรียน <br/>
			ชั้นมัธยมศึกษาปีที่ <?=displayXyear($_POST['roomID'])?><br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
	<tr>
		<td align="center">
			<table class="admintable">
				<tr> 
					<td class="key" width="200px" align="center">วิธีการเดินทาง</td>
					<td class="key" width="120px" align="center">จำนวนนักเรียน<br/>(คน)</td>
					<td class="key" width="120px" align="center">ร้อยละ</td>
				</tr>
				<?  $_Point = 0; $_sumTime = 0;  ?>
				<?	while($_dat = mysqli_fetch_assoc($_result)){ ?>
					<tr>
						<td style="padding-left:15px;" align="left"><?=displayTravel($_dat['travelby'])?></td>
						<td style="padding-right:45px;" align="right"><?=number_format($_dat['c'],0,'.',',')?></td>
						<td style="padding-right:45px;" align="right"><?=number_format(($_dat['c']/$_totalStudent['total'])*100,'2','.',',')?></td>
						<? $_Point += $_dat['travelby'] * $_dat['c'] ; $_sumTime += $_dat['c'];?>
					</tr>	
				<?	} mysqli_free_result($_result); ?>
					<tr height="30px">
						<td class="key" align="center" colspan="2">รวม : <?=number_format($_totalStudent['total'],0,'.',',')?> คน</td>
						<td></td>
					</tr>
			</table>
		</td>
	</tr>
</table>
<? } // end if 
	else
	{  ?>
	<center><font color="#FF0000"><br/>ยังไม่มีข้อมูลตามเงื่อนไขที่ค้นหา</font></center>
<? }} // end else ?>  
</div>

