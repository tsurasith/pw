﻿<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      	<td width="6%" align="center"><a href="index.php?option=module_maps/index"><img src="../images/add.png" alt="" width="54" height="50" border="0" /></a></td>
    	<td><strong><font color="#990000" size="4">แผนที่ติดตามที่อยู่</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>1.1.2 รายชื่อนักเรียนตามหมู่บ้าน</strong></font></span></td>
		<td>
	  <?php
			if(isset($_REQUEST['acadyear'])){ $acadyear = $_REQUEST['acadyear']; }
			else if(isset($_POST['acadyear'])){ $acadyear = $_POST['acadyear']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_maps/studentListVillage&acadyear=" . ($acadyear - 1) .  "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_maps/studentListVillage&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		</font>
		<br/><font color="#000000" size="2">
			เลือกหมู่บ้าน <select name="p_village" class="inputboxUpdate">
				<?	$_resultVillage = mysqli_query($_connection,"select distinct trim(p_village) as p_village from students where xEDBE = '" . $acadyear . "' order by 1");
					while($_datVillage = mysqli_fetch_assoc($_resultVillage))
					{  ?>
						<option value="<?=$_datVillage['p_village']?>" <?=isset($_POST['p_village'])&&$_POST['p_village']==$_datVillage['p_village']?"selected":""?> > <?=$_datVillage['p_village']?> </option>
				<?	} mysqli_free_result($_resultVillage) ?>
			</select>
	  		<input type="submit" value="เรียกดู" class="button" name="search"/><br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=$_POST['studstatus']=="1,2"?"checked='checked'":""?> /> เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา </font>
	   </td>
    </tr>
  </table>
  </form>

<? if(isset($_POST['p_village'])) { ?>
  <table class="admintable" width="100%"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="9" align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			รายชื่อนักเรียนหมู่บ้าน  <?=$_POST['p_village']==""?"ไม่ระบุ":$_POST['p_village']?><br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?></th>
    </tr>
    <tr> 
		<td class="key" width="40px" align="center">เลขที่</td>
      	<td class="key" width="70px" align="center">เลขประจำตัว</td>
      	<td class="key" width="200px" align="center">ชื่อ - นามสกุล</td>
		<td class="key" width="60px" align="center">ห้องเรียน</td>
      	<td class="key" width="100px"  align="center">สถานภาพ<br/>ปัจจุบัน</td>
		<td class="key" width="150px" align="center">การเดินทางมาโรงเรียน</td>
      	<td class="key" width="80px" align="center">เวลาที่ใช้<br/>(นาที)</td>
      	<td class="key" width="70px"  align="center">แผนที่</td>
		<td class="key" width="70px" align="center">ภาพถ่าย</td>
    </tr>
	<?php
		$sqlStudent = "select id,prefix,firstname,lastname,xlevel,xyearth,room,
								howlong,travelby,utm_coordinate_x,utm_coordinate_y,studstatus,p_village
						 from students 
						 where xedbe = '" . $acadyear . "' and trim(p_village) = '" . $_POST['p_village'] . "'";
		if($_POST['studstatus']=="1,2") $sqlStudent .= " and studstatus in (1,2) ";
		$sqlStudent .= " order by xlevel,xyearth,room,sex ";
		$resStudent = mysqli_query($_connection,$sqlStudent);
		$ordinal = 1;
		$totalRows = mysqli_num_rows($resStudent);
		for($i = 0; $i < $totalRows ; $i++)
		{
			if($i % 2 == 0 ){echo "<tr>";}
			else{echo "<tr>";}
			$dat = mysqli_fetch_array($resStudent);
			echo "<td align=\"center\">$ordinal</td>";
			if($_SESSION['superAdmin']) { echo "<td align=\"center\"><a href='index.php?option=module_maps/updatemaps&report=studentListVillage&acadyear=" . $acadyear . "&student_id=" . $dat['id'] ."&p_village=" . $dat['p_village'] . "'>" . $dat['id'] . "</a></td>";}
			else {echo "<td align='center'>" . $dat['id'] . "</td>" ;}
			echo "<td>" . $dat['prefix'] . $dat['firstname'] . " " . $dat['lastname'] . "</td>";
			echo "<td align='center'>" . ($dat['xlevel']==3?$dat['xyearth']:$dat['xyearth']+3) . '/' . $dat['room'] . "</td>";
			echo "<td align=\"center\">" . displayStudentStatusColor($dat['studstatus']) . "</td>";
			echo "<td>" . displayTravel($dat['travelby']) . "</td>";
			echo "<td align=\"center\">" . $dat['howlong'] . "</td>";
			if(trim($dat['utm_coordinate_x']) != "" && trim($dat['utm_coordinate_y']) != ""){
				echo "<td align=\"center\">" ?>
							<a href="module_maps/maps.php?p_village=<?=$dat['p_village']?>&id=<?=$dat['id']?>
									 &stName=<?=$dat['prefix'].$dat['firstname'].'%20'.$dat['lastname']?>
									 &lat=<?=$dat['utm_coordinate_x']?>
									 &long=<?=$dat['utm_coordinate_y']?>" target="_blank">แผนที่</a>
		<?      echo  "</td>";
			}
			else
			{
				echo "<td align='center'>-</td>";
			}
			
			echo "<td align='center'>";
						$_homeImage = "/pk/images/studhome/id" . $dat['id'] . ".jpg";
						if(file_exists($_SERVER["DOCUMENT_ROOT"] . $_homeImage))
						{ 
							echo "<a href='module_maps/displayHomeImage.php?student_id=" . $dat['id'] . "' target='_blank'>";
							echo "แสดง";
							echo "</a>";
						}
						else 
						{
							echo "-";
						}
            echo "</td>";
			echo "</tr>";
			$ordinal++;
		}
	?>
	<tr>
		<td colspan="8">
        	<a href="module_maps/ztestMap.php?village=<?=$_POST['p_village']?>&acadyear=<?=$acadyear?>" target="_blank">
            	ดูแผนที่่ตามหมู่บ้าน
            </a>
        </td>
	</tr>
</table>
<? }  //end if check $_POST['p_village'] ?> 
</div>

