﻿<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      	<td width="6%" align="center"><a href="index.php?option=module_maps/index"><img src="../images/add.png" alt="" width="54" height="50" border="0" /></a></td>
    	<td><strong><font color="#990000" size="4">แผนที่ติดตามที่อยู่</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>4.2 รายการแก้ไขข้อมูลเพิ่มเติมเกี่ยวกับแผนที่และที่อยู่อาศัย<br/>ของนักเรียน [รายชื่อตามหมู่บ้าน]</strong></font></span></td>
		<td>
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			else if(isset($_POST['acadyear'])) { $acadyear = $_POST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
			else if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_maps/studentListEditVillage&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_maps/studentListEditVillage&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		</font>
		<br/>
	  		<font color="#000000"  size="2" >เลือกหมู่บ้าน
			<select name="p_village" class="inputboxUpdate">
				<?	$_resultVillage = mysqli_query($_connection,"select distinct trim(p_village) as p_village from students where xEDBE = '" . $acadyear . "' order by 1");
					while($_datVillage = mysqli_fetch_assoc($_resultVillage))
					{  ?>
						<option value="<?=$_datVillage['p_village']?>" <?=isset($_POST['p_village'])&&$_POST['p_village']==$_datVillage['p_village']?"selected":""?> > <?=$_datVillage['p_village']?> </option>
				<?	} mysqli_free_result($_resultVillage) ?>
			</select>
	  		<input type="submit" value="เรียกดู" class="button" name="search"/> <br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=isset($_POST['studstatus'])=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา
		 </font>
	   </td>
    </tr>
  </table>
  </form>
  
<? if(isset($_POST['search'])) { ?>  
  <table class="admintable" width="100%"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="9" align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			รายชื่อหมู่บ้าน <?=$_POST['p_village']?> <br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
    <tr> 
		<td class="key" width="35px" align="center">เลขที่</td>
      	<td class="key" width="80px" align="center">เลขประจำตัว</td>
      	<td class="key" width="200px" align="center">ชื่อ - นามสกุล</td>
		<td class="key" width="80px" align="center">ห้องเรียน</td>
      	<td class="key" width="100px"  align="center">สถานภาพ<br/>ปัจจุบัน</td>
		<td class="key" align="center">แก้ไขรูปบ้าน</td>
		<td class="key" align="center">แก้ไขรูปบิดา</td>
		<td class="key" align="center">แก้ไขรูปมารดา</td>
		<td class="key" align="center">แก้ไขรูป<br/>ผู้ปกครอง</td>
    </tr>
	<?php
		$sqlStudent = "select id,prefix,firstname,lastname,studstatus,xlevel,xyearth,room
						 from students 
						 where p_village = '" . $_POST['p_village'] . "' and xedbe = '" . $acadyear . "' ";
		if(isset($_POST['studstatus'])=="1,2") $sqlStudent .= " and studstatus in (1,2) ";
		$sqlStudent .= " order by xlevel,xyearth,room,sex,id ";
		
		$resStudent = mysqli_query($_connection,$sqlStudent);
		$ordinal = 1;
		$totalRows = mysqli_num_rows($resStudent);
		for($i = 0; $i < $totalRows ; $i++)	{ $dat = mysqli_fetch_array($resStudent); ?>
			<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF" >
				<td align="center"><?=$ordinal++?></td>
				<td align="center"><?=$dat['id']?></td>
				<td><?=$dat['prefix'] . $dat['firstname'] . " " . $dat['lastname']?></td>
				<td align="center"><?=($dat['xlevel']==3?$dat['xyearth']:$dat['xyearth']+3) . '/' . $dat['room']?></td>
				<td align="center"><?=displayStudentStatusColor($dat['studstatus'])?></td>
				<td align="center">
					<a href="index.php?option=module_maps/updateHomePics&p_village=<?=$_POST['p_village']?>&acadsemester=<?=$acadsemester?>&acadyear=<?=$acadyear?>&student_id=<?=$dat['id']?>&report=studentListEditVillage">
						<?
							$_homeImage   = $_student_img_home_path   . "id" . $dat['id'] . ".jpg"; 
							$_fatherImage = $_father_img_path . "id" . $dat['id'] . ".jpg";
							$_motherImage = $_mother_img_path . "id" . $dat['id'] . ".jpg";
							$_parentImage = $_parent_img_path . "id" . $dat['id'] . ".jpg";

						?>
						<? if(file_exists($_homeImage)) { ?>
							<img src="../images/home.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปถ่ายบ้านพักของนักเรียน" />
						<? } else { ?>
							<img src="../images/delete.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปถ่ายบ้านพักของนักเรียน" />
						<? } ?>
					</a>
				</td>
				<td align="center">
				<a href="index.php?option=module_maps/updateFatherPics&p_village=<?=$_POST['p_village']?>&acadsemester=<?=$acadsemester?>&acadyear=<?=$acadyear?>&student_id=<?=$dat['id']?>&report=studentListEditVillage">
				<? if(file_exists($_fatherImage)) { ?>
					<img src="../images/apply.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปบิดาของนักเรียน" />
				<? } else { ?>
					<img src="../images/delete.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปบิดาของนักเรียน" />
				<? } ?>
				</a>
			</td>
			<td align="center">
				<a href="index.php?option=module_maps/updateMotherPics&p_village=<?=$_POST['p_village']?>&acadsemester=<?=$acadsemester?>&acadyear=<?=$acadyear?>&student_id=<?=$dat['id']?>&report=studentListEditVillage">
				<? if(file_exists($_motherImage)) { ?>
					<img src="../images/apply.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปมารดาของนักเรียน" />
				<? } else { ?>
					<img src="../images/delete.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปมารดาของนักเรียน" />
				<? } ?>
				</a>
			</td>
			<td align="center">
				<a href="index.php?option=module_maps/updateAPics&p_village=<?=$_POST['p_village']?>&acadsemester=<?=$acadsemester?>&acadyear=<?=$acadyear?>&student_id=<?=$dat['id']?>&report=studentListEditVillage">
				<? if(file_exists($_parentImage)) { ?>
					<img src="../images/apply.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปผู้ปกครองของนักเรียน" />
				<? } else { ?>
					<img src="../images/delete.png" width="16px" alt="คลิ๊กเพื่อแก้ไขรูปผู้ปกครองของนักเรียน" />
				<? } ?>
				</a>
			</td>
		</tr>
	<? } // end for?>
</table>
<? } // end submit ?>  
</div>

