﻿<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
	<tr> 
	  <td width="6%" align="center" valign="top"><a href="index.php?option=module_color/index"><img src="../images/color.png" alt="กิจกรรมคณะสี" width="48" height="48" border="0"/></a></td>
	  <td valign="top"><strong><font color="#990000" size="4">กิจกรรมคณะสี</font></strong><br />
		<span class="normal"><font color="#0066FF"><strong>3.1.3 รายชื่อนักเรียนตามคณะสีตามระดับการศึกษา</strong></font></span></td>
	  <td align="right">
		<?php
				if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
				if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
			?>
			ปีการศึกษา<?php  
						echo "<a href=\"index.php?option=module_color/studentListByColor&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
						echo '<font color=\'blue\'>' .$acadyear . '</font>';
						echo " <a href=\"index.php?option=module_color/studentListByColor&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
					?>
			ภาคเรียนที่   <?php 
						if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
						else {
							echo " <a href=\"index.php?option=module_color/studentListByColor&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
						}
						if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
						else {
							echo " <a href=\"index.php?option=module_color/studentListByColor&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
						}
				?>
		</font>
	  <form method="post">
	  <font color="#330033"   size="2">
	  	คณะสี <select name="color" class="inputboxUpdate">
				<option value=""></option>
				<option value="ม่วง"  <?=isset($_POST['color'])&&$_POST['color']=="ม่วง"?"selected":""?>>ม่วง</option>
				<option value="เหลือง" <?=isset($_POST['color'])&&$_POST['color']=="เหลือง"?"selected":""?>>เหลือง</option>
				<option value="เขียว"  <?=isset($_POST['color'])&&$_POST['color']=="เขียว"?"selected":""?>>เขียว</option>
				<option value="ชมพู"  <?=isset($_POST['color'])&&$_POST['color']=="ชมพู"?"selected":""?>>ชมพู</option>
				<option value="ส้ม"   <?=isset($_POST['color'])&&$_POST['color']=="ส้ม"?"selected":""?>>ส้ม</option>
			 </select>
			 <input type="submit" name="submit" value="เรียกดู" class="button" /><br/>
		ระดับชั้น 	 
			 <input type="radio" name="level" value="3" <?=$_POST['level']!=4?"checked":""?> /> ม.ต้น |
			 <input type="radio" name="level" value="4" <?=$_POST['level']==4?"checked":""?> /> ม.ปลาย<br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=$_POST['studstatus']=="1,2"?"checked='checked'":""?> />
				เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
			 </font>
	  </form>
	  </td>
	</tr>
</table><br/>
<? if(isset($_POST['submit']) && ($_POST['level'] == "" || $_POST['color'] == "")) { ?>
		<center><font color="#FF0000"><br/>กรุณาเลือก คณะสี และ ระดับชั้นที่ต้องการทราบข้อมูลก่อน</font></center>
<? } //end if ?>


<? if(isset($_POST['submit']) && $_POST['level'] !=  "" && $_POST['color'] != "") { ?>
		<? $_sql = "select id,prefix,firstname,lastname,nickname,xyearth,room,studstatus from students 
						where xlevel = '" . $_POST['level'] . "' and color = '" . $_POST['color'] . "' " .($_POST['studstatus']=="1,2"?" and studstatus in (1,2) ":"") . "
							and xedbe = '" . $acadyear . "' order by xyearth,room,id"; ?>
		<?	$_result = mysqli_query($_connection,$_sql); ?>
		<? 	if($_result) { ?>
				<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center" width="100%" >
					<tr>
						<th align="center" colspan="6">
							<img src="../images/school_logo.png" width="120px">
							<br/>
							รายชื่อนักเรียนคณะสี<?=$_POST['color']?><br/>
							ระดับชั้นมัธยมศึกษาตอน<?=($_POST['level']==4)?"ปลาย":"ต้น"?>
							<br/>
							ประจำภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
							<br/>
						</th>
					</tr>
					<tr height="35px" > 
						<td class="key" width="50px" align="center">เลขที่</td>
						<td class="key" width="110px" align="center" >เลขประจำตัว</td>
						<td class="key" width="250px" align="center" >ชื่อ - นามสกุล</td>
						<td class="key" width="100px" align="center">ชื่อเล่น</td>
						<td class="key" align="center" width="70px">ห้อง</td>
						<td class="key" align="center" >สถานภาพปัจจุบัน</td>
					</tr>
					<?	$_i = 1; ?>
					<?	while($_dat = mysqli_fetch_assoc($_result)) { ?>
					<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
						<td align="center"><?=$_i++?></td>
						<td align="center"><?=$_dat['id']?></td>
						<td ><?=$_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname'] ?></td>
						<td align="center"><?=$_dat['nickname']==""?"-":$_dat['nickname']?>
						<td align="center"><?=(($_POST['level']==4)?(($_dat['xyearth']+3)."/".$_dat['room']):($_dat['xyearth']."/".$_dat['room']))?></td>
						<td align="center"><?=displayStatus($_dat['studstatus'])?></td>
					</tr>
					<? } //end-while ?>
				</table>
			<? } else { ?> <center><font color="red"><br/>ไม่พบข้อมูลที่ต้องการตามเงื่อนไข</font></center> <? } ?>
	<?	} // end if ?>
		
</div>
<?php
	function displayStatus($id)
	{
		switch ($id) {
			case 0 :  return "<font color='red'><b>ออก</b></font>"; break;
			case 1 :  return "ปกติ"; break;
			case 2 :  return "<b>สำเร็จการศึกษา</b>"; break;
			case 3 :  return "<font color='red'><b>แขวนลอย</b></font>"; break;
			case 4 :  return "<font color='darkorange'><b>พักการเรียน</b></font>"; break;
			case 5 :  return "<font color='blue'><b>ย้ายสถานศึกษา</b></font>"; break;
			case 9 :  return "<font color='red'><b>เสียชีวิต</b></font>"; break;
			default : return " - ไม่ทราบ - ";
		}	
	}
?>