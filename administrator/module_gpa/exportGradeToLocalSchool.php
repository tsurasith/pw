

<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
?>

<?php

require_once("../include/SimpleXLSXGen.php");

if(isset($_POST["go_to_export"]))
{
   
	$_file_name  = "LC_";
	$_file_name .= $_POST['acadyear'] . "_" . $_POST['acadsemester'] . "_";
	$_file_name .= "m" . $_POST['roomID'] . "_" . $_POST['SubjectCode'];
	$_file_name .= ".xls";


	$_roomID = $_POST['roomID'];
	$xlevel  = 0;
	$xyearth = 0;
	$_year   = (int) substr($_roomID,0,1);

	if($_year<4){
		$xlevel = 3;
		$xyearth = $_year;
	}else{
		$xlevel = 4;
		$xyearth = $_year-3;
	}

	$room = substr($_roomID,-1);

	// set header
    //header("Content-Type: application/vnd.ms-excel");
    //header("Content-Disposition: attachment; filename=\"$_file_name\"");

	// excel columns header
	$_columns_header = array('StudentID','รหัส','คำนำหน้า','ชื่อ','นามสกุล','คะแนน','เกรด');

	// data
	$_data = array();
	$_data[] = $_columns_header;

	$_sql_export = "
			select
				l.StudentID,
				s.student_id,
				t.prefix,
				t.firstname,
				t.lastname,
				s.point_100,
				s.grade
			from 
				register_students s inner join students t
				on (
					 s.student_id = t.id and 
					 s.acadyear   = t.xedbe
					)
				left join tb_students l
				on (
					 convert(s.student_id using utf8) = convert(l.StudentCode using utf8)
					)
			where 
				s.SubjectCode  = '" . $_POST['SubjectCode'] . "' and 
				s.acadyear     = '" . $_POST['acadyear'] . "' and 
				s.acadsemester = '" . $_POST['acadsemester'] . "' and 
				t.xlevel       = '" . $xlevel . "' and
				t.xyearth      = '" . $xyearth . "' and 
				t.room         = '" . $room  . "'
			order by 
				t.sex,
				convert(t.firstname using tis620),
				convert(t.lastname  using tis620)
		";
	$_res = mysqli_query($_connection,$_sql_export);
	//echo $_sql_export . "<br/>";
	
	while($_dat = mysqli_fetch_assoc($_res)){
		//$_data[] = $_dat;
		$_tt = array();
		$_tt[0] = $_dat['StudentID'];
		$_tt[1] = $_dat['student_id'];
		$_tt[2] = $_dat['prefix'];
		$_tt[3] = $_dat['firstname'];
		$_tt[4] = $_dat['lastname'];

		if(is_numeric($_dat['point_100'])){
			$_tt[5] = (int)$_dat['point_100'];
		}else{
			$_tt[5] = $_dat['point_100'];
		}
		if(is_numeric($_dat['grade'])){
			if(is_int($_dat['grade'])){
				$_tt[6] = (int) $_dat['grade'];
			}else{
				$_tt[6] = substr($_dat['grade'],0,3);
				$_tt[6] = $_tt[6];
			}
		}else{
			$_tt[6] = trim($_dat['grade']);
		}
		$_data[] = $_tt;
	}

	$_target_file = "";
	$_target_file = $_target . $_local_school_folder . "/" . $_file_name;

	if(file_exists($_target_file)){
		unlink($_target_file);
	}
	
	$xlsx = SimpleXLSXGen::fromArray($_data);
	$xlsx->saveAs($_target_file); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 


}
?>

<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.5 ส่งออกผบการเรียนสำหรับ Local School</strong></font></span></td>
      <td >

		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_gpa/exportGradeToLocalSchool&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_gpa/exportGradeToLocalSchool&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/exportGradeToLocalSchool&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_gpa/exportGradeToLocalSchool&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
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


	$_sql_subject = "
			select
				rs.SubjectCode,
				cs.SubjectName,
				count(rs.student_id) as 'registered',
				sum(if(trim(rs.grade) != '',1,0)) as 'graded',
				sum(if(trim(rs.grade)  = '',1,0)) as 'ungrade',
				sum(if(rs.grade is null,1,0)) as 'ungrade2',
				cs.SubjectType,
				cs.SubjectUnit,
				cs.SubjectHour,
				cs.is_split_class
			from
				register_students rs inner join 
				(select id from students 
				 where xEDBE = '" . $acadyear . "' and xLevel = '" . $xlevel . "' and xyearth = '" . $xyearth . "' and room = '" . $room . "') ss 
				on (
					rs.student_id = ss.id 
					)
				left join curriculum_subjects cs 
				on (
					rs.SubjectCode = cs.SubjectCode
					)
			where
				rs.acadyear     = '" . $acadyear . "' and
				rs.acadsemester = '" . $acadsemester . "'
			group by
				rs.SubjectCode
			order by
				field(cs.SubjectType,'พื้นฐาน','เพิ่มเติม','กิจกรรมพัฒนาผู้เรียน'),
				cs.SubjectGroup,
				rs.SubjectCode
			";

	$_res_subject = mysqli_query($_connection,$_sql_subject);

	$_order = 1;
  ?>
  
<? if(isset($_POST['search']) && $_POST['roomID'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือกห้องเรียนก่อน !</font></center>
<? } else if (isset($_POST['search']) && $_POST['roomID'] != "") { ?>
	<div align="center">
		<? if(mysqli_num_rows($_res_subject) >0){ ?> 
			<table class="admintable">
				<tr height="35px">
					<td colspan="12" align="center">
						<img src="../images/school_logo.png" width="120px"><br/>
						รายวิชาและสรุปผลการเรียนห้อง <b><?=getFullRoomFormat($_POST['roomID'])?></b>
						ภาคเรียนที่ <b><?=$acadsemester?></b> ปีการศึกษา <b><?=$acadyear?></b>
					</td>
				</tr>
				<tr height="35px"> 
					<td class="key" width="25px"  align="center" rowspan="2">-</td>
					<td class="key" width="80px"  align="center" rowspan="2">รหัสวิชา</td>
					<td class="key" width="200px" align="center" rowspan="2">ชื่อวิชา</td>
					<td class="key" width="40px"  align="center" rowspan="2">นก.</td>
					<td class="key" width="40px"  align="center" rowspan="2">ชั่วโมง</td>
					<td class="key" width="80px"  align="center" rowspan="2">ประเภทวิชา</td>
					<td class="key" width="80px"  align="center" rowspan="2">รูปแบบ<br/>การสอน</td>
					<td class="key" width="40px"  align="center" rowspan="2">นร</td>
					<td class="key"               align="center" colspan="2">ผลการเรียน</td>
					<td class="key" align="center" rowspan="2" colspan="2">-</td>
				</tr>
				<tr>
					<td class="key" width="45px"  align="center">มี</td>
					<td class="key" width="45px"  align="center">ไม่มี</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res_subject)){ ?>
					<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
						<td align="center" valign="top"><?=$_order++?></td>
						<td align="center" valign="top">
							<?php
								//if($_init_subject_code != $_dat['SubjectCode']){
									echo $_dat['SubjectCode'];
								//}else{
								//	echo "";
								//}
								
							?>
						</td>
						<td align="left" valign="top">
							<?php
								echo $_dat['SubjectName'];
							?>
						</td>
						<td align="center" valign="top"><?=$_dat['SubjectUnit']?></td>
						<td align="center" valign="top"><?=$_dat['SubjectHour']?></td>
						<td align="center" valign="top"><?=strlen($_dat['SubjectType'])>30?"กิจกรรมฯ":$_dat['SubjectType']?></td>
						<td align="center" valign="top">
							<?php
								$_split_class = $_dat['is_split_class'];

								if($_split_class==0){
									echo "จัดตามห้อง";
								}else if($_split_class==1){
									echo "คละห้อง";
								}else{
									echo "คละระดับชั้น";
								}
							?>
						</td>
						<td align="right" valign="top" style="padding-right:15px"><?=$_dat['registered']?></td>
						<td align="right" valign="top" style="padding-right:15px"><?=$_dat['graded']?></td>
						<td align="right" valign="top" style="padding-right:15px"><?=$_dat['ungrade']+$_dat['ungrade2']?></td>
						<td align="center" valign="top">
							<!--<form method="post" action="module_gpa/exportXLS2.php">-->
							<form method="post" action="">
								<input type="hidden" name="SubjectCode"     value="<?=$_dat['SubjectCode']?>" />
								<input type="hidden" name="SubjectName"     value="<?=$_dat['SubjectName']?>" />
								<input type="hidden" name="roomID"          value="<?=$_POST['roomID']?>" />
								<input type="hidden" name="acadyear"        value="<?=$acadyear?>" />
								<input type="hidden" name="acadsemester"    value="<?=$acadsemester?>" />
								<input type="hidden" name="search"          value="สืบค้น"  />
								<input type="submit" name="go_to_export"    value="สร้าง Excel" />
							</form>
						</td>
						<td>
							<?php

								$_file_name  = "LC_";
								$_file_name .= $acadyear . "_" . $acadsemester . "_";
								$_file_name .= "m" . $_POST['roomID'] . "_" . $_dat['SubjectCode'];
								$_file_name .= ".xls";


								$_file_existing = $_target . $_local_school_folder . "/" . $_file_name;

								$_link = "". $_local_school_folder . "/" . $_file_name;

								if(file_exists($_file_existing)){
									echo "<a href=\"$_link\">";
									echo "Download";
									echo "</a>";
									echo " ";
									echo date("Y-m-d h:i",fileatime($_file_existing));
								}else{
									echo "-";
								}
								//echo $_file_existing;
							?>
						</td>
					</tr>
				<? } ?>
			</table>
		<? } else { 
				echo "<br/><br/>";
				echo "<font color='red'> ไม่พบรายวิชาที่มีผลการเรียน ขอให้กลับไปตรวจรายวิชาที่ลงทะเบียนและการส่งผลการเรียน </font>";
				echo "<br/><br/>";
		} ?>
	</div>
<? } //end else-if ?>
</div>

<?php
   // to fix SubjectCode is null
   /*
	  delete from register_students where grade is null and SubjectCode = 'ส20204';
	  delete from register_students where grade is null and SubjectCode = 'ส20206';
	  delete from register_students where grade is null and SubjectCode = 'ส30202';
 	  delete from register_students where grade is null and SubjectCode = 'ส30204';
      delete from register_students where grade is null and SubjectCode = 'ส30206';
   */
?>
