<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>2.3 แสดงตารางเรียนตามห้องเรียน</strong></font></span></td>
      <td >
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_gpa/learnTablesRoom&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_gpa/learnTablesRoom&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/learnTablesRoom&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/learnTablesRoom&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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
			ตารางเรียนนักเรียนห้อง <?=getFullRoomFormat($_POST['roomID'])?><br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
    <tr> 
		<td class="key" width="85px" align="center" rowspan="2">วัน</td>
      	<td class="key" align="center" colspan="9">คาบเรียน</td>
    </tr>
    <tr>
		<td class="key" width="80px" align="center">1</td>
		<td class="key" width="80px" align="center">2</td>
		<td class="key" width="80px" align="center">3</td>
		<td class="key" width="80px" align="center">4</td>
		<td class="key" width="80px" align="center">พักเที่ยง</td>
		<td class="key" width="80px" align="center">5</td>
		<td class="key" width="80px" align="center">6</td>
		<td class="key" width="80px" align="center">7</td>
		<td class="key" width="80px" align="center">8</td>
    </tr>
	<?php


		$sqlStudent = "select t.*,
								u.user_account_firstname
						FROM
							teaching_schedule t left join users_account u
							on (t.teacher_id = u.user_account_id)
						WHERE
							t.room_id = '" . $_roomID . "'
							and t.acadyear = '" . $acadyear . "'
							and t.acadsemester = '" . $acadsemester . "'
						order BY
							weekday,period  ";
		
		// echo $sqlStudent;
		
		$resStudent = mysqli_query($_connection,$sqlStudent);
		$totalRows = mysqli_num_rows($resStudent);
		
		$_learnTable = array();


		if($totalRows>0){
			//echo "this has data.";

			$_counter = 0;
			$_dat = mysqli_fetch_assoc($resStudent);
			$_counter++;

			$_initWeekday = $_dat['weekday'];
			$_initPeriod  = $_dat['period'];
			$_initSubject = $_dat['SubjectCode'];

			for($_i=1;$_i<=5;$_i++){
				echo "<tr height='55px'>";
				echo "<td align='center' class='key'>" . displayDayofWeek($_i) . "</td>";
				for($_j=1;$_j<=8;$_j++){
					if($_initWeekday==$_i && $_initPeriod==$_j){
						echo "<td align='center'>";
						echo $_dat['SubjectCode'] . "<br/>" . "ครู". $_dat['user_account_firstname']."";
						$_initSubject = $_dat['SubjectCode'];

						if($_counter < $totalRows){
							$_dat = mysqli_fetch_assoc($resStudent);
							$_counter++;
							if($_dat['period'] == $_j){
								if($_initSubject == $_dat['SubjectCode']){
									echo "<br/>" . "ครู". $_dat['user_account_firstname'];
								}else{
									echo $_dat['SubjectCode'] . "<br/>" . "ครู". $_dat['user_account_firstname'];
								}
								
							}
							else{
								$_initPeriod = $_dat['period'];
								$_initWeekday = $_dat['weekday'];
							}
						}
						echo "</td>";
					}else {
						echo "<td align='center'> -";
						echo "</td>";
					}
					if($_j==4){
						echo "<td align='center'>-</td>";
					}
				}
				echo "</tr>";
			}

		}else { ?>
			<tr><td colspan="10" align="center"> ไม่พบการบันทึกข้อมูลตามเงื่อนไขที่สืบค้น </td></tr>
	<?	}
		?>
</table>
</div>
  <? }//end else-if
 
 // <tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
 
 ?>
</div>

