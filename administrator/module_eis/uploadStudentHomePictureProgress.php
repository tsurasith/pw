﻿<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear'])) { $acadyear = $_POST['acadyear']; }
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester']; }
?>


<div id="content">
<form action="" method="post">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>3.2 ความคืบหน้าการอัพโหลดรูปนักเรียน</strong></font></span></td>
    <td>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_eis/uploadStudentHomePictureProgress&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_eis/uploadStudentHomePictureProgress&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?> <br/>
		 <font size="2" color="#000000">
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
	  		<input type="submit" value="สืบค้น" class="button" name="search"/> <br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=isset($_POST['studstatus'])=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
			</font>
		<br/>
	   </td>
    </tr>
  </table>
  </form>
<?php


$_roomID = "";
$_roomID = isset($_POST['roomID'])?$_POST['roomID']:"";

$xlevel;
$xyearth;
if($_roomID != "all")
{
	$xlevel = substr($_roomID,0,1);;
  $xyearth = substr($_roomID,2,1);
}

?>
<? if(isset($_POST['search']) && $_POST['roomID'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือก ระดับชั้นที่ต้องการทราบข้อมูล !</font></center>
<? } else if (isset($_POST['search']) && $_POST['roomID'] != "") { ?>
<div align="center">
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="21" align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			รายงานความคืบหน้า การอัพโหลดรูปภาพที่อยู่อาศัย บิดา มารดาและผู้ปกครองของนักเรียน<br/>ประจำปีการศึกษา <?=$acadyear?><br/>
			ชั้นมัธยมศึกษาปีที่ 
			<?
			   if($_POST['roomID'] != "all") echo $xlevel==3?$xyearth:($xyearth+3);
			   else echo "ทั้งโรงเรียน";
			 ?><br/>
	  </th>
    </tr>
    <tr height="35px"> 
      	<td class="key" width="70px" align="center" rowspan="2">ห้องเรียน</td>
		<td class="key" width="70px" align="center" colspan="2">บ้าน</td>
		<td class="key" width="70px" align="center" colspan="2">ผู้ปกครอง</td>
		<td class="key" width="70px" align="center" colspan="2">บิดา</td>
		<td class="key" width="70px" align="center" colspan="2">มารดา</td>
	</tr>
	<tr>
		<td class="key" width="80px" align="center">อัพโหลด</td>
		<td class="key" width="80px" align="center">ยังไม่อัพโหลด</td>
	
		<td class="key" width="80px" align="center">อัพโหลด</td>
		<td class="key" width="80px" align="center">ยังไม่อัพโหลด</td>

		<td class="key" width="80px" align="center">อัพโหลด</td>
		<td class="key" width="80px" align="center">ยังไม่อัพโหลด</td>

		<td class="key" width="80px" align="center">อัพโหลด</td>
		<td class="key" width="80px" align="center">ยังไม่อัพโหลด</td>
    </tr>
	<?php
		
		$_homeUploadedTotal = 0;
		$_homeNotUploadTotal = 0;

		$_parentUploadedTotal = 0;
		$_parentNotUploadTotal = 0;

		$_fatherUploadedTotal = 0;
		$_fatherNotUploadTotal = 0;

		$_motherUploadedTotal = 0;
		$_motherNotUploadTotal = 0;
		
		$_roomSql = "
						select distinct xlevel,xyearth,room 
						from students
						where 
							1=1 ";

		if($_POST['roomID']!="all"){
			$_roomSql .= "  and xlevel = '" . $xlevel . "' and 
							xyearth = '" . $xyearth . "' and
							xedbe = '" . $acadyear . "' ";
		}
		if(isset($_POST['studstatus'])){
			$_roomSql .= " and studstatus in ('1','2') ";
		}
		$_roomSql .= " order by xlevel,xyearth,room ";
		
		$_resRoom = mysqli_query($_connection,$_roomSql);
		$_countRoom = 0;
		$_countRoom = mysqli_num_rows($_resRoom);

		if($_countRoom<1){
			echo "<tr><td colspan='3' align='center'><br/>ยังไม่มีข้อมูลห้องเรียนในภาคเรียนนี้<br/></td></tr>";
		}else
		{ ?>

			<? while($_datRoom = mysqli_fetch_assoc($_resRoom)) { ?>
				<tr>
					<td align="center">
						<?php
							$level = ($_datRoom['xlevel']==3?$_datRoom['xyearth']:($_datRoom['xyearth']+3));
							echo $level.'/'.$_datRoom['room'];
						?>
					</td>
					<?php
							$_studentListSql = "
							select id,room,xlevel,xyearth from students
							where
								xlevel = '" . $_datRoom['xlevel'] . "' and 
								xyearth = '" . $_datRoom['xyearth'] . "' and
								xedbe = '" . $acadyear . "' and
								room = '" . $_datRoom['room'] . "'
								";
							if(isset($_POST['studstatus'])){
								$_studentListSql .= " and studstatus in ('1','2') ";
							}
							$_homeUploaded = 0;
							$_homeNotUpload = 0;

							$_parentUploaded = 0;
							$_parentNotUpload = 0;

							$_fatherUploaded = 0;
							$_fatherNotUpload = 0;

							$_motherUploaded = 0;
							$_motherNotUpload = 0;

							$_resStudent = mysqli_query($_connection,$_studentListSql);

							while($_datStudent = mysqli_fetch_assoc($_resStudent)){
								$_homeImage   = $_student_img_home_path . "id" . $_datStudent['id'] . ".jpg";
								$_parentImage = $_parent_img_path . "id" . $_datStudent['id'] . ".jpg";
								$_fatherImage = $_father_img_path . "id" . $_datStudent['id'] . ".jpg";
								$_motherImage = $_mother_img_path . "id" . $_datStudent['id'] . ".jpg";
								
								if(file_exists($_homeImage)){
									$_homeUploaded++;
								}else{
									$_homeNotUpload++;
								}

								if(file_exists($_parentImage)){
									$_parentUploaded++;
								}else{
									$_parentNotUpload++;
								}
								if(file_exists($_fatherImage)){
									$_fatherUploaded++;
								}else{
									$_fatherNotUpload++;
								}
								if(file_exists($_motherImage)){
									$_motherUploaded++;
								}else{
									$_motherNotUpload++;
								}

							}
							$_homeUploadedTotal += $_homeUploaded;
							$_homeNotUploadTotal += $_homeNotUpload;

							$_parentUploadedTotal += $_parentUploaded;
							$_parentNotUploadTotal += $_parentNotUpload;

							$_fatherUploadedTotal += $_fatherUploaded;
							$_fatherNotUploadTotal += $_fatherNotUpload;

							$_motherUploadedTotal += $_motherUploaded;
							$_motherNotUploadTotal += $_motherNotUpload;
						?>
					<td align="center"><?=$_homeUploaded?></td>
					<td align="center"><?=$_homeNotUpload?></td>

					<td align="center"><?=$_parentUploaded?></td>
					<td align="center"><?=$_parentNotUpload?></td>

					<td align="center"><?=$_fatherUploaded?></td>
					<td align="center"><?=$_fatherNotUpload?></td>

					<td align="center"><?=$_motherUploaded?></td>
					<td align="center"><?=$_motherNotUpload?></td>
				</tr>
			<? } ?>
			<tr>
				<td></td>
				<td class="key" align="center"><?=$_homeUploadedTotal?></td>
				<td class="key" align="center"><?=$_homeNotUploadTotal?></td>

				<td class="key" align="center"><?=$_parentUploadedTotal?></td>
				<td class="key" align="center"><?=$_parentNotUploadTotal?></td>

				<td class="key" align="center"><?=$_fatherUploadedTotal?></td>
				<td class="key" align="center"><?=$_fatherNotUploadTotal?></td>

				<td class="key" align="center"><?=$_motherUploadedTotal?></td>
				<td class="key" align="center"><?=$_motherNotUploadTotal?></td>
			</tr>
<?		}
	?>
</table>
</div>
  <? } //end else-if ?>

</div>