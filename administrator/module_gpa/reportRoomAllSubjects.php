

<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
?>

<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.5 รายงานผลการเรียนตามห้องเรียน (ทุกรายวิชา) - จากการนำเข้าผลการเรียน</strong></font></span></td>
      <td >

		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_gpa/reportRoomAllSubjects&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_gpa/reportRoomAllSubjects&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/reportRoomAllSubjects&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/reportRoomAllSubjects&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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

	$_subject_columns = array();
	$_gpa = array();
	  
	$_sql_student = "
			SELECT 
				st.student_id,
				s.prefix,s.firstname,s.lastname,
		";

	$_sql_subject = "
			SELECT DISTINCT
				s.SubjectCode
				,c.SubjectName
				,c.SubjectUnit
				,c.SubjectType
			FROM
				register_students s inner join curriculum_subjects c 
				on (s.SubjectCode = c.SubjectCode)
			WHERE
				s.student_id IN
				(
				 SELECT id
				 FROM
					students
				 WHERE
					xlevel = '" . $xlevel . "' AND xyearth = '" . $xyearth . "' AND xedbe = '" . $acadyear . "' AND room = '" . $room . "'
				) and
				s.acadyear     = '" . $acadyear . "' and
				s.acadsemester = '" . $acadsemester . "'
			order BY
			FIELD(c.SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'),
				c.SubjectGroup,
				c.SubjectCode
	";

	$_res_subject = mysqli_query($_connection,$_sql_subject);

	if($_res_subject && mysqli_num_rows($_res_subject)>0){
		while($_s = mysqli_fetch_assoc($_res_subject)){
			$_sql_student .= "
			( 
				select grade from register_students g 
				where 
					g.SubjectCode  = '" . $_s['SubjectCode'] . "' and
					g.acadyear     = g.acadyear and 
					g.acadsemester = g.acadsemester and
					g.student_id   = st.student_id
			) as '" . $_s['SubjectCode'] . "', 			
			";

			 array_push($_subject_columns,$_s['SubjectCode']);
			 array_push($_gpa,$_s);
		}
	}
	$_sql_student .= "studstatus ";
	$_sql_student .= "
			from
				register_students st left join students s 
				on (st.student_id = s.id and st.acadyear = s.xedbe)
			where
				s.xLevel  = '" . $xlevel . "' and 
				s.xYearth = '" . $xyearth . "' and 
				s.room    = '" . $room ."' 
			group by st.student_id
			order by
				s.SEX, convert(s.firstname using tis620)

	";

  ?>
  
<? if(isset($_POST['search']) && $_POST['roomID'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือกห้องเรียนก่อน !</font></center>
<? } else if (isset($_POST['search']) && $_POST['roomID'] != "") { ?>

<div align="center">
	<?php
		//echo $_sql_student;
		//print_r($_subject_columns);
		//print_r($_gpa);
		$_cc = count($_subject_columns);
	?>
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="<?=$_cc+5?>" align="center">
	  		<img src="../images/school_logo.png" width="120px"><br/>
			ผลการเรียนนักเรียน ห้อง <?=getFullRoomFormat($_POST['roomID'])?><br/>
			ภาคเรียนที่ <?php echo $acadsemester; ?> ปีการศึกษา <?php echo $acadyear; ?>
	  </th>
    </tr>
    <tr> 
		<td class="key" width="30px" align="center" rowspan="2">เลขที่</td>
      	<td class="key" width="65px" align="center" rowspan="2">เลขประจำตัว</td>
      	<td class="key" width="195px" align="center" rowspan="2">ชื่อ - นามสกุล</td>
      	<td class="key" width="100px"  align="center" rowspan="2">สถานภาพ</td>
		<td class="key" align="center" colspan="<?=$_cc?>">ผลการเรียน</td>
		<td class="key" width="50px" align="center" rowspan="2">GPA</td>
    </tr>
    <tr>
		<? for($_i=0;$_i<$_cc;$_i++){ ?>
			<td class="key">
				<div style="writing-mode: vertical-rl; transform:rotate(-180deg);">
					<?php
						//echo substr(trim($_subject_columns[$_i]),0,1);
						echo $_subject_columns[$_i];
					?>
				</div>
			</td>
		<? } ?>
    </tr>
	<?php
		
		
		// echo $sqlStudent;
		
		$resStudent = mysqli_query($_connection,$_sql_student);
		$ordinal = 1;
		$totalRows = mysqli_num_rows($resStudent);


		for($i = 0; $i < $totalRows ; $i++) { ?>
		<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
			<? $dat = mysqli_fetch_array($resStudent); ?>
			<td align="center"><?=$ordinal++?></td>
			<td align="center"><?=$dat['student_id']?></td>
			<td><?=$dat['prefix'] . $dat['firstname'] . " " . $dat['lastname']?></td>
			<td align="center"><?=displayStudentStatusColor($dat['studstatus'])?></td>
			<?php
				// GPA calculation
				$_total_point = 0.0;
				$_total_unit  = 0.0;
			?>

			<? for($_j=0;$_j<$_cc;$_j++){ ?>
				<td align="center">
					<?php
						echo displayGrade($dat[$_subject_columns[$_j]]);

						if($_gpa[$_j]['SubjectType'] != "กิจกรรมพัฒนาผู้เรียน"){
							if($dat[$_subject_columns[$_j]] != ""){
								$_total_unit += $_gpa[$_j]['SubjectUnit'];
								if(is_numeric($dat[$_subject_columns[$_j]])){
									$_total_point += ($dat[$_subject_columns[$_j]] * $_gpa[$_j]['SubjectUnit']);
								}
							}
						}

					?>
				</td>
			<? } ?>
			<td align="center">
				<?php
					if($_total_unit != 0.0){
						echo number_format($_total_point/$_total_unit,2);
					}else{
						echo "-";
					}
					
				?>
			</td>
		</tr>
	<? } //end for?>
</table>
</div>
  <? } //end else-if ?>
</div>

